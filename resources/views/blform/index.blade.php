@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Bill of Lading Form</h4>
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('blfrom.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i>Add New BL Form</a>
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
                <h2>Table 1</h2>
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
                <h2>Table 2</h2>
              </tbody>
            </table>
          </div> 
        </div>  
      </div>
      </div>
    </div>
  </div>
@endsection