@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Bill of Lading Form</h4>
    <div class="clearfix"></div>
    <br>
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Bill of Lading Form</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">All VINs of BL</a>
      </li>
    </ul>
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                </tr>
              </thead>
              <tbody>
                <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('blform.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Add New BL Form</a>
                <br>
                <br>
                <div class="clearfix"></div>
                <tr>
                  @php
                    $vinsresult = DB::table('bl_vinsdata')->get();
                  @endphp
                  @foreach ($vinsresult as $row)
                    $vindata = $row->vin_number;
                    $blnumber = $row->bl_number;
                    <td>{{ $vindata }}</td>
                    <td>{{ $blnumbero }}</td>
                  @endforeach
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                </tr>
              </thead>
              <tbody>
                <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('blform.create') }}"><i class="fa fa-plus" aria-hidden="true"></i> Add New VIN</a>
                <br>
                <br>
                <div class="clearfix"></div>
                <tr>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
@endsection
