@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Addon List</h4>
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('addon.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Addon</a>
  </div>
  <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
          <thead>
            <tr>
              <th>No</th>
              <th>Image</th>
              <th>Addon Name</th>
              <th>Addon Code</th>
              <th>Brand</th>
              <th>Model Line</th>
              <th>Lead Time</th>
              <th>Additional Remarks</th>
              <th>Purchase Price ( AED )</th>
              <th>Selling Price ( AED )</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <div hidden>{{$i=0;}}</div>
            @foreach ($addons as $key => $addon)
              <tr data-id="1">
                <td>{{ ++$i }}</td>
                <td><img src="{{ asset('addon_image/' . $addon->image) }}" style="width:100%;" /></td>
                <td>{{ $addon->addon_id }}</td>
                <td>{{ $addon->addon_code }}</td>
                <td>{{ $addon->brand_id }}</td>
                <td>{{ $addon->model_id }}</td>
                <td>{{ $addon->lead_time }}</td>
                <td>{{ $addon->additional_remarks }}</td>
                <td>{{ $addon->purchase_price }}</td>
                <td>{{ $addon->selling_price }}</td>
                <td>
                  <!-- @can('role-edit') -->
                    <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('addon.edit',$addon->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                  <!-- @endcan -->
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
@endsection
