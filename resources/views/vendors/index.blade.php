@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title">
            Vendors List
        </h4>
{{--        @can('vendors-create')--}}
            <a class="btn btn-sm btn-info float-end" href="{{ route('vendors.create') }}" text-align: right>
                <i class="fa fa-plus" aria-hidden="true"></i> New Vendor
            </a>
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <div class="clearfix"></div>
            <br>
{{--        @endcan--}}
    </div>

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
{{--    @can('vendors-list')--}}
        <div class="card-body">
            <div class="table-responsive" >
                <table id="vendor-table" class="table table-striped table-editable table-edits table table-condensed" style="">
                    <thead class="bg-soft-secondary">
                    <tr>
                        <th>Ref.NO</th>
                        <th>Individual/ Company Name </th>
                        <th>Type</th>
                        <th>Category</th>
{{--                        <th>Nationality</th>--}}
                        <th>Email</th>
                        <th>Primary Contact Number</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <div hidden>{{$i=0;}}
                    </div>
                    @foreach ($vendors as $key => $vendor)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $vendor->trade_name_or_individual_name }}</td>
                            <td>{{ $vendor->vendor_type }}</td>
                            <td>@if($vendor->category == 'vehicle-procurment')
                                    Vehicle Procurment
                                @elseif($vendor->category == 'parts-procurment')
                                    Parts Procurment
                                @elseif($vendor->category == 'IT')
                                    IT
                                @else

                                @endif
                              </td>
{{--                            <td>{{ $vendor->nationality  }}</td>--}}
                            <td>{{ $vendor->email }}</td>
                            <td>{{ $vendor->mobile }}</td>
                            <td>
{{--                                @can('vendors-view')--}}
{{--                                    <a href="{{ route('vendors.show',$vendor->id) }}">--}}
{{--                                        <button type="button" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i> </button>--}}
{{--                                    </a>--}}
{{--                                @endcan--}}
{{--                                @can('vendors-edit')--}}
                                    <a href="{{ route('vendors.edit',$vendor->id) }}">
                                        <button type="button" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> </button>
                                    </a>
{{--                                @endcan--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
{{--    @endcan--}}
@endsection