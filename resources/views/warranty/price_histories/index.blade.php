@extends('layouts.table')
@section('content')
    @can('warranty-list')
        <div class="card-header">
            <h4 class="card-title">Warranty Price Histories</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Old Price</th>
                        <th>Updated Price</th>
                        <th>Updated By</th>
                        <th>Date & Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <div hidden>{{$i=0;}}</div>
                    @foreach ($priceHistories as $key => $priceHistory)
                        <tr data-id="1">
                            <td>{{ ++$i }}</td>
                            <td>{{ $priceHistory->old_price }}</td>
                            <td>{{ $priceHistory->updated_price }}</td>
                            <td>{{ $priceHistory->user->name }} </td>
                            <td>{{ $priceHistory->updated_at }} </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endcan
@endsection
