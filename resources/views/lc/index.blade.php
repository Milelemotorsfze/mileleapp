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

    .lc-doc-missing {
        color: #fa5c7c;
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
            Documentation matrix for every LC quotation. A transaction stays <strong>Blocked</strong> until the LC terms are
            recorded, all five documents are received, the LC is unexpired and compliance is marked compliant.
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
                        <th>Quotation</th>
                        <th>Client</th>
                        <th>Deal Value</th>
                        <th>LC Number</th>
                        <th>Issuing Bank</th>
                        <th>Expiry Date</th>
                        @foreach ($documents as $label)
                            <th>{{ $label }}</th>
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
                            <td>
                                #{{ $quotation->id }}<br>
                                <small class="text-muted">{{ $quotation->date ? \Carbon\Carbon::parse($quotation->date)->format('d-M-Y') : '-' }}</small>
                            </td>
                            <td>
                                {{ $quotation->call->company_name ?? $quotation->call->name ?? '-' }}<br>
                                <small class="text-muted">{{ $quotation->createdBy->name ?? '' }}</small>
                            </td>
                            <td>{{ $quotation->currency }} {{ number_format((float) $quotation->deal_value, 2) }}</td>
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
                                        <i class="fa fa-check lc-doc-ok" title="Received"></i>
                                    @else
                                        <i class="fa fa-times lc-doc-missing" title="Not received"></i>
                                    @endif
                                </td>
                            @endforeach
                            <td>
                                @php
                                    $complianceClass = match ($lc->compliance_status) {
                                        'compliant' => 'bg-success',
                                        'discrepant' => 'bg-danger',
                                        'under_review' => 'bg-info',
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
                            <td colspan="{{ 10 + count($documents) }}" class="text-center text-muted">
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
