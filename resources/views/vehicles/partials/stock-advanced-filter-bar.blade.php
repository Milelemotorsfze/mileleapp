@php
$barUid = $stockFilterBarTableId ?? 'dtBasicExample7';
$salesPeople = isset($stockFilterSalespeople) ? collect($stockFilterSalespeople) : collect();
$brands = isset($stockFilterBrands) ? $stockFilterBrands : collect();
$modelLines = isset($stockFilterModelLines) ? $stockFilterModelLines : collect();
$variants = isset($stockFilterVariants) ? $stockFilterVariants : collect();
$warehouses = isset($stockFilterWarehouses) ? $stockFilterWarehouses : collect();
$stockVariantMeta = $variants->map(function ($v) {
return [
'id' => (string) $v->id,
'name' => (string) $v->name,
'model_line_id' => (int) $v->master_model_lines_id,
];
})->values();
@endphp
<style>
    #stock-adv-bar-wrap-{{ $barUid }}.stock-advanced-filter-bar {
        position: relative;
        z-index: 25;
        background: #f8f9fc;
        border: 1px solid #e2e6ef !important;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.06);
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-form-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-section {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: 0.5rem;
        padding: 0.85rem 1rem;
        height: 100%;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-section-title {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 0.65rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid #f1f5f9;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-field label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.2rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-field .form-control,
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-field .form-select {
        border-color: #d8dee9;
        background: #fff;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-field .form-control:focus,
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-field .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.12);
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-range-box {
        background: #f8fafc;
        border: 1px solid #e8ecf4;
        border-radius: 0.375rem;
        padding: 0.5rem 0.65rem;
        height: 100%;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-vin-textarea {
        min-height: 10rem;
        resize: vertical;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 0.8rem;
        line-height: 1.45;
    }
    @media (min-width: 992px) {
        #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-vin-textarea {
            min-height: 100%;
        }
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-select-wrap {
        position: relative;
        width: 100%;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-select-wrap .select2-container {
        width: 100% !important;
        display: block;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-select-wrap .select2-dropdown {
        width: 100% !important;
        max-width: 100%;
        box-sizing: border-box;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .select2-container--default .select2-selection--multiple {
        min-height: 31px;
        border-color: #d8dee9;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-form-footer {
        background: #fff;
        border: 1px solid #e8ecf4;
        border-radius: 0.5rem;
        padding: 0.85rem 1rem;
        margin-top: 0.25rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-summary {
        min-height: 1.5rem;
        line-height: 1.45;
        font-size: inherit;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-actions .btn-primary {
        min-width: 10rem;
        font-weight: 500;
    }
    /* Results / filter breakdown only — larger, readable text */
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail {
        font-size: 1.05rem;
        line-height: 1.6;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail-heading {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail-list {
        font-size: 1.05rem;
        line-height: 1.6;
        margin-bottom: 0.75rem;
        padding-left: 1.25rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail-list li {
        margin-bottom: 0.5rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail-text {
        font-size: 1.05rem;
        line-height: 1.6;
        margin-bottom: 0.65rem;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-detail .badge {
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.35em 0.55em;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-diag-block {
        font-size: 1.05rem;
        line-height: 1.6;
    }
    #stock-adv-bar-wrap-{{ $barUid }} .stock-adv-diag-block p {
        margin-bottom: 0.5rem;
    }
</style>
<div class="stock-advanced-filter-bar rounded p-3 mb-3 position-relative" id="stock-adv-bar-wrap-{{ $barUid }}" data-dt-table-id="{{ $barUid }}">
    <div class="mb-3">
        <h6 class="stock-adv-form-title">Advanced search</h6>
        <p class="small text-muted mb-0">Set your criteria below, then apply. Active filters are combined (all must match).</p>
    </div>

    <div class="row g-3 align-items-stretch">
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-3 h-100">
                <section class="stock-adv-section">
                    <div class="stock-adv-section-title">Documents &amp; location</div>
                    <div class="row g-2">
                        <div class="col-6 col-md-3 stock-adv-field">
                            <label class="form-label mb-0">PO number(s)</label>
                            <input type="text" class="form-control form-control-sm stock-adv-inp" data-field="po_numbers" placeholder="Comma or line">
                        </div>
                        <div class="col-6 col-md-3 stock-adv-field">
                            <label class="form-label mb-0">SO number(s)</label>
                            <input type="text" class="form-control form-control-sm stock-adv-inp" data-field="so_numbers" placeholder="SO numbers">
                        </div>
                        <div class="col-6 col-md-3 stock-adv-field">
                            <label class="form-label mb-0">GRN number(s)</label>
                            <input type="text" class="form-control form-control-sm stock-adv-inp" data-field="grn_numbers" placeholder="GRN numbers">
                        </div>
                        <div class="col-6 col-md-3 stock-adv-field">
                            <label class="form-label mb-0">Location</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="location_ids" multiple>
                                    @foreach ($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="stock-adv-section">
                    <div class="stock-adv-section-title">Vehicle</div>
                    <div class="row g-2">
                        <div class="col-12 col-md-4 stock-adv-field">
                            <label class="form-label mb-0">Brand</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="brand_ids" multiple>
                                    @foreach ($brands as $b)
                                    <option value="{{ $b->id }}">{{ $b->brand_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 stock-adv-field">
                            <label class="form-label mb-0">Model line</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="model_line_ids" multiple>
                                    @foreach ($modelLines as $ml)
                                    <option value="{{ $ml->id }}" data-brand-id="{{ $ml->brand_id ?? '' }}">{{ $ml->model_line }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 stock-adv-field">
                            <label class="form-label mb-0">Variant</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="variant_ids" multiple disabled>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="stock-adv-section">
                    <div class="stock-adv-section-title">Sales &amp; pricing</div>
                    <div class="row g-2 align-items-end">
                        <div class="col-12 col-md-4 stock-adv-field">
                            <label class="form-label mb-0">Sales person</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="sales_person_ids" multiple>
                                    @foreach ($salesPeople as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 stock-adv-field">
                            <label class="form-label mb-0">Status</label>
                            <div class="stock-adv-select-wrap">
                                <select class="form-select form-select-sm stock-adv-select" data-field="stock_statuses" multiple>
                                    <option value="Incoming">Incoming</option>
                                    <option value="Pending Inspection">Pending Inspection</option>
                                    <option value="Available Stock">Available Stock</option>
                                    <option value="Booked">Booked</option>
                                    <option value="Sold">Sold</option>
                                    <option value="Delivered">Delivered</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 stock-adv-field">
                            <div class="stock-adv-range-box d-flex gap-2">
                                <div class="flex-fill">
                                    <label class="form-label mb-0">Price min</label>
                                    <input type="number" class="form-control form-control-sm stock-adv-inp" data-field="price_min" placeholder="Min" step="any" min="0" inputmode="decimal">
                                </div>
                                <div class="flex-fill">
                                    <label class="form-label mb-0">Price max</label>
                                    <input type="number" class="form-control form-control-sm stock-adv-inp" data-field="price_max" placeholder="Max" step="any" min="0" inputmode="decimal">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="stock-adv-section">
                    <div class="stock-adv-section-title">Date ranges</div>
                    <div class="row g-2">
                        <div class="col-12 col-md-4">
                            <div class="stock-adv-range-box d-flex gap-2">
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">PO from</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="po_date_from">
                                </div>
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">PO to</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="po_date_to">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="stock-adv-range-box d-flex gap-2">
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">GRN from</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="grn_date_from">
                                </div>
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">GRN to</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="grn_date_to">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="stock-adv-range-box d-flex gap-2">
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">SO from</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="so_date_from">
                                </div>
                                <div class="flex-fill stock-adv-field">
                                    <label class="form-label mb-0">SO to</label>
                                    <input type="date" class="form-control form-control-sm stock-adv-inp" data-field="so_date_to">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <section class="stock-adv-section h-100 d-flex flex-column">
                <div class="stock-adv-section-title">VIN lookup</div>
                <div class="stock-adv-field flex-grow-1 d-flex flex-column">
                    <label class="form-label mb-1">VIN(s)</label>
                    <textarea class="form-control form-control-sm stock-adv-inp stock-adv-vin-textarea flex-grow-1" data-field="vins" rows="6" placeholder="Paste exact VINs — one per line or comma-separated"></textarea>
                    <span class="form-text text-muted mt-1">Tip: paste multiple VINs from a spreadsheet.</span>
                </div>
            </section>
        </div>
    </div>

    <footer class="stock-adv-form-footer d-flex flex-wrap align-items-center justify-content-between gap-3 mt-3">
        <div class="stock-adv-summary fw-semibold text-dark flex-grow-1" id="stock-adv-summary-{{ $barUid }}"></div>
        <div class="stock-adv-actions d-flex flex-wrap gap-2 ms-auto">
            <button type="button" class="btn btn-outline-secondary stock-adv-clear" data-table-id="{{ $barUid }}">Clear</button>
            <button type="button" class="btn btn-primary stock-adv-apply" data-table-id="{{ $barUid }}">Save &amp; Apply Filters</button>
        </div>
    </footer>

    <div class="mt-2 pt-2 border-top stock-adv-detail text-dark" id="stock-adv-detail-{{ $barUid }}"></div>
</div>

<script>
    (function() {
        var tableId = @json($barUid);
        window.stockBarCommitted = window.stockBarCommitted || {};
        window.stockBarPendingApplyAlert = window.stockBarPendingApplyAlert || {};
        window.stockBarModelLinesByTable = window.stockBarModelLinesByTable || {};
        window.stockBarVariantsByTable = window.stockBarVariantsByTable || {};
        window.stockBarVariantsByTable[tableId] = @json($stockVariantMeta);

        function splitTokens(str) {
            if (!str || !String(str).trim()) return [];
            return String(str).split(/[\s,;]+/).map(function(s) {
                return s.trim();
            }).filter(Boolean);
        }

        function splitLines(str) {
            if (!str || !String(str).trim()) return [];
            return String(str).split(/[\r\n,;]+/).map(function(s) {
                return s.trim();
            }).filter(Boolean);
        }

        /** Empty string if not a finite number >= 0 (matches server normalizeStockBarPriceScalar). */
        function sanitizeNonNegativePriceScalar(raw) {
            var s = raw == null ? '' : String(raw).trim();
            if (s === '') {
                return '';
            }
            var n = parseFloat(s);
            if (!isFinite(n) || n < 0) {
                return '';
            }
            return String(n);
        }

        function clampStockBarPriceInputs($w) {
            ['price_min', 'price_max'].forEach(function(field) {
                var $inp = $w.find('[data-field="' + field + '"]');
                var clean = sanitizeNonNegativePriceScalar($inp.val());
                $inp.val(clean);
            });
        }

        function collectBarFromDom() {
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            clampStockBarPriceInputs($w);
            var payload = {
                po_numbers: splitTokens($w.find('[data-field="po_numbers"]').val()),
                so_numbers: splitTokens($w.find('[data-field="so_numbers"]').val()),
                grn_numbers: splitTokens($w.find('[data-field="grn_numbers"]').val()),
                vins: splitLines($w.find('[data-field="vins"]').val()),
                brand_ids: $w.find('[data-field="brand_ids"]').val() || [],
                model_line_ids: $w.find('[data-field="model_line_ids"]').val() || [],
                variant_ids: $w.find('[data-field="variant_ids"]').val() || [],
                location_ids: $w.find('[data-field="location_ids"]').val() || [],
                sales_person_ids: $w.find('[data-field="sales_person_ids"]').val() || [],
                stock_statuses: $w.find('[data-field="stock_statuses"]').val() || [],
                po_date_from: $w.find('[data-field="po_date_from"]').val() || '',
                po_date_to: $w.find('[data-field="po_date_to"]').val() || '',
                grn_date_from: $w.find('[data-field="grn_date_from"]').val() || '',
                grn_date_to: $w.find('[data-field="grn_date_to"]').val() || '',
                so_date_from: $w.find('[data-field="so_date_from"]').val() || '',
                so_date_to: $w.find('[data-field="so_date_to"]').val() || '',
                price_min: sanitizeNonNegativePriceScalar($w.find('[data-field="price_min"]').val()),
                price_max: sanitizeNonNegativePriceScalar($w.find('[data-field="price_max"]').val())
            };
            return payload;
        }

        function barHasAnyFilter(p) {
            if (!p) return false;
            return (p.po_numbers && p.po_numbers.length) ||
                (p.so_numbers && p.so_numbers.length) ||
                (p.grn_numbers && p.grn_numbers.length) ||
                (p.vins && p.vins.length) ||
                (p.brand_ids && p.brand_ids.length) ||
                (p.model_line_ids && p.model_line_ids.length) ||
                (p.variant_ids && p.variant_ids.length) ||
                (p.location_ids && p.location_ids.length) ||
                (p.sales_person_ids && p.sales_person_ids.length) ||
                (p.stock_statuses && p.stock_statuses.length) ||
                (p.po_date_from || p.po_date_to || p.grn_date_from || p.grn_date_to || p.so_date_from || p.so_date_to) ||
                (p.price_min && String(p.price_min).trim() !== '') ||
                (p.price_max && String(p.price_max).trim() !== '');
        }
        window.stockBarPayloadHasFilters = barHasAnyFilter;

        function captureModelLineMetaOnce() {
            if (Object.prototype.hasOwnProperty.call(window.stockBarModelLinesByTable, tableId)) {
                return;
            }
            var meta = [];
            $('#stock-adv-bar-wrap-' + tableId).find('[data-field="model_line_ids"] option').each(function() {
                var $o = $(this);
                var id = $o.val();
                if (id === undefined || id === '') {
                    return;
                }
                var bAttr = $o.attr('data-brand-id');
                meta.push({
                    id: String(id),
                    name: $.trim($o.text()),
                    brand_id: bAttr !== undefined && bAttr !== '' && !isNaN(parseInt(bAttr, 10)) ? parseInt(bAttr, 10) : null
                });
            });
            window.stockBarModelLinesByTable[tableId] = meta;
        }

        function rebuildModelLineOptions() {
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            captureModelLineMetaOnce();
            var meta = window.stockBarModelLinesByTable[tableId] || [];
            var brandSel = parseIdList($w.find('[data-field="brand_ids"]'));
            var filtered;
            if (brandSel.length === 0) {
                filtered = meta.slice();
            } else {
                filtered = meta.filter(function(ml) {
                    if (ml.brand_id == null) {
                        return false;
                    }
                    return brandSel.indexOf(ml.brand_id) !== -1;
                });
            }

            var $ml = $w.find('[data-field="model_line_ids"]');
            var prev = $ml.val() || [];
            if ($ml.data('select2')) {
                $ml.select2('destroy');
            }
            $ml.empty();
            if (filtered.length === 0) {
                $ml.append($('<option>', {
                    value: '',
                    text: brandSel.length ? 'No model lines for selected brand(s)' : 'No model lines loaded',
                    disabled: true
                }));
            } else {
                filtered.forEach(function(ml) {
                    $ml.append(
                        $('<option>', {
                            value: ml.id,
                            text: ml.name
                        })
                        .attr('data-brand-id', ml.brand_id != null ? ml.brand_id : '')
                    );
                });
            }
            var validMl = prev.filter(function(id) {
                return $ml.find('option').filter(function() {
                    return String($(this).val()) === String(id);
                }).length > 0;
            });
            $ml.val(validMl.length ? validMl : null);
            if (typeof $.fn.select2 === 'function') {
                $ml.select2({
                    width: '100%',
                    placeholder: 'Model line',
                    allowClear: true,
                    closeOnSelect: false,
                    minimumResultsForSearch: 0,
                    dropdownParent: $ml.closest('.stock-adv-select-wrap'),
                    dropdownAutoWidth: false
                });
            }
            rebuildVariantOptions();
        }

        function rebuildVariantOptions() {
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            var all = window.stockBarVariantsByTable[tableId] || [];
            var mlSel = parseIdList($w.find('[data-field="model_line_ids"]'));
            var $var = $w.find('[data-field="variant_ids"]');
            var prev = $var.val() || [];

            if ($var.data('select2')) {
                $var.select2('destroy');
            }
            $var.empty();

            if (mlSel.length === 0) {
                $var.prop('disabled', true);
                $var.append($('<option>', {
                    value: '',
                    text: 'Select model line(s) first',
                    disabled: true
                }));
                $var.val(null);
            } else {
                $var.prop('disabled', false);
                var filtered = all.filter(function(v) {
                    return mlSel.indexOf(v.model_line_id) !== -1;
                });
                if (filtered.length === 0) {
                    $var.append($('<option>', {
                        value: '',
                        text: 'No variants for selected model line(s)',
                        disabled: true
                    }));
                } else {
                    filtered.forEach(function(v) {
                        $var.append($('<option>', {
                            value: v.id,
                            text: v.name
                        }));
                    });
                }
                var validVar = prev.filter(function(id) {
                    return $var.find('option').filter(function() {
                        return String($(this).val()) === String(id);
                    }).length > 0;
                });
                $var.val(validVar.length ? validVar : null);
            }

            if (typeof $.fn.select2 === 'function') {
                $var.select2({
                    width: '100%',
                    placeholder: mlSel.length ? 'Variant' : 'Select model line first',
                    allowClear: true,
                    closeOnSelect: false,
                    minimumResultsForSearch: 0,
                    dropdownParent: $var.closest('.stock-adv-select-wrap'),
                    dropdownAutoWidth: false
                });
            }
        }

        function parseIdList($select) {
            return ($select.val() || []).map(function(x) {
                return parseInt(x, 10);
            }).filter(function(n) {
                return !isNaN(n) && n > 0;
            });
        }

        function initSelect2BrandSalesOnly() {
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            if (typeof $.fn.select2 !== 'function') {
                return;
            }
            $w.find('[data-field="brand_ids"], [data-field="location_ids"], [data-field="sales_person_ids"], [data-field="stock_statuses"]').each(function() {
                if ($(this).data('select2')) {
                    return;
                }
                $(this).select2({
                    width: '100%',
                    placeholder: $(this).closest('.stock-adv-select-wrap').prev('label').text(),
                    allowClear: true,
                    closeOnSelect: false,
                    minimumResultsForSearch: 0,
                    dropdownParent: $(this).closest('.stock-adv-select-wrap'),
                    dropdownAutoWidth: false
                });
            });
        }

        $(function() {
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            captureModelLineMetaOnce();
            initSelect2BrandSalesOnly();
            rebuildModelLineOptions();

            $w.on('select2:open', '.stock-adv-select', function() {
                var $sel = $(this);
                var $wrap = $sel.closest('.stock-adv-select-wrap');
                $w.find('.stock-adv-select-wrap').css('z-index', 1);
                $wrap.css('z-index', 50);
                var cw = $wrap.outerWidth();
                if (!cw) {
                    return;
                }
                setTimeout(function() {
                    $wrap.find('.select2-dropdown').filter(':visible').last().css({
                        minWidth: cw + 'px',
                        width: cw + 'px',
                        boxSizing: 'border-box'
                    });
                }, 0);
            });
            $w.on('select2:close', '.stock-adv-select', function() {
                $(this).closest('.stock-adv-select-wrap').css('z-index', 1);
            });

            $w.on('change', '[data-field="brand_ids"]', function() {
                rebuildModelLineOptions();
            });

            $w.on('change', '[data-field="model_line_ids"]', function() {
                rebuildVariantOptions();
            });

            $w.on('blur change', '[data-field="price_min"], [data-field="price_max"]', function() {
                clampStockBarPriceInputs($w);
            });

            $w.on('click', '.stock-adv-apply', function() {
                var payload = collectBarFromDom();
                window.stockBarCommitted[tableId] = payload;
                window.stockBarPendingApplyAlert[tableId] = true;
                var dt = $('#' + tableId).DataTable();
                dt.ajax.reload();
            });

            $w.on('click', '.stock-adv-clear', function() {
                window.stockBarCommitted[tableId] = {};
                window.stockBarPendingApplyAlert[tableId] = true;
                $w.find('.stock-adv-inp').val('');
                $w.find('[data-field="brand_ids"], [data-field="sales_person_ids"], [data-field="stock_statuses"], [data-field="location_ids"]').each(function() {
                    $(this).val(null).trigger('change');
                });
                var $mlClear = $w.find('[data-field="model_line_ids"]');
                if ($mlClear.data('select2')) {
                    $mlClear.select2('destroy');
                }
                $mlClear.val(null);
                rebuildModelLineOptions();
                var $varClear = $w.find('[data-field="variant_ids"]');
                if ($varClear.data('select2')) {
                    $varClear.select2('destroy');
                }
                $varClear.val(null);
                rebuildVariantOptions();
                var dt = $('#' + tableId).DataTable();
                dt.ajax.reload();
            });
        });
    })();
</script>
<script>
    (function() {
        function stockBarEscapeHtml(s) {
            if (s === null || s === undefined) {
                return '';
            }
            return String(s)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function stockBarFormatMultiSelectDiagnosticsHtml(diag) {
            if (!diag || typeof diag !== 'object') {
                return '';
            }
            var titles = {
                model_lines: 'Model line',
                variants: 'Variant',
                brands: 'Brand',
                locations: 'Location',
                sales_persons: 'Sales person',
                statuses: 'Status'
            };
            var chunks = [];
            Object.keys(titles).forEach(function(k) {
                var list = diag[k];
                if (!list || !list.length) {
                    return;
                }
                var bad = [];
                var good = [];
                list.forEach(function(it) {
                    var label = it.name != null && String(it.name) !== '' ? String(it.name) : String(it.id);
                    if (it.matches) {
                        good.push(stockBarEscapeHtml(label) + (it.count != null ? ' (' + it.count + ')' : ''));
                    } else {
                        bad.push(stockBarEscapeHtml(label));
                    }
                });
                var bits = [];
                if (bad.length) {
                    bits.push('<span class="text-danger"><strong>No rows alone:</strong> ' + bad.join(', ') + '</span>');
                }
                if (good.length) {
                    bits.push('<span class="text-success"><strong>Has rows alone:</strong> ' + good.join(', ') + '</span>');
                }
                if (bits.length) {
                    chunks.push('<p class="stock-adv-detail-text mb-1"><strong>' + titles[k] + ' —</strong> ' + bits.join(' ') + '</p>');
                }
            });
            if (!chunks.length) {
                return '';
            }
            return (
                '<div class="mt-2 pt-2 border-top small">' +
                '<div class="stock-adv-detail-heading mb-2">Each value in a multi-select (with your other bar filters)</div>' +
                chunks.join('') +
                '</div>'
            );
        }

        function optionTextByValue($w, field, id) {
            var $opt = $w.find('[data-field="' + field + '"] option').filter(function() {
                return String($(this).val()) === String(id);
            });
            return $opt.length ? $.trim($opt.first().text()) : '';
        }

        function stockBarFilterBreakdownRows($w, p, filt) {
            var rows = [];
            if (p.po_numbers && p.po_numbers.length) {
                rows.push({
                    label: 'PO number(s)',
                    value: p.po_numbers.join(', ')
                });
            }
            if (p.so_numbers && p.so_numbers.length) {
                rows.push({
                    label: 'SO number(s)',
                    value: p.so_numbers.join(', ')
                });
            }
            if (p.grn_numbers && p.grn_numbers.length) {
                rows.push({
                    label: 'GRN number(s)',
                    value: p.grn_numbers.join(', ')
                });
            }
            if (p.vins && p.vins.length) {
                rows.push({
                    label: 'VIN(s)',
                    value: p.vins.join(', ')
                });
            }
            if (p.brand_ids && p.brand_ids.length) {
                var bn = (p.brand_ids || []).map(function(id) {
                    return optionTextByValue($w, 'brand_ids', id) || ('#' + id);
                });
                rows.push({
                    label: 'Brand(s)',
                    value: bn.join(', ')
                });
            }
            if (p.model_line_ids && p.model_line_ids.length) {
                var mn = (p.model_line_ids || []).map(function(id) {
                    return optionTextByValue($w, 'model_line_ids', id) || ('#' + id);
                });
                rows.push({
                    label: 'Model line(s)',
                    value: mn.join(', ')
                });
            }
            if (p.variant_ids && p.variant_ids.length) {
                var vn = (p.variant_ids || []).map(function(id) {
                    return optionTextByValue($w, 'variant_ids', id) || ('#' + id);
                });
                rows.push({
                    label: 'Variant(s)',
                    value: vn.join(', ')
                });
            }
            if (p.location_ids && p.location_ids.length) {
                var loc = (p.location_ids || []).map(function(id) {
                    return optionTextByValue($w, 'location_ids', id) || ('#' + id);
                });
                rows.push({
                    label: 'Location(s)',
                    value: loc.join(', ')
                });
            }
            if (p.sales_person_ids && p.sales_person_ids.length) {
                var sn = (p.sales_person_ids || []).map(function(id) {
                    return optionTextByValue($w, 'sales_person_ids', id) || ('#' + id);
                });
                rows.push({
                    label: 'Sales person(s)',
                    value: sn.join(', ')
                });
            }
            if (p.stock_statuses && p.stock_statuses.length) {
                rows.push({
                    label: 'Status',
                    value: p.stock_statuses.join(', ')
                });
            }
            if (p.po_date_from || p.po_date_to) {
                rows.push({
                    label: 'PO date range',
                    value: (p.po_date_from || '…') + ' → ' + (p.po_date_to || '…')
                });
            }
            if (p.grn_date_from || p.grn_date_to) {
                rows.push({
                    label: 'GRN date range',
                    value: (p.grn_date_from || '…') + ' → ' + (p.grn_date_to || '…')
                });
            }
            if (p.so_date_from || p.so_date_to) {
                rows.push({
                    label: 'SO date range',
                    value: (p.so_date_from || '…') + ' → ' + (p.so_date_to || '…')
                });
            }
            if (p.price_min && String(p.price_min).trim() !== '' || p.price_max && String(p.price_max).trim() !== '') {
                rows.push({
                    label: 'Price range',
                    value: (p.price_min && String(p.price_min).trim() !== '' ? String(p.price_min).trim() : '…') +
                        ' → ' +
                        (p.price_max && String(p.price_max).trim() !== '' ? String(p.price_max).trim() : '…')
                });
            }
            return rows;
        }

        /**
         * @param {string} tableId DataTable id (same as bar wrap id suffix)
         * @param {object} json DataTables JSON
         */
        window.stockBarUpdateResultPanel = function(tableId, json, opts) {
            opts = opts || {};
            if (!json || json.recordsFiltered === undefined) {
                return;
            }
            var filt = json.recordsFiltered;
            var total = json.recordsTotal != null ? json.recordsTotal : filt;
            var p = (window.stockBarCommitted && window.stockBarCommitted[tableId]) || {};
            var hasF = window.stockBarPayloadHasFilters && window.stockBarPayloadHasFilters(p);
            var $w = $('#stock-adv-bar-wrap-' + tableId);
            var $sum = $('#stock-adv-summary-' + tableId);
            var $detail = $('#stock-adv-detail-' + tableId);
            if (!$sum.length) {
                return;
            }

            if (hasF) {
                $sum.removeClass('text-dark text-muted').addClass(filt === 0 ? 'text-danger' : 'text-success');
                $sum.html('<strong>This search found ' + filt + ' record(s) matching your filters.</strong>');
            } else {
                $sum.removeClass('text-success text-danger text-muted').addClass('text-dark');
                $sum.html('<strong>This view shows ' + filt + ' record(s) (from ' + total + ' in this report).</strong>');
            }

            var parts = [];
            if ($detail.length) {
                $detail.empty();
            }
            if (hasF) {
                var br = stockBarFilterBreakdownRows($w, p, filt);
                var comboOk = filt > 0;
                parts.push('<div class="stock-adv-detail-heading">Bar filters (AND — all must match)</div>');
                if (!br.length) {
                    parts.push('<p class="stock-adv-detail-text text-muted mb-2 mb-0">No bar filter values were sent.</p>');
                } else {
                    parts.push('<ul class="stock-adv-detail-list">');
                    br.forEach(function(row) {
                        var badge = comboOk ?
                            '<span class="badge bg-success ms-1">applied</span>' :
                            '<span class="badge bg-danger ms-1">no combined match</span>';
                        parts.push(
                            '<li class="mb-1">' +
                            '<strong>' + stockBarEscapeHtml(row.label) + ':</strong> ' +
                            stockBarEscapeHtml(row.value) +
                            badge +
                            '</li>'
                        );
                    });
                    parts.push('</ul>');
                    if (!comboOk) {
                        parts.push(
                            '<p class="stock-adv-detail-text text-danger mb-2 mb-0"><strong>Failed:</strong> no vehicle matches <em>all</em> of the criteria above together. <strong>Worked:</strong> none for this combination — relax or remove one filter and try again.</p>'
                        );
                    } else {
                        var diagEarly = json.stock_bar_diagnostics;
                        var hasBad = false;
                        if (diagEarly && typeof diagEarly === 'object') {
                            Object.keys(diagEarly).forEach(function(k) {
                                (diagEarly[k] || []).forEach(function(it) {
                                    if (!it.matches) {
                                        hasBad = true;
                                    }
                                });
                            });
                        }
                        if (hasBad) {
                            parts.push(
                                '<p class="stock-adv-detail-text text-success mb-1 mb-0"><strong>Worked:</strong> together these filters return rows. <strong>Note:</strong> some choices below had no matching vehicles when applied alone with your other filters.</p>'
                            );
                        } else {
                            parts.push(
                                '<p class="stock-adv-detail-text text-success mb-2 mb-0"><strong>Worked:</strong> this result set matches every criterion listed. <strong>Failed:</strong> none for this combination.</p>'
                            );
                        }
                    }
                }
            }

            var diagBlock = stockBarFormatMultiSelectDiagnosticsHtml(json.stock_bar_diagnostics);
            if (diagBlock) {
                parts.push(diagBlock);
            }

            if ($detail.length) {
                $detail.html(parts.join(''));
            }

            if (window.stockBarPendingApplyAlert && window.stockBarPendingApplyAlert[tableId]) {
                window.stockBarPendingApplyAlert[tableId] = false;
                if (hasF) {
                    if (filt === 0 && typeof alertify !== 'undefined') {
                        alertify.error('No result found against this specific search.');
                    } else if (filt > 0 && typeof alertify !== 'undefined') {
                        alertify.success('This search found ' + filt + ' record(s) matching.');
                    }
                }
            }
        };

        /**
         * Bind summary updates to DataTable XHR and apply current response (initComplete runs after first load).
         */
        window.stockBarBindTableSummary = function(bindTableId) {
            var $t = $('#' + bindTableId);
            if (!$t.length) {
                return;
            }
            $t.off('xhr.dt.stockBarSummary').on('xhr.dt.stockBarSummary', function(e, settings, json) {
                if (window.stockBarUpdateResultPanel) {
                    window.stockBarUpdateResultPanel(bindTableId, json);
                }
            });
            if ($.fn.DataTable && $.fn.DataTable.isDataTable($t)) {
                var json = $t.DataTable().ajax.json();
                if (json && window.stockBarUpdateResultPanel) {
                    window.stockBarUpdateResultPanel(bindTableId, json);
                }
            }
        };
    })();
</script>