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

    /* Copy-to-all-VINs button, revealed once its cell has a value. */
    .js-copy-cell > .js-copy-down {
        flex: 0 0 auto;
        margin-left: 4px;
        padding: 0.1rem 0.35rem;
        line-height: 1.2;
    }
    .js-copy-cell > .js-copy-source,
    .js-copy-cell > .js-copy-select-wrap { min-width: 0; }
    .js-copy-flash {
        background-color: #d1e7dd !important;
        transition: background-color 0.15s ease-in;
    }

    /* Blocks interaction with the modal while the claim is being submitted. */
    .js-submitting-overlay {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.6);
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.3rem;
    }
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
            window.refreshClaimCopyButtons(this);
        });

        // Bootstrap 5 traps focus inside the modal, which can stop the Select2
        // search box receiving keystrokes. Focus it explicitly on open.
        $(document).on('select2:open', function () {
            var field = document.querySelector('.select2-container--open .select2-search__field');
            if (field) {
                field.focus();
            }
        });

        // ---- submit guard ----

        // The form carries an inline onsubmit="return validateForm(id)". That
        // handler runs first and calls preventDefault() when validation fails,
        // so only show the loader once the submit is actually going through.
        $(document).on('submit', 'form[id^="docStatusForm_"]', function (e) {
            var $form = $(this);

            if (e.isDefaultPrevented()) {
                return; // validation rejected it - leave the button usable
            }

            if ($form.data('claim-submitting')) {
                e.preventDefault(); // already on its way, swallow the extra click
                return;
            }
            $form.data('claim-submitting', true);

            var $btn = $form.find('button[type="submit"]');
            $btn.data('original-html', $btn.html())
                .prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Saving...');

            // Also block Close/dismiss so the modal cannot be torn down mid-post.
            $form.closest('.modal-content')
                .css('position', 'relative')
                .append('<div class="js-submitting-overlay"></div>');
            $form.find('[data-bs-dismiss="modal"]').prop('disabled', true);
        });

        // Restore the form if the browser returns to a cached copy of the page,
        // otherwise the button would still be spinning after a Back navigation.
        $(window).on('pageshow', function (event) {
            if (!event.originalEvent || !event.originalEvent.persisted) {
                return;
            }
            $('form[id^="docStatusForm_"]').each(function () {
                var $form = $(this).removeData('claim-submitting');
                var $btn = $form.find('button[type="submit"]');
                if ($btn.data('original-html')) {
                    $btn.prop('disabled', false).html($btn.data('original-html'));
                }
                $form.closest('.modal-content').find('.js-submitting-overlay').remove();
                $form.find('[data-bs-dismiss="modal"]').prop('disabled', false);
            });
        });

        // ---- copy a filled cell down its column ----

        var COLUMN_SELECTORS = {
            container: '.js-shipping-container',
            bl: '.js-shipping-bl',
            country: 'select.js-shipping-country'
        };

        // The button only makes sense once its own cell has something to copy.
        function refreshCopyButton($source) {
            var $btn = $source.closest('.js-copy-cell').find('.js-copy-down');
            if ($btn.length === 0) {
                return;
            }
            var value = $source.val();
            var filled = value !== null && String(value).trim() !== '';
            $btn.toggle(filled);
        }

        window.refreshClaimCopyButtons = function (container) {
            $(container).find('.js-copy-cell .js-copy-source').each(function () {
                refreshCopyButton($(this));
            });
        };

        $(document).on('input change', '.js-copy-cell .js-copy-source', function () {
            refreshCopyButton($(this));
        });

        $(document).on('click', '.js-copy-down', function () {
            var $btn = $(this);
            var $source = $btn.closest('.js-copy-cell').find('.js-copy-source');
            var selector = COLUMN_SELECTORS[$btn.data('column')];
            if (!selector) {
                return;
            }

            var value = $source.val();
            var $targets = $btn.closest('.js-shipping-section').find(selector).not($source);
            if ($targets.length === 0) {
                return;
            }

            $targets.each(function () {
                var $t = $(this);
                $t.val(value);
                // Select2 mirrors the underlying select only on change.
                if ($t.is('select')) {
                    $t.trigger('change');
                }
                refreshCopyButton($t);

                // Brief highlight so it is obvious which cells were filled.
                var $flash = $t.is('select')
                    ? $t.closest('.js-copy-select-wrap').find('.select2-selection')
                    : $t;
                $flash.addClass('js-copy-flash');
                setTimeout(function () { $flash.removeClass('js-copy-flash'); }, 600);
            });
        });
    })();
</script>
