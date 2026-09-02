@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
        padding-top: 0px;
    }

    .table>tbody>tr>td, .table>thead>tr>th {
        padding: 6px 8px;
        vertical-align: middle;
        font-size: 12px;
    }

    thead th {
        background-color: rgb(194, 196, 204) !important;
    }

    .lc-doc-ok {
        color: #0acf97;
    }

    /* The checklist is optional, so an unticked document is neutral, not an error. */
    .lc-doc-missing {
        color: #98a6ad;
    }

    .lc-other-docs {
        color: #6c757d;
        text-align: left;
        max-width: 160px;
        white-space: normal;
    }

    .lc-doc-advisory {
        color: #6c757d;
        text-align: left;
        white-space: normal;
    }

    /* Grouped PFI cells hold several real fields, so they read left-aligned. */
    .lc-cell {
        text-align: left !important;
        white-space: normal;
        min-width: 130px;
    }

    .lc-blockers {
        margin: 0;
        padding-left: 16px;
        text-align: left;
    }

    .lc-summary-card {
        cursor: pointer;
    }

    .lc-summary-card.active {
        outline: 2px solid #727cf5;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Letter of Credit Transactions</h4>
        <p class="text-muted mb-0">
            Documentation matrix for every LC quotation. A transaction stays <strong>Blocked</strong> until the LC terms
            are recorded, the LC is unexpired and compliance is marked compliant. The document checklist is optional and
            is reported for visibility only — it never blocks a shipment on its own.
        </p>
    </div>

    <div class="card-body">
        <div class="row mb-3">
            @php
                $cards = [
                    ['key' => 'all', 'label' => 'Total LC Deals', 'value' => $summary['total'], 'class' => 'text-dark'],
                    ['key' => 'blocked', 'label' => 'Shipment Blocked', 'value' => $summary['blocked'], 'class' => 'text-danger'],
                    ['key' => 'cleared', 'label' => 'Cleared to Ship', 'value' => $summary['cleared'], 'class' => 'text-success'],
                    ['key' => 'expiring_soon', 'label' => 'Expiring in 14 Days', 'value' => $summary['expiring_soon'], 'class' => 'text-warning'],
                    ['key' => 'expired', 'label' => 'Expired LCs', 'value' => $summary['expired'], 'class' => 'text-danger'],
                ];
            @endphp
            @foreach ($cards as $card)
                <div class="col-sm">
                    <a href="{{ route('lc-transactions.index', ['filter' => $card['key']]) }}" class="text-decoration-none">
                        <div class="card border lc-summary-card {{ $filter === $card['key'] ? 'active' : '' }}">
                            <div class="card-body py-2 text-center">
                                <h3 class="mb-0 {{ $card['class'] }}">{{ $card['value'] }}</h3>
                                <small class="text-muted">{{ $card['label'] }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <div class="table-responsive">
            <table id="lcTransactionsTable" class="table table-striped table-bordered">
                <thead class="bg-soft-secondary">
                    <tr>
                        <th>PFI</th>
                        <th>Client</th>
                        <th>Destination</th>
                        <th>Value &amp; Terms</th>
                        <th>Sales Person</th>
                        <th>LC Number</th>
                        <th>Issuing Bank</th>
                        <th>LC Expiry</th>
                        @foreach ($documents as $label)
                            <th title="{{ $label }}">{{ $documentHeadings[$loop->index] ?? $label }}</th>
                        @endforeach
                        <th>Compliance</th>
                        <th>Shipment</th>
                        <th>Outstanding</th>
                        <th>Open</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        @php
                            $quotation = $row['quotation'];
                            $lc = $row['lc'];
                            $days = $row['days_to_expiry'];
                        @endphp
                        <tr>
                            <td class="lc-cell">
                                <strong>PFI #{{ $row['pfi_number'] }}</strong><br>
                                <small class="text-muted">{{ $row['document_type'] ?? '-' }}</small><br>
                                <small>{{ $row['document_date'] ? $row['document_date']->format('d-M-Y') : '-' }}</small>
                                @if ($row['validity_days'] !== null)
                                    <br>
                                    <small class="text-muted">
                                        Valid {{ $row['validity_days'] }}
                                        {{ $row['validity_days'] == 1 ? 'day' : 'days' }}
                                        @if ($row['valid_until'])
                                            &rarr; {{ $row['valid_until']->format('d-M-Y') }}
                                        @endif
                                    </small>
                                @endif
                                @if ($row['sales_order'])
                                    <br><span class="badge bg-dark">SO {{ $row['sales_order']->so_number }}</span>
                                @endif
                            </td>
                            <td class="lc-cell">
                                {{ $row['client_company'] ?: ($row['client_name'] ?: '-') }}
                                @if ($row['client_company'] && $row['client_name'])
                                    <br><small class="text-muted">{{ $row['client_name'] }}</small>
                                @endif
                                @if ($row['client_contact_person'])
                                    <br><small class="text-muted">Attn: {{ $row['client_contact_person'] }}</small>
                                @endif
                                @if ($row['client_phone'])
                                    <br><small class="text-muted">{{ $row['client_phone'] }}</small>
                                @endif
                            </td>
                            <td class="lc-cell">
                                {{ $row['destination_country'] ?: '-' }}
                                @if ($row['destination_port'])
                                    <br><small class="text-muted">Port: {{ $row['destination_port'] }}</small>
                                @endif
                                @if ($row['port_of_loading'])
                                    <br><small class="text-muted">Loading: {{ $row['port_of_loading'] }}</small>
                                @endif
                                <br>
                                <small class="text-muted">
                                    {{ $row['category'] }}@if ($row['incoterm']) &middot; {{ $row['incoterm'] }}@endif
                                </small>
                            </td>
                            <td class="lc-cell">
                                <strong>{{ $quotation->currency }} {{ number_format((float) $quotation->deal_value, 2) }}</strong>
                                @if ($row['payment_terms'])
                                    <br><small class="text-muted">{{ $row['payment_terms'] }}</small>
                                @endif
                                @if ($row['bank'])
                                    <br><small class="text-muted">{{ $row['bank'] }}</small>
                                @endif
                                @if ($row['payment_due_date'])
                                    <br><small class="text-muted">Due {{ $row['payment_due_date']->format('d-M-Y') }}</small>
                                @endif
                            </td>
                            <td class="lc-cell">{{ $row['sales_person'] ?: '-' }}</td>
                            <td>
                                @if (filled($lc->lc_number))
                                    {{ $lc->lc_number }}
                                @else
                                    <span class="badge bg-danger">Not recorded</span>
                                @endif
                            </td>
                            <td>
                                @if (filled($lc->issuing_bank))
                                    {{ $lc->issuing_bank }}
                                @else
                                    <span class="badge bg-danger">Not recorded</span>
                                @endif
                            </td>
                            <td>
                                @if ($lc->lc_expiry_date)
                                    {{ $lc->lc_expiry_date->format('d-M-Y') }}<br>
                                    @if ($lc->isExpired())
                                        <span class="badge bg-danger">Expired</span>
                                    @elseif ($days !== null && $days <= 14)
                                        <span class="badge bg-warning">{{ $days }} day(s) left</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">Not recorded</span>
                                @endif
                            </td>
                            @foreach ($documents as $column => $label)
                                <td>
                                    @if ($lc->{$column})
                                        <i class="fa fa-check lc-doc-ok" title="Ticked"></i>
                                    @else
                                        <i class="fa fa-minus lc-doc-missing" title="Not ticked (optional)"></i>
                                    @endif
                                    @if ($column === 'doc_others' && $lc->doc_others && filled($lc->doc_others_details))
                                        <div class="lc-other-docs" title="{{ $lc->doc_others_details }}">
                                            <small>{{ \Illuminate\Support\Str::limit($lc->doc_others_details, 60) }}</small>
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                @php
                                    $complianceClass = match ($lc->compliance_status) {
                                        'compliant' => 'bg-success',
                                        'discrepant' => 'bg-danger',
                                        'under_review' => 'bg-info',
                                        'bank_processing' => 'bg-primary',
                                        'in_progress' => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $complianceClass }}">{{ $lc->complianceStatusLabel() }}</span>
                            </td>
                            <td>
                                @if ($row['can_ship'])
                                    <span class="badge bg-success">Cleared</span>
                                @else
                                    <span class="badge bg-danger">Blocked</span>
                                @endif
                            </td>
                            <td>
                                @if ($row['blockers'])
                                    <ul class="lc-blockers">
                                        @foreach ($row['blockers'] as $blocker)
                                            <li>{{ $blocker }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                @if ($row['documents_not_ticked'])
                                    <div class="lc-doc-advisory">
                                        <small>
                                            Docs {{ $row['documents_received'] }}/{{ count($documents) }} ticked
                                            &middot; optional: {{ implode(', ', $row['documents_not_ticked']) }}
                                        </small>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-info btn-sm"
                                   href="{{ route('qoutation.proforma_invoice_edit', ['callId' => $quotation->calls_id, 'quotationId' => $quotation->id]) }}"
                                   title="Open quotation">
                                    <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 12 + count($documents) }}" class="text-center text-muted">
                                No Letter of Credit transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#lcTransactionsTable').DataTable({
                order: [],
                pageLength: 25,
                columnDefs: [{ orderable: false, targets: -1 }]
            });
        });
    </script>
@endsection
