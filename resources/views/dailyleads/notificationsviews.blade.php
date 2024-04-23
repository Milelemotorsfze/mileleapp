@extends('layouts.main')
@section('content')
<div class="card-header">
        <h4 class="card-title">View Notifications</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
        <div class="table-responsive" >
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                @if($additionalValue == "Pending Lead")
                  <th>Priority</th>
                  <th>Lead Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Action</th>
                  @else
                  <th>Priority</th>
                  <th>Lead Date</th>
                  <th>Purchase Type</th>
                  <th>Customer Name</th>
                  <th>Customer Phone</th>
                  <th>Customer Email</th>
                  <th>Brands & Models</th>
                  <th>Custom Model & Brand</th>
                  <th>Preferred Language</th>
                  <th>Location</th>
                  <th>Remarks & Messages</th>
                  <th>Action</th>
                @endif
                </tr>
                </thead>
                <tbody>
                @foreach ($data as $calls)
                    <tr data-id="{{$calls->id}}">
                <td>
                    @if ($calls->priority == "High")
                        <i class="fas fa-circle blink" style="color: red;"> Hot</i>
                    @elseif ($calls->priority == "Normal")
                        <i class="fas fa-circle" style="color: green;"> Normal</i>
                    @elseif ($calls->priority == "Low")
                        <i class="fas fa-circle" style="color: orange;"> Low</i>
                    @else
                        <i class="fas fa-circle" style="color: black;"> Regular</i>
                    @endif
                </td>
                    <td>{{ date('d-M-Y', strtotime($calls->created_at)) }}</td>
                    <td>{{ $calls->type }}</td>
                    <td>{{ $calls->name }}</td>
                    <td>
                    <div class="dropdown">
                    <a href="#" role="button" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ $calls->phone }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style=" min-width: 0; padding: 0;">
                        <li>
                            <a class="dropdown-item" href="#" onclick="openWhatsApp('{{ $calls->phone }}')">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="tel:{{ $calls->phone }}">
                                <i class="fas fa-phone"></i>
                            </a>
                        </li>
                        </ul>
                    </div>
                    </td>
                    <td><a href="mailto:{{ $calls->email }}">{{ $calls->email }}</a></td>
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
                    <td>{{ $calls->language }}</td>
                    <td>{{ $calls->location }}</td>
                    @php
    $text = $calls->remarks;
    $remarks = preg_replace("#([^>])&nbsp;#ui", "$1 ", $text);
    @endphp
    <td>{{ str_replace(['<p>', '</p>'], '', strip_tags($remarks)) }}</td>
                    <td>
                    <div class="dropdown">
    <button type="button" class="btn btn-sm btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="Adding Into Demand">
      <i class="fa fa-bars" aria-hidden="true"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end">
    <li><a class="dropdown-item" href="#" onclick="openModalfellowup('{{ $calls->id }}')">FollowUp</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModalp('{{ $calls->id }}')">Prospecting</a></li>
      <li><a class="dropdown-item" href="#" onclick="openModald('{{ $calls->id }}')">Unique Inquiry / Demand</a></li>
      <!-- <li><a class="dropdown-item" href="#" onclick="openModal('{{ $calls->id }}')">Quotation</a></li> -->
      <li><a class="dropdown-item"href="{{route('qoutation.proforma_invoice',['callId'=> $calls->id]) }}">Quotation</a></li>
      <!-- <li><a class="dropdown-item" href="#" onclick="openModalqualified('{{ $calls->id }}')">Negotiation</a></li> -->
      <!-- <li><a class="dropdown-item" href="{{ route('booking.create', ['call_id' => $calls->id]) }}">Booking Vehicles</a></li> -->
      <!-- <li><a class="dropdown-item" href="">Booking (Coming Soon)</a></li> -->
      <!-- <li><a class="dropdown-item" href="#" onclick="openModalclosed('{{ $calls->id }}')">Sales Order</a></li> -->
      <!-- <li><a class="dropdown-item"href="{{route('salesorder.createsalesorder',['callId'=> $calls->id]) }}">Sales Order</a></li> -->
      <li><a class="dropdown-item" href="#" onclick="openModalr('{{ $calls->id }}')">Rejected</a></li>
    </ul>
  </div>
                    </td>
                    </td>
                  </tr>
                @endforeach
                        </tbody>
            </table>
        </div>
		</br>
    </div>
    @endsection
@push('scripts')
@endpush