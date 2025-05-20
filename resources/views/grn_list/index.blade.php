@extends('layouts.table')
@section('content')

@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['view-grn-list']);
@endphp

@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">GRN List</h4>
    <br/>
    <div class="row g-3">
        <div class="col-md-3">
            <label for="filterDate" class="form-label"><strong>Filter by Date</strong></label>
            <input type="date" id="filterDate" class="form-control" oninput="filterTable()">
        </div>
        <div class="col-md-3">
            <label for="filterGRN" class="form-label"><strong>Filter by GRN Number</strong></label>
            <input type="text" id="filterGRN" class="form-control" placeholder="Enter GRN Number" oninput="filterTable()">
        </div>
        <div class="col-md-3">
            <label for="filterVIN" class="form-label"><strong>Filter by VIN</strong></label>
            <input type="text" id="filterVIN" class="form-control" placeholder="Enter VIN Number" oninput="filterTable()">
        </div>
    </div>
</div>

<div class="tab-content" id="grn-list">
    <div class="tab-pane fade show active" id="grn-list">
        <div class="card-body">
            <div class="table-responsive">
                <table class="my-datatable table table-striped table-editable table-edits table" id="grnTable">
                    <thead>
                        <tr>
                            <th>Sr No</th>
                            <th>Date</th>
                            <th>GRN Number</th>
                            <th>Vin Number</th>
                        </tr>
                    </thead>
                    <tbody>
                    <div hidden>{{$i=0;}}
                    </div>
                        @foreach($grns as $grn)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td class="date">{{ $grn->Movementrefernce->date ?? '' }}</td>
                            <td class="grn-number">{{ $grn->MovementGrn->grn_number ?? ''}}</td>
                            <td class="vin-number">{{ $grn->vin }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@else
<div class="card-header">
    <p class="card-title">Sorry! You don't have permission to access this page</p>
    <a style="float:left;" class="btn btn-sm btn-info" href="/"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go To Dashboard</a>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go Back To Previous Page</a>
</div>
@endif
@endsection

@push('scripts')
<script>
    function filterTable() {
        const filterDate = document.getElementById('filterDate').value.toLowerCase();
        const filterGRN = document.getElementById('filterGRN').value.toLowerCase();
        const filterVIN = document.getElementById('filterVIN').value.toLowerCase();

        const rows = document.querySelectorAll('#grnTable tbody tr');

        rows.forEach(row => {
            const dateCell = row.querySelector('.date').textContent.toLowerCase();
            const grnCell = row.querySelector('.grn-number').textContent.toLowerCase();
            const vinCell = row.querySelector('.vin-number').textContent.toLowerCase();

            const isDateMatch = filterDate ? dateCell.includes(filterDate) : true;
            const isGRNMatch = filterGRN ? grnCell.includes(filterGRN) : true;
            const isVINMatch = filterVIN ? vinCell.includes(filterVIN) : true;

            if (isDateMatch && isGRNMatch && isVINMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush