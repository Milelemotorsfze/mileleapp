@extends('layouts.table')
@section('content')
        <div class="card-header">
            <h4 class="card-title">Purchase Price Histories</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger" >
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('error') }}
                </div>
            @endif
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
        </div>
        @can('warranty-purchase-price-histories-list')
        @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-purchase-price-histories-list']);
        @endphp
        @if ($hasPermission)
         <div class="card-body">
            <div class="table-responsive">
                <table id="purchase-price-histories-table" class="table table-striped table-editable table-edits table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Old Price (AED)</th>
                        <th>Updated Price (AED)</th>
                        <th>Created By</th>
                        <th>Updated By</th>
                        <th>Date & Time</th>
                    </tr>
                    </thead>
                    <tbody>
                    <div hidden>{{$i=0;}}</div>
                    @foreach ($priceHistories as $key => $priceHistory)
                        <tr data-id="1">
                            <td>{{ ++$i }}</td>
                            <td>{{ $priceHistory->old_price ?? ''}}</td>
                            <td>{{ $priceHistory->updated_price }}</td>
                            <td>{{ $priceHistory->createdBy->name ?? '' }}</td>
                            <td>{{ $priceHistory->user->name ?? '' }} </td>
                            <td>{{ $priceHistory->updated_at }} </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
           
        })
    </script>
@endpush
