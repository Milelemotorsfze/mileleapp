@extends('layouts.table')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    div.dataTables_wrapper div.dataTables_info {
  padding-top: 0px;
}
  #dtBasicExample1 tbody tr:hover {
    cursor: pointer;
  }
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
  padding: 4px 8px 4px 8px;
  text-align: center;
  vertical-align: middle;
}
.table-wrapper {
      position: relative;
    }
    thead th {
      position: sticky!important;
      top: 0;
      background-color: rgb(194, 196, 204)!important;
      z-index: 1;
    }
    #table-responsive {
      height: 100vh;
      overflow-y: auto;
    }
    #dtBasicSupplierInventory {
      width: 100%;
      font-size: 12px;
    }
    th.nowrap-td {
      white-space: nowrap;
      height: 10px;
    }
    .nav-pills .nav-link {
      position: relative;
    }

    .badge-notification {
      position: absolute;
      top: 0;
      right: 0;
      transform: translate(50%, -110%);
      background-color: red;
      color: white;
      border-radius: 50%;
      padding: 0.3rem 0.6rem;
    }
  </style>
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole('posting-records');
  @endphp
  @if ($hasPermission)
  <div class="card-header">
  @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<a class="btn btn-sm btn-Success float-end" href="{{ route('posting.createposting', ['leadssource_id' => $leadssource->id]) }}" text-align: right>
        <i class="fa fa-check" aria-hidden="true"></i> Add New Posting
      </a>
      <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
    <h4 class="card-title">
     Posting Records - {{$leadssource->source_name}} 
    </h4>
    <br>    
  </div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table-bordered">
            <thead class="bg-soft-secondary">
                <tr>
                <th>Brand</th>
                <th>Model lines</th>
                <th>Varaints</th>
                <th>Exterior Colour</th>
                <th>Interior Colour</th>
                <th>Videos</th>
                <th>Reels</th>
                <th>Pictures</th>
                <th>Ads</th>
                <th>Stories</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>    
      </div>
    </div>
  </div>
  <script>
        $(document).ready(function () {
            var url = window.location.href; // Get the current URL
    var id = url.substring(url.lastIndexOf('/') + 1); // Extract the ID from the URL
          var table1 =  $('#dtBasicExample1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('postingrecords', ['id' => ':id']) }}".replace(':id', id), // Use the ID extracted from the URL
            columns: [
            { data: 'brand_name', name: 'brands.brand_name' },
            { data: 'model_line', name: 'master_model_lines.model_line' },
            { data: 'name', name: 'varaints.name' },
            { data: 'exterior_colour', name: 'exterior_colour' },
            { data: 'interior_colour', name: 'interior_colour' },
            { data: 'videos', name: 'posting_platforms.videos' },
            { data: 'reels', name: 'posting_platforms.reels' },
            { data: 'Pictures', name: 'posting_platforms.Pictures' },
            { data: 'ads', name: 'posting_platforms.ads' },
            { data: 'stories', name: 'posting_platforms.stories' }
            ]
        });
    });
    </script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection