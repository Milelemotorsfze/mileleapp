@extends('layouts.table')
<style>
    .import-summary .card { border-left: 4px solid #ced4da; }
    .import-summary .card.updates { border-left-color: #198754; }
    .import-summary .card.unchanged { border-left-color: #6c757d; }
    .import-summary .card.errors { border-left-color: #dc3545; }
    .import-summary .count { font-size: 26px; font-weight: 600; line-height: 1.1; }
    .import-preview-table { font-size: 12px; width: 100% !important; }
    .import-preview-table td, .import-preview-table th { padding: 4px 8px !important; vertical-align: middle; }
    .import-preview-table .price-from { color: #6c757d; text-decoration: line-through; }
    .import-preview-table .price-to { color: #198754; font-weight: 600; }
</style>
@section('content')
@php
    $updateCount = count($result['updates']);
    $unchangedCount = count($result['unchanged']);
    $errorCount = count($result['errors']);
    $vehicleCount = array_sum(array_column($result['updates'], 'vehicle_count'));
    $keyHeaders = \App\Services\VariantPriceCsvImportService::KEY_COLUMNS;
    $valueHeaders = \App\Services\VariantPriceCsvImportService::UPDATABLE_COLUMNS;
@endphp
<div class="card-header">
    <a class="btn btn-sm btn-info float-end" href="{{ route('variantprices.allvariantprice') }}"><i class="fa fa-arrow-left"></i> Back</a>
    <h4 class="card-title">Variant Price CSV Upload: Preview</h4>
    <p class="text-muted mb-0">Nothing has been saved yet. Check the rows below, then confirm.</p>
</div>
<div class="card-body">
    <div class="row import-summary mb-3">
        <div class="col-6 col-md-3 mb-2">
            <div class="card mb-0"><div class="card-body py-2">
                <div class="text-muted small">Rows in file</div>
                <div class="count">{{ number_format($result['total_rows']) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card updates mb-0"><div class="card-body py-2">
                <div class="text-muted small">Will update</div>
                <div class="count text-success">{{ number_format($updateCount) }}</div>
                <div class="small text-muted">{{ number_format($vehicleCount) }} vehicles</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card unchanged mb-0"><div class="card-body py-2">
                <div class="text-muted small">No change</div>
                <div class="count">{{ number_format($unchangedCount) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3 mb-2">
            <div class="card errors mb-0"><div class="card-body py-2">
                <div class="text-muted small">Errors (will be skipped)</div>
                <div class="count {{ $errorCount ? 'text-danger' : '' }}">{{ number_format($errorCount) }}</div>
            </div></div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-4">
        <form method="POST" action="{{ route('variantprices.import.confirm', $token) }}" id="confirmImportForm">
            @csrf
            <button type="submit" class="btn btn-success" id="confirmImportButton" @disabled($updateCount === 0)>
                Confirm and update {{ number_format($updateCount) }} row(s)
            </button>
        </form>
        <form method="POST" action="{{ route('variantprices.import.cancel', $token) }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Cancel</button>
        </form>
        @if($updateCount === 0)
            <span class="align-self-center text-muted">There is nothing to update. Fix the errors and upload the file again.</span>
        @endif
    </div>

    @if($errorCount)
    <h5 class="text-danger">Errors ({{ number_format($errorCount) }})</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped import-preview-table" id="importErrorsTable">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Row</th>
                    @foreach($keyHeaders as $header)<th>{{ $header }}</th>@endforeach
                    <th>Problem</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['errors'] as $error)
                <tr>
                    <td>{{ $error['line'] }}</td>
                    @foreach(array_keys($keyHeaders) as $field)<td>{{ $error['key'][$field] ?? '' }}</td>@endforeach
                    <td class="text-danger">{!! implode('<br>', array_map('e', $error['messages'])) !!}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($updateCount)
    <h5 class="text-success">Will update ({{ number_format($updateCount) }})</h5>
    <div class="table-responsive mb-4">
        <table class="table table-bordered table-striped import-preview-table" id="importUpdatesTable">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>Row</th>
                    @foreach($keyHeaders as $header)<th>{{ $header }}</th>@endforeach
                    @foreach($valueHeaders as $header)<th>{{ $header }}</th>@endforeach
                    <th>Vehicles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($result['updates'] as $update)
                <tr>
                    <td>{{ $update['line'] }}</td>
                    @foreach(array_keys($keyHeaders) as $field)<td>{{ $update['key'][$field] ?? '' }}</td>@endforeach
                    @foreach(array_keys($valueHeaders) as $field)
                    <td>
                        @isset($update['changes'][$field])
                            <span class="price-from">{{ $update['changes'][$field]['from'] }}</span>
                            &rarr; <span class="price-to">{{ $update['changes'][$field]['to'] }}</span>
                        @else
                            <span class="text-muted">no change</span>
                        @endisset
                    </td>
                    @endforeach
                    <td>{{ number_format($update['vehicle_count']) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($unchangedCount)
    <details class="mb-3">
        <summary class="h6">No change ({{ number_format($unchangedCount) }})</summary>
        <div class="table-responsive mt-2">
            <table class="table table-bordered table-striped import-preview-table" id="importUnchangedTable">
                <thead class="bg-soft-secondary">
                    <tr>
                        <th>Row</th>
                        @foreach($keyHeaders as $header)<th>{{ $header }}</th>@endforeach
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($result['unchanged'] as $row)
                    <tr>
                        <td>{{ $row['line'] }}</td>
                        @foreach(array_keys($keyHeaders) as $field)<td>{{ $row['key'][$field] ?? '' }}</td>@endforeach
                        <td class="text-muted">{{ $row['reason'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </details>
    @endif
</div>
<script>
    $(function () {
        $('#importErrorsTable, #importUpdatesTable, #importUnchangedTable').DataTable({
            pageLength: 25,
            order: [[0, 'asc']],
            autoWidth: false,
        });

        $('#confirmImportForm').on('submit', function (e) {
            var errors = {{ $errorCount }};
            var message = 'Update {{ number_format($updateCount) }} row(s) ({{ number_format($vehicleCount) }} vehicles)?';
            if (errors) {
                message += '\n\n' + errors + ' row(s) with errors will be skipped.';
            }
            if (!confirm(message)) {
                e.preventDefault();
                return;
            }
            $('#confirmImportButton').prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm" role="presentation"></span> Updating prices...');
        });
    });
</script>
@endsection
