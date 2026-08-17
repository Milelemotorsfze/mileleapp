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
@endphp

<div class="row mt-2" id="lc-details-section" style="{{ $lcVisible ? '' : 'display: none;' }}">
    <div class="col-sm-12">
        <div class="card border mb-0">
            <div class="card-body py-2">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <strong>Letter of Credit Details</strong>
                        <small class="text-muted">
                            &nbsp;Documentation is verified against this checklist before shipment is released.
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_number">LC Number :</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control widthinput" id="lc_number" name="lc_number"
                                       maxlength="100" value="{{ $lcNumber }}" placeholder="e.g. LC-2026-00123">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_issuing_bank">Issuing Bank :</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" class="form-control widthinput" id="lc_issuing_bank"
                                       name="lc_issuing_bank" maxlength="150" value="{{ $lcIssuingBank }}"
                                       placeholder="Bank name">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row mt-1">
                            <div class="col-sm-5">
                                <label for="lc_expiry_date">LC Expiry Date :</label>
                            </div>
                            <div class="col-sm-7">
                                <input type="date" class="form-control widthinput" id="lc_expiry_date"
                                       name="lc_expiry_date" value="{{ $lcExpiryDate }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-3">
                                Document Checklist :
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

            function toggleLcSection() {
                var isLc = $('input[name="nature_of_deal"]:checked').val() === 'letter_of_credit';
                $section.toggle(isLc);
            }

            function refreshLcSummary() {
                var $checks = $('.lc-document-check');
                var total = $checks.length;
                var received = $checks.filter(':checked').length;
                var $summary = $('#lc-documents-summary');

                if (received === total) {
                    $summary.removeClass('text-danger').addClass('text-success')
                        .text('All ' + total + ' documents received.');
                } else {
                    var missing = [];
                    $checks.not(':checked').each(function () {
                        missing.push($('label[for="' + $(this).attr('id') + '"]').text().trim());
                    });
                    $summary.removeClass('text-success').addClass('text-danger')
                        .text(received + ' of ' + total + ' documents received. Pending: ' + missing.join(', '));
                }
            }

            $(document).on('change', 'input[name="nature_of_deal"]', toggleLcSection);
            $(document).on('change', '.lc-document-check', refreshLcSummary);

            toggleLcSection();
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
