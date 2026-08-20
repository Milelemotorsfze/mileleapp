{{--
    Country <option> lists are populated lazily when a modal is opened.
    The pending list renders ~1000 VIN rows; pre-rendering 272 options into
    every country select would put ~270,000 <option> nodes on the page.
--}}
<style>
    /* Match the sm inputs the shipping rows use. */
    .js-claim-country + .select2-container .select2-selection--single {
        height: calc(1.5em + 0.5rem + 2px);
        border: 1px solid #ced4da;
        border-radius: 0.2rem;
    }
    .js-claim-country + .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: calc(1.5em + 0.5rem);
        padding-left: 0.5rem;
        font-size: 12px;
    }
    .js-claim-country + .select2-container .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.5rem);
    }
    /* Keep the dropdown above the modal backdrop. */
    .select2-container--open { z-index: 1060; }
</style>
@php
    // Explicit flags so the payload can never terminate this <script> block,
    // regardless of what a country name contains.
    $claimCountryJson = json_encode(
        $countries->map(function ($c) {
            return ['id' => $c->id, 'name' => $c->name];
        })->values(),
        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE
    );
@endphp
<script type="text/javascript">
    window.claimCountryOptions = {!! $claimCountryJson !!};

    (function () {
        var optionsHtml = null;

        function buildOptionsHtml() {
            if (optionsHtml !== null) {
                return optionsHtml;
            }
            var parts = ['<option value="">Select Country</option>'];
            for (var i = 0; i < window.claimCountryOptions.length; i++) {
                var c = window.claimCountryOptions[i];
                parts.push('<option value="' + c.id + '">' + $('<div>').text(c.name).html() + '</option>');
            }
            optionsHtml = parts.join('');
            return optionsHtml;
        }

        // Fill and enhance every country select inside a modal the first time
        // it is shown. dropdownParent is the modal itself so the searchable
        // dropdown escapes the .table-responsive overflow and sits above the
        // Bootstrap backdrop.
        window.populateClaimCountrySelects = function (container) {
            var $selects = $(container).find('select.js-claim-country').filter(function () {
                return !$(this).data('populated');
            });
            if ($selects.length === 0) {
                return;
            }
            var html = buildOptionsHtml();
            $selects.each(function () {
                var $s = $(this);
                $s.html(html);
                var selected = $s.attr('data-selected');
                if (selected) {
                    $s.val(selected);
                }
                $s.data('populated', true);

                if ($.fn.select2) {
                    $s.select2({
                        dropdownParent: $(container),
                        placeholder: 'Select Country',
                        allowClear: true,
                        width: '100%'
                    });
                }
            });
        };

        $(document).on('show.bs.modal', '.modal', function () {
            window.populateClaimCountrySelects(this);
        });

        // Bootstrap 5 traps focus inside the modal, which can stop the Select2
        // search box receiving keystrokes. Focus it explicitly on open.
        $(document).on('select2:open', function () {
            var field = document.querySelector('.select2-container--open .select2-search__field');
            if (field) {
                field.focus();
            }
        });
    })();
</script>
