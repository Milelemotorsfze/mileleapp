@extends('layouts.table')
@section('content')
@php
  $hasPermission = Auth::user()->hasPermissionForSelectedRole(['Calls-modified', 'approve-reservation']);
  @endphp
  @if ($hasPermission)
  <div class="card-header">
    <h4 class="card-title">
     Repeated Customers Info
    </h4>
  </div>
  <div class="tab-content">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Sales Person</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Source</th>
                  <th>Preferred Language</th>
                  <th>Destination</th>
                  <th>Remarks & Messages</th>
                  <th>Sales Status</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($calls as $key => $calls)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ date('d-m-Y (H:i A)', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>     
                    <td>{{ $calls->phone }}</td> 
                    <td>{{ $calls->email }}</td>
                     @php
                     $sales_persons_name = "";
                     $sales_persons = DB::table('users')->where('id', $calls->sales_person)->first();
                     $sales_persons_name = $sales_persons->name;
                     @endphp  
                    <td>{{ $sales_persons_name }}</td>
                    @php
    $leads_models_brands = DB::table('calls_requirement')
        ->select('calls_requirement.model_line_id', 'master_model_lines.brand_id', 'brands.brand_name', 'master_model_lines.model_line')
        ->join('master_model_lines', 'calls_requirement.model_line_id', '=', 'master_model_lines.id')
        ->join('brands', 'master_model_lines.brand_id', '=', 'brands.id')
        ->where('calls_requirement.lead_id', $calls->id)
        ->get();
@endphp

<td>
    @php
        $models_brands_string = '';
        foreach ($leads_models_brands as $lead_model_brand) {
            $models_brands_string .= $lead_model_brand->brand_name . ' - ' . $lead_model_brand->model_line . ', ';
        }
        // Remove the trailing comma and space from the string
        $models_brands_string = rtrim($models_brands_string, ', ');
        echo $models_brands_string;
    @endphp
</td>
                    <td>{{ $calls->custom_brand_model }}</td>
                    @php
                    $leadsources = "";
                     $leadsource = DB::table('lead_source')->where('id', $calls->source)->first();
                     $leadsources = $leadsource->source_name;
                     @endphp
                    <td>{{ $leadsources }}</td>
                    <td>{{ $calls->language }}</td>
                    <td>{{ $calls->location }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>   
    <td>{{ $calls->status }}</td>      
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      </div>
  </div>
  @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection