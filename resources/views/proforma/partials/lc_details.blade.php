@php
    use App\Models\QuotationLcDetail;

    /**
     * Letter of Credit requirement matrix.
     * Expects: $natureOfDeal (string), $lcDetail (QuotationLcDetail|null).
     * Shown only when "Letter of credit" is the selected Nature of Deal.
     */
    $lcDetail = $lcDetail ?? null;
    $lcVisible = ($natureOfDeal ?? 'regular_deal') === 'letter_of_credit';

    $lcValue = function ($field, $default = null) use ($lcDetail) {
        return old('lc_'.$field, $lcDetail->{$field} ?? $default);
    };

    $lcNumber = old('lc_number', $lcDetail->lc_number ?? '');
    $lcIssuingBank = old('lc_issuing_bank', $lcDetail->issuing_bank ?? '');
    $lcExpiryDate = old('lc_expiry_date', $lcDetail && $lcDetail->lc_expiry_date
        ? $lcDetail->lc_expiry_date->format('Y-m-d')
        : '');
    $lcComplianceStatus = old('lc_compliance_status', $lcDetail->compliance_status ?? 'pending');
    $lcComplianceRemarks = old('lc_compliance_remarks', $lcDetail->compliance_remarks ?? '');
    $lcOthersDetails = old('lc_doc_others_details', $lcDetail->doc_others_details ?? '');

    // Issuing bank is a dropdown, with a free-text fallback for banks not listed.
    $lcBankOptions = QuotationLcDetail::issuingBankOptions();
    $lcOtherBank = QuotationLcDetail::OTHER_BANK;
    $lcBankIsListed = filled($lcIssuingBank) && in_array($lcIssuingBank, $lcBankOptions, true);
    $lcBankIsOther = filled($lcIssuingBank) && ! $lcBankIsListed;
@endphp

<style>
    /* Attention pulse for the Letter of credit radio when it first becomes available.
       Uses box-shadow spread instead of padding so nothing on the row shifts. */
    #letter-of-credit-option {
        border-radius: 4px;
    }

    #letter-of-credit-option.lc-option-highlight {
        animation: lcOptionPulse 0.9s ease-in-out 4;
    }

    #letter-of-credit-option.lc-option-highlight .form-check-label {
        font-weight: 600;
        color: #9a6b00;
    }

    @keyframes lcOptionPulse {
        0%, 100% {
            background-color: transparent;
            box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
        }
        50% {
            background-color: #fff3cd;
            box-shadow: 0 0 0 5px #fff3cd;
        }
    }

    #lc-option-hint {
        margin-left: 4px;
        white-space: nowrap;
    }

    @media (prefers-reduced-motion: reduce) {
        /* Hold a steady highlight instead of flashing. */
        #letter-of-credit-option.lc-option-highlight {
            animation: none;
            background-color: #fff3cd;
            box-shadow: 0 0 0 5px #fff3cd;
        }
    }
</style>

<div class="row mt-2" id="lc-details-section" style="{{ $lcVisible ? '' : 'display: none;' }}">
    <div class="col-sm-12">
        <div class="card border mb-0">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <strong>Letter of Credit Details</strong>
                        <small class="text-muted">
                            &nbsp;Tracked on the LC Transactions view. The document checklist is optional — tick what
                            applies.
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_number">LC Number : <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control widthinput @error('lc_number') is-invalid @enderror"
                                       id="lc_number" name="lc_number" {{ $lcVisible ? 'required' : '' }}
                                       maxlength="100" value="{{ $lcNumber }}" placeholder="e.g. LC-2026-00123">
                                @error('lc_number')
                                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_issuing_bank_select">Issuing Bank : <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7">
                                <select class="form-select @error('lc_issuing_bank') is-invalid @enderror"
                                        id="lc_issuing_bank_select" {{ $lcVisible ? 'required' : '' }}>
                                    <option value="">Select a bank</option>
                                    @foreach ($lcBankOptions as $lcBankOption)
                                        <option value="{{ $lcBankOption }}" {{ $lcBankIsListed && $lcIssuingBank === $lcBankOption ? 'selected' : '' }}>
                                            {{ $lcBankOption }}
                                        </option>
                                    @endforeach
                                    <option value="{{ $lcOtherBank }}" {{ $lcBankIsOther ? 'selected' : '' }}>
                                        Other (type the bank name)
                                    </option>
                                </select>
                                {{-- Carries the bank name to the server, whether picked or typed. --}}
                                <input type="text" class="form-control widthinput mt-1"
                                       id="lc_issuing_bank" name="lc_issuing_bank"
                                       maxlength="150" value="{{ $lcIssuingBank }}"
                                       placeholder="Bank name"
                                       {{ $lcVisible && $lcBankIsOther ? 'required' : '' }}
                                       style="{{ $lcBankIsOther ? '' : 'display: none;' }}">
                                @error('lc_issuing_bank')
                                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_expiry_date">LC Expiry Date : <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" class="form-control widthinput @error('lc_expiry_date') is-invalid @enderror"
                                       id="lc_expiry_date"
                                       name="lc_expiry_date" {{ $lcVisible ? 'required' : '' }}
                                       value="{{ $lcExpiryDate }}">
                                @error('lc_expiry_date')
                                    <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-3">
                                Document Checklist :
                                <small class="text-muted d-block">Optional</small>
                            </div>
                            <div class="col-sm-9">
                                @foreach (QuotationLcDetail::DOCUMENTS as $lcDocColumn => $lcDocLabel)
                                    @php
                                        $lcDocChecked = (bool) $lcValue($lcDocColumn, false);
                                    @endphp
                                    <div class="form-check form-check-inline">
                                        <input type="hidden" name="lc_{{ $lcDocColumn }}" value="0">
                                        <input class="form-check-input lc-document-check" type="checkbox"
                                               id="lc_{{ $lcDocColumn }}" name="lc_{{ $lcDocColumn }}" value="1"
                                               {{ $lcDocChecked ? 'checked' : '' }}>
                                        <label class="form-check-label" for="lc_{{ $lcDocColumn }}">{{ $lcDocLabel }}</label>
                                    </div>
                                @endforeach
                                <div id="lc-others-details-wrapper" class="mt-2"
                                     style="{{ (bool) $lcValue('doc_others', false) ? '' : 'display: none;' }}">
                                    <label for="lc_doc_others_details" class="form-label mb-1">
                                        <small>Other document(s) — type the name or any note:</small>
                                    </label>
                                    <textarea class="form-control" id="lc_doc_others_details" name="lc_doc_others_details"
                                              rows="2" maxlength="1000"
                                              placeholder="e.g. Insurance Certificate, Beneficiary's Certificate, Weight List">{{ $lcOthersDetails }}</textarea>
                                </div>
                                <div class="mt-1">
                                    <small id="lc-documents-summary" class="text-muted"></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-5">
                                <label for="lc_compliance_status">Compliance Status :</label>
                            </div>
                            <div class="col-sm-7">
                                <select class="form-select" id="lc_compliance_status" name="lc_compliance_status">
                                    @foreach (QuotationLcDetail::COMPLIANCE_STATUSES as $lcStatusKey => $lcStatusLabel)
                                        <option value="{{ $lcStatusKey }}" {{ $lcComplianceStatus == $lcStatusKey ? 'selected' : '' }}>
                                            {{ $lcStatusLabel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="lc_compliance_remarks">Compliance Remarks :</label>
                            </div>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="lc_compliance_remarks" name="lc_compliance_remarks"
                                          rows="1" maxlength="1000"
                                          placeholder="Discrepancies, amendments or bank observations">{{ $lcComplianceRemarks }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Self-contained: only touches #lc-details-section, never the surrounding quotation form logic.
    (function () {
        function initLcSection() {
            var $section = $('#lc-details-section');
            if (!$section.length) {
                return;
            }

            // Letter of credit is only offered on a Proforma Invoice.
            function isProformaSelected() {
                var documentType = $('input[name="document_type"]:checked').val();
                return documentType === 'Proforma' || documentType === 'Proforma Invoice';
            }

            var lcOptionWasAvailable = null;
            var lcHighlightTimer = null;

            function highlightLcOption() {
                var $option = $('#letter-of-credit-option');
                if (!$option.length) {
                    return;
                }

                clearTimeout(lcHighlightTimer);
                $option.removeClass('lc-option-highlight');
                void $option[0].offsetWidth; // restart the animation on repeated toggles
                $option.addClass('lc-option-highlight');
                $('#lc-option-hint').stop(true, true).show();

                lcHighlightTimer = setTimeout(clearLcHighlight, 3800);
            }

            function clearLcHighlight(fade) {
                clearTimeout(lcHighlightTimer);
                $('#letter-of-credit-option').removeClass('lc-option-highlight');
                var $hint = $('#lc-option-hint').stop(true, true);
                if (fade === false) {
                    $hint.hide();
                } else {
                    $hint.fadeOut(400);
                }
            }

            function syncLcAvailability() {
                var lcAllowed = isProformaSelected();
                $('#letter-of-credit-option').toggle(lcAllowed);

                // Falling back to a regular deal keeps a hidden option from staying selected.
                if (!lcAllowed && $('#letter_of_credit').is(':checked')) {
                    $('#regular_deal').prop('checked', true);
                }

                // Announce the option only when it newly appears, never on page load.
                if (lcAllowed && lcOptionWasAvailable === false) {
                    highlightLcOption();
                } else if (!lcAllowed) {
                    clearLcHighlight(false);
                }
                lcOptionWasAvailable = lcAllowed;

                toggleLcSection();
            }

            function toggleLcSection() {
                var isLc = $('input[name="nature_of_deal"]:checked').val() === 'letter_of_credit';
                $section.toggle(isLc);

                // LC number and issuing bank are mandatory for a letter of credit.
                // The attribute has to come off when the panel is hidden: the browser
                // refuses to submit a form holding an invalid control it cannot focus.
                $('#lc_number, #lc_issuing_bank_select, #lc_expiry_date').prop('required', isLc);
                syncIssuingBank();
            }

            // The dropdown is the control; the text input is what actually posts,
            // so it mirrors the picked bank and is only typed into for "Other".
            function syncIssuingBank() {
                var $select = $('#lc_issuing_bank_select');
                var $input = $('#lc_issuing_bank');
                if (!$select.length) {
                    return;
                }

                var choice = $select.val();
                var isOther = choice === '{{ $lcOtherBank }}';
                var lcActive = $section.is(':visible');

                $input.toggle(isOther);
                $input.prop('required', lcActive && isOther);

                if (!isOther) {
                    $input.val(choice || '');
                }
            }

            // The checklist is optional, so this reads as information, never as an error.
            function refreshLcSummary() {
                var $checks = $('.lc-document-check');
                var total = $checks.length;
                var received = $checks.filter(':checked').length;
                var $summary = $('#lc-documents-summary');

                $('#lc-others-details-wrapper').toggle($('#lc_doc_others').is(':checked'));

                if (received === total) {
                    $summary.removeClass('text-muted').addClass('text-success')
                        .text('All ' + total + ' documents ticked.');
                } else {
                    var outstanding = [];
                    $checks.not(':checked').each(function () {
                        outstanding.push($('label[for="' + $(this).attr('id') + '"]').text().trim());
                    });
                    $summary.removeClass('text-success').addClass('text-muted')
                        .text(received + ' of ' + total + ' ticked. Not ticked: ' + outstanding.join(', ')
                            + ' (optional — does not block the shipment).');
                }
            }

            $(document).on('change', 'input[name="nature_of_deal"]', toggleLcSection);
            $(document).on('change', 'input[name="document_type"]', syncLcAvailability);
            $(document).on('change', '.lc-document-check', refreshLcSummary);
            $(document).on('change', '#lc_issuing_bank_select', function () {
                // Switching to "Other" clears the previously picked bank to type over.
                if ($(this).val() === '{{ $lcOtherBank }}') {
                    $('#lc_issuing_bank').val('');
                }
                syncIssuingBank();
                if ($(this).val() === '{{ $lcOtherBank }}') {
                    $('#lc_issuing_bank').trigger('focus');
                }
            });

            syncLcAvailability();
            refreshLcSummary();
        }

        if (window.jQuery) {
            jQuery(document).ready(initLcSection);
        } else {
            document.addEventListener('DOMContentLoaded', function () {
                if (window.jQuery) {
                    initLcSection();
                }
            });
        }
    })();
</script>
