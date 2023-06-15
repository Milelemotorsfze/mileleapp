@extends('layouts.table')
@section('content')
@can('warranty-list')
  <div class="card-header">
    <h4 class="card-title">Warranty Info</h4>
    @can('warranty-create')
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('warranty.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Warranty</a>  
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
              <td>{{ $premium->PolicyName->name }}</td>
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
                <a class="btn btn-sm btn-success" href=""><i class="fa fa-eye" aria-hidden="true"></i></a>
                @endcan
                @can('warranty-edit')
                <a class="btn btn-sm btn-info" href="{{ route('warranty.edit',$premium->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                @endcan
                @can('warranty-delete')
                <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id=""> <i class="fa fa-trash" aria-hidden="true"></i></a>
                @endcan
                @can('warranty-active-inactive')
                @if($premium->status == 'active')
                  <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" data-modal-id=""><i class="fa fa-ban" aria-hidden="true"></i></a>
                @elseif($premium->status == 'inactive')
                  <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-primary modal-button" data-modal-id=""><i class="fa fa-check" aria-hidden="true"></i></a>
                @endif
                @endcan
              </td>
            </tr>
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
 

@endcan
    @endsection

