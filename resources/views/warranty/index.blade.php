@extends('layouts.table')
@section('content')
@can('warranty-list')
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-list']);
@endphp
@if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">Warranty Info</h4>
    @can('warranty-create')
    @php
    $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-create']);
    @endphp
    @if ($hasPermission)
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('warranty.create') }}" text-align: right>
      <i class="fa fa-plus" aria-hidden="true"></i> New Warranty</a>
    @endif
    @endcan
      @can('warranty-sales-view')
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-sales-view']);
      @endphp
      @if ($hasPermission)
      <a style="float: right;margin-right: 2px" class="btn btn-sm btn-info" href="{{ route('warranty.view') }}" text-align: right>
      <i class="fa fa-table" aria-hidden="true"></i></a>
      @endif
      @endcan
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>No</th>
              <th>Policy Name</th>
              <th>Vehicle Category</th>
              <th>Eligibility Years</th>
              <th>Elegibility Mileage</th>
              <th>Extended Warranty Period</th>
              <th>Extended Warranty Mileage</th>
              <th>Claim Limit</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <div hidden>{{$i=0;}}</div>
            @foreach ($premiums as $key => $premium)
            <tr data-id="1">
              <td>{{ ++$i }}</td>
              <td>{{ $premium->PolicyName->name ?? ''}}</td>
              <td>
                @if($premium->vehicle_category1 == 'non_electric')
                  Non Electric /
                @elseif($premium->vehicle_category1 == 'electric')
                  Electric /
                @endif
                @if($premium->vehicle_category2 == 'normal_and_premium')
                  Normal And Premium
                @elseif($premium->vehicle_category2 == 'lux_sport_exotic')
                  Lux Sport Exotic
                @endif
              </td>
              <td>{{ $premium->eligibility_year }} Years</td>
              <td>{{ $premium->eligibility_milage }} KM</td>
              <td>{{ $premium->extended_warranty_period }} Months</td>
              <td>
                @if($premium->is_open_milage == 'yes')
                  Open Mileage
                @elseif($premium->is_open_milage == 'no')
                  {{$premium->extended_warranty_milage}} KM
                @endif
              </td>
              <td>{{ $premium->claim_limit_in_aed }} AED</td>
              <td>
                @if($premium->status == 'active')
                  <label class="badge badge-soft-success">{{ $premium->status }}</label>
                @elseif($premium->status == 'inactive')
                  <label class="badge badge-soft-danger">{{ $premium->status }}</label>
                @endif
              </td>
              <td>
                @can('warranty-view')
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-view']);
                @endphp
                @if ($hasPermission)
                <a class="btn btn-sm btn-success" href="{{ route('warranty.show',$premium->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                @endif
                @endcan
                @can('warranty-edit')
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-edit']);
                @endphp
                @if ($hasPermission)
                <a class="btn btn-sm btn-info" href="{{ route('warranty.edit',$premium->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                @endif
                @endcan
                @can('warranty-delete')
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-delete']);
                @endphp
                @if ($hasPermission)
                    <button type="button" class="btn btn-danger btn-sm warranty-delete sm-mt-3"
                            data-id="{{ $premium->id }}" data-url="{{ route('warranty.destroy', $premium->id) }}">
                        <i class="fa fa-trash"></i>
                    </button>
                @endif
                @endcan
                @can('warranty-active-inactive')
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole(['warranty-active-inactive']);
                @endphp
                @if ($hasPermission)
                @if($premium->status == 'active')
                  <button title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary status-inactive-button"
                          data-id="{{ $premium->id }}" data-status="inactive" >
                      <i class="fa fa-ban" aria-hidden="true"></i></button>
                @elseif($premium->status == 'inactive')
                  <a data-id="{{ $premium->id }}" data-status="active" title="Make Active" data-placement="top" class="btn btn-sm btn-primary status-active-button" >
                      <i class="fa fa-check" aria-hidden="true"></i></a>
                @endif
                @endif
                @endcan
              </td>
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
    <script>
        $('.warranty-delete').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });

        $('.status-active-button').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })
        $('.status-inactive-button').click(function (e) {
            // alert("ok");
            var status = $(this).attr('data-status');
            var id =  $(this).attr('data-id');
            statusChange(id,status)
        })

        function statusChange(id,status) {
            let url = '{{ route('warranty-brands.status-change') }}';
            if(status == 'active') {
                var message = 'Active';
            }else{
                var message = 'Inactive';
            }
            var confirm = alertify.confirm('Are you sure you want to '+ message +' this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            id: id,
                            status: status,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (data) {
                            window.location.reload();
                            alertify.success(status + " Successfully");
                        }
                    });
                }
            }).set({title:"Status Change"})
        }
    </script>
@endpush
