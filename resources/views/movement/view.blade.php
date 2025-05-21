@extends('layouts.main')
@section('content')

<style>
    .is-invalid {
        padding-top: 5px !important;
    }
</style>

@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole('View-daily-movemnets');
@endphp
@if ($hasPermission)
<div class="card-header">
    <h4 class="card-title">Movements Transition</h4>
    @if ($previousId)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($previousId)]) }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i>
    </a>
    @endif
    <b>Ref No: {{$currentId}}</b>
    @if ($nextId)
    <a class="btn btn-sm btn-info" href="{{ route('movement.lastReference', ['currentId' => ($nextId)]) }}">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
    @else
    <a class="btn btn-sm btn-info" href="{{ route('movement.create') }}">
        <i class="fa fa-arrow-right" aria-hidden="true"></i>
    </a>
    @endif
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Date Of Movement:</label>
            <div>{{ \Carbon\Carbon::parse($movementref->date)->format('j-M-Y') }}</div>
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead class="bg-soft-secondary">
                <tr>
                    <th>VIN</th>
                    <th>Model Line</th>
                    <th>From</th>
                    <th>To</th>
                    <th>SO</th>
                    <th>PO</th>
                    <th>Revised</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movement as $movements)
                <tr>
                    <td>{{ $movements->vin }}</td>
                    @php
                    $vehicle = DB::table('vehicles')->where('vin', $movements->vin)->first();
                    $variant = DB::table('varaints')->where('id', $vehicle->varaints_id ?? 0)->first();
                    $modelLine = DB::table('master_model_lines')->where('id', $variant->master_model_lines_id ?? 0)->value('model_line');
                    $from = DB::table('warehouse')->where('id', $movements->from)->value('name');
                    $to = DB::table('warehouse')->where('id', $movements->to)->value('name');
                    $so = DB::table('so')->where('id', $vehicle->so_id ?? 0)->value('so_number');
                    $po = DB::table('purchasing_order')->where('id', $vehicle->purchasing_order_id ?? 0)->value('po_number');
                    $latestMovement = DB::table('movements')->where('vin', $movements->vin)->orderByDesc('created_at')->first();
                    @endphp
                    <td>{{ $modelLine }}</td>
                    <td>{{ $from }}</td>
                    <td>{{ $to }}</td>
                    <td>{{ $so }}</td>
                    <td>{{ $po }}</td>
                    <td>
                        @if($movementref->created_by == auth()->id() && $latestMovement && $latestMovement->id == $movements->id)
                        <button type="button" class="btn btn-sm btn-danger revise-btn" data-id="{{ $movements->id }}">
                            Revise
                        </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="reviseModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="reviseForm" method="POST" action="">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Revise Movement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label><strong>Date of Movement:</strong></label>
                        <input type="date" name="date" class="form-control" required>
                        <br>
                        <label><strong>New To Location:</strong></label>
                        <select name="to" id="revise-to-select" class="form-control" required>
                            <option value="" selected disabled>Select To Location</option>
                            @foreach ($warehouses as $warehouse)
                            @if ($warehouse->name !== 'Supplier')
                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endif
                            @endforeach
                        </select>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')

<script>
    $(document).ready(function () {
        let reviseFrom = null;

        $('.revise-btn').click(function () {
            const id = $(this).data('id');
            reviseFrom = $(this).closest('tr').find('td:nth-child(4)').text().trim(); 

            const formAction = `{{ url('movement/revised') }}/${id}`;
            $('#reviseForm').attr('action', formAction);

            const today = new Date().toISOString().split('T')[0];
            $('input[name="date"]').attr('max', today);

            $('#reviseModal').modal('show');
        });

        $('#reviseModal').on('shown.bs.modal', function () {
            $('#revise-to-select').select2({
                dropdownParent: $('#reviseModal')
            });
        });

        $('#reviseForm').on('submit', function (e) {
            const selectedToText = $('#revise-to-select option:selected').text().trim();

            if (reviseFrom && selectedToText && reviseFrom === selectedToText) {
                e.preventDefault();
                const sameLocationErrors = 'From and To locations cannot be the same.';
                alertify.alert("Location Conflict", sameLocationErrors);
            }
        });
    });
</script>

@endpush