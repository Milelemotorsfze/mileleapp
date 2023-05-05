<div class="table-responsive" id="addonListTable" hidden>     
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
            <th>Purchase Price(AED)</th>
            <th>Selling Price(AED)</th>
            <th>Payment Condition</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <div hidden>{{$i=0;}}
          </div>
          @foreach ($addons as $key => $addon)
            <tr data-id="1">
              <td>{{ ++$i }}</td>
              <td><img src="{{ asset('addon_image/' . $addon->image) }}" style="width:100%; height:100px;" /></td>
              <td>{{ $addon->name }}</td>
              <td>{{ $addon->addon_code }}</td>
              <td>{{ $addon->brand_name }}</td>
              <td>{{ $addon->model_line }}</td>
              <td>{{ $addon->lead_time }}</td>
              <td>{{ $addon->additional_remarks }}</td>
              <td>{{ $addon->purchase_price }}</td>
              <td>{{ $addon->selling_price }}</td>
              <td>{{ $addon->payment_condition }}</td>
              <td>
                <a class="btn btn-sm btn-info" href="{{ route('addon.view',$addon->addon_details_table_id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                <a class="btn btn-sm btn-info" href="{{ route('addon.editDetails',$addon->addon_details_table_id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>