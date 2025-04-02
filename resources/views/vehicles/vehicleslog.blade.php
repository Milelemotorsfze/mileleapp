@extends('layouts.table')
<style>
    .row {
        display: flex;
        align-items: center;
    }
    .label {
    flex-basis: 30%;
    font-family: Calibri, sans-serif;
    font-size: 16px;
}
.value {
    flex-basis: 70%;
    font-family: Calibri, sans-serif;
    font-size: 16px;
    color: #333;
}
.bordered-section {
        border: 1px solid #ccc;
        padding: 10px;
    }
    .bordered-section {
        border: 1px solid #ccc;
        padding: 10px;
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Vehicle Detail</h4>
        <div class="d-flex justify-content-between align-items-center">
        <div>
            @if ($previousId)
                <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $previousId) }}">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i>
                </a>
            @endif
        </div>
        <div>
            <center><b>Vehicle Identification Number:
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('vin-view');
                    @endphp
                    @if ($hasPermission)
                    {{$vehicle->vin}}
                    @endif
                </b></center>
        </div>
        <div>
            @if ($nextId)
                <a class="btn btn-sm btn-info" href="{{ route('vehicleslog.viewdetails', $nextId) }}">
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>
                </a>
            @endif
        </div>
    </div>
    <br>
    @php
            $picturespdi = DB::table('vehicle_pictures')->where('vehicle_id', $vehicle->id)->where('category', 'PDI')->first();
            $pdipictures = $picturespdi->vehicle_picture_link ?? '';
            @endphp
            @if($pdipictures)
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a title="Vehicles PDI Pictures Details" data-placement="top" class="btn btn-sm btn-primary float-end" href="{{$pdipictures}}" target="_blank">View PDI Pictures</a>
            @endif
            @php
            $picturesmodification = DB::table('vehicle_pictures')->where('vehicle_id', $vehicle->id)->where('category', 'Modification')->first();
            $modificationpictures = $picturesmodification->vehicle_picture_link ?? '';
            @endphp
            @if($modificationpictures)
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a title="Vehicles Modification Pictures Details" data-placement="top" class="btn btn-sm btn-primary float-end" href="{{$modificationpictures}}" target="_blank">View Modification Pictures</a>
            @endif
            @php
            $picturegrn = DB::table('vehicle_pictures')->where('vehicle_id', $vehicle->id)->where('category', 'GRN')->first();
            $grnpictures = $picturegrn->vehicle_picture_link ?? '';
            @endphp
            @if($grnpictures)
            <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
            <a title="Vehicles GRN Pictures Details" data-placement="top" class="btn btn-sm btn-primary float-end" href="{{$grnpictures}}" target="_blank">View GRN Pictures</a>
            @endif
    </div>

    <div class="card-body">
    @php
    $po = DB::table('purchasing_order')->where('id', $vehicle->purchasing_order_id)->first();
    $po_date = $po->po_date ?? '';
    $po_number = $po->po_number ?? '';
    $so = DB::table('so')->where('id', $vehicle->so_id)->first();
    $so_date = $so->so_date ?? '';
    $so_number = $so->so_number ?? '';
    $sales_person_id = $so->sales_person_id ?? '';
    $salesPersonName = $so ? DB::table('users')->where('id', $sales_person_id)->value('name') : '';
    $grn = $vehicle->movement_grn_id ? DB::table('movement_grns')->where('id', $vehicle->movement_grn_id)->first() : null;
    $grn_date = $grn ? $grn->date : null;
    $grn_number = $grn ? $grn->grn_number : null;
    $gdn = $vehicle->gdn_id ? DB::table('gdn')->where('id', $vehicle->gdn_id)->first() : null;
    $gdn_date = $gdn ? $gdn->date : null;
    $gdn_number = $gdn ? $gdn->gdn_number : null;
    $result = DB::table('varaints')
                                                ->join('brands', 'varaints.brands_id', '=', 'brands.id')
                                                ->join('master_model_lines', 'varaints.master_model_lines_id', '=', 'master_model_lines.id')
                                                ->where('varaints.id', $vehicle->varaints_id)
                                                ->select('varaints.name', 'varaints.my', 'varaints.detail', 'varaints.upholestry', 'varaints.steering', 'varaints.fuel_type', 'varaints.seat','varaints.gearbox','varaints.model_detail', 'brands.brand_name AS brand_name', 'master_model_lines.model_line')
                                                ->first();
                                                 if($result) {
                                                    $varaints_name = $result->name;
                                                    $varaints_my = $result->my;
                                                    $varaints_steering = $result->steering;
                                                    $varaints_fuel_type = $result->fuel_type;
                                                    $varaints_seat = $result->seat;
                                                    $varaints_detail = $result->detail;
                                                    $varaints_gearbox = $result->gearbox;
                                                    $varaints_upholestry = $result->upholestry;
                                                    $brand_name = $result->brand_name;
                                                    $model_line = $result->model_line;
                                                    $model_detail = $result->model_detail;
                                                 }
    use Carbon\Carbon;
    if($po_date){
    $po_date = Carbon::createFromFormat('Y-m-d', $po_date)->format('d-M-Y');
    }
    if($grn_date){
    $grn_date = Carbon::createFromFormat('Y-m-d', $grn_date)->format('d-M-Y');
    }
    if($gdn_date){
    $gdn_date = Carbon::createFromFormat('Y-m-d', $gdn_date)->format('d-M-Y');
    }
    $exColour = $vehicle->ex_colour ? DB::table('color_codes')->where('id', $vehicle->ex_colour)->first() : null;
    $ex_colours = $exColour ? $exColour->name : null;
    $intColour = $vehicle->int_colour ? DB::table('color_codes')->where('id', $vehicle->int_colour)->first() : null;
    $int_colours = $intColour ? $intColour->name : null;
    $warehouse = $vehicle->vin ? DB::table('movements')->where('vin', $vehicle->vin)->latest()->first() : null;
    $warehouses = $warehouse ? DB::table('warehouse')->where('id', $warehouse->to)->value('name') : null;
    $documents = $vehicle->documents_id ? DB::table('documents')->where('id', $vehicle->documents_id)->first() : null;
    $import_type = $documents ? $documents->import_type : null;
    $owership = $documents ? $documents->owership : null;
    $document_with = $documents ? $documents->document_with : null;
    $bl_number = $documents ? $documents->bl_number : null;
    $latestRemark = DB::table('vehicles_remarks')->where('vehicles_id', $vehicle->id)->where('department', 'sales')->orderBy('created_at', 'desc')->value('remarks');
    @endphp
    <div class="bordered-section">
        <div class="row">
            <div class="col-sm-3">
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>PO Number:</strong></strong>
                </div>
                <div class="col-lg-8 value">
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                    @endphp
                    @if ($hasPermission)
                        {{$po_number}}
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>PO Date:</strong>
                </div>
                <div class="col-lg-8 value">
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-po');
                    @endphp
                    @if ($hasPermission)
                        {{$po_date}}
                    @endif

                </div>
            </div>
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('stock-status-view');
            @endphp
            @if ($hasPermission)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong> Stock Status:</strong>
                </div>
                <div class="col-lg-8 value">
                 </div>
           </div>
           @endif
        </div>
            <div class="col-sm-3">
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('estimated-arrival-view');
        @endphp
        @if ($hasPermission)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>Estimated Arrival:</strong>
                </div>
                <div class="col-lg-8 value">
                    @if($vehicle->estimation_date)
                        {{ date('d-M-Y', strtotime($vehicle->estimation_date)) }}
                    @else
                        {{$vehicle->estimation_date}}
                    @endif
                </div>
            </div>
         @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('ETA-timer-view');
        @endphp
        @if ($hasPermission)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong> ETA Timer:</strong>
                </div>
                <div class="col-lg-8 value">
                    @if($vehicle->estimation_date && !isset($grn_number))
                    @php
                        $estimationDate = \Carbon\Carbon::parse($vehicle->estimation_date);
                        $today = \Carbon\Carbon::now();
                        $days = $today->diffInDays($estimationDate);
                    @endphp
                    {{ $days }} days
                    @else
                     @endif
                </div>
            </div>
        @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('aging-view');
        @endphp
        @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong>Aging:</strong>
            </div>
            <div class="col-lg-8 value">
                @if ($grn_date)
                     @php
                        $grn_date = Carbon::parse($grn_date);
                        $aging = $grn_date->diffInDays(\Carbon\Carbon::today());
                     @endphp
                     {{ $aging }}
                     @else
                     @php
                        $paymentLog = DB::table('payment_logs')->where('vehicle_id', $vehicle->id)->latest()->first();
                      @endphp
                      @if ($paymentLog)
                          @php
                              $savedDate = $paymentLog->date;
                              $today = now()->format('Y-m-d');
                              $numberOfDays = Carbon::parse($savedDate)->diffInDays($today);
                          @endphp
                         {{$numberOfDays}}
                      @else
                      @endif
                @endif
            </div>
        </div>
        @endif
    </div>
            <div class="col-sm-3">
            @php
                $hasPermissionGrn = Auth::user()->hasPermissionForSelectedRole('grn-view');
            @endphp
            @if ($hasPermissionGrn)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>GRN Number:</strong>
                </div>
                <div class="col-lg-8 value">
                    {{$grn_number}}
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>GRN Date:</strong>
                </div>
                <div class="col-lg-8 value">
                    {{$grn_date}}
                </div>
            </div>
            @endif
            <div class="row">
                @php
                    $hasPermission = Auth::user()->hasPermissionForSelectedRole('inspection-view');
                @endphp
                @if ($hasPermission)
                    <div class="col-lg-4 label">
                        <strong>Inspection Date:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        {{$vehicle->inspection_date}}
                    </div>
                @endif
            </div>
        </div>
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('gdn-view');
            @endphp
            @if ($hasPermission)
                <div class="col-sm-3">
                    <div class="row">
                        <div class="col-lg-4 label">
                            <strong>GDN Number:</strong>
                        </div>
                        <div class="col-lg-8 value">
                            {{$gdn_number}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 label">
                            <strong>GDN Date:</strong>
                        </div>
                        <div class="col-lg-8 value">
                            {{$gdn_date}}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <br>
    <div class="bordered-section">
        <div class="row">
            <div class="col-sm-3">

              <div class="row">
                  <div class="col-lg-4 label">
                      <strong>SO Number:</strong>
                  </div>
                  <div class="col-lg-8 value">
                      @php
                          $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                      @endphp
                      @if ($hasPermission)
                        {{$so_number}}
                      @endif

                  </div>
              </div>
              <div class="row">
                    <div class="col-lg-4 label">
                        <strong>SO Date:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        @php
                            $hasPermission = Auth::user()->hasPermissionForSelectedRole('view-so');
                        @endphp
                        @if ($hasPermission)
                            {{$so_date}}
                        @endif
                    </div>
              </div>
      </div>
              @php
                  $hasPermission = Auth::user()->hasPermissionForSelectedRole('reservation-view');
              @endphp
              @if ($hasPermission)
                  <div class="col-sm-3">
                        <div class="row">
                            <div class="col-lg-4 label">
                                <strong>Sales Person:</strong>
                            </div>
                            <div class="col-lg-8 value">
                                @if ($salesPersonName)
                                    {{ $salesPersonName }}
                                @else
                                 @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 label">
                                <strong>Reservation Date:</strong>
                            </div>
                            <div class="col-lg-8 value">
                                @if($vehicle->reservation_start_date)
                                    {{ date('d-M-Y', strtotime($vehicle->reservation_start_date)) }}
                                @else
                                    {{$vehicle->reservation_start_date}}
                              @endif
                            </div>
                        </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="row">
                        <div class="col-lg-4 label">
                            <strong>Due Date:</strong>
                         </div>
                        <div class="col-lg-8 value">
                            @if($vehicle->reservation_end_date)
                                {{ date('d-M-Y', strtotime($vehicle->reservation_end_date)) }}
                            @else
                                {{$vehicle->reservation_end_date}}
                           @endif
                        </div>
                    </div>
                </div>
              @endif
        </div>
    </div>
  <br>
  <div class="bordered-section">
  <div class="row">
  <div class="col-sm-3">
  <div class="row">
      @php
          $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
      @endphp
      @if ($hasPermission)
        <div class="col-lg-4 label">
            <strong>Brand:</strong>
        </div>
        <div class="col-lg-8 value">
            {{$brand_name ?? ''}}</div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong>Model Line:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$model_line ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong>Model Desc:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$model_detail ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Model Year:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$varaints_my ?? ''}}
            </div>
        </div>
      @endif
      @php
          $hasPermission = Auth::user()->hasPermissionForSelectedRole('py-mm-yyyy-view');
      @endphp
      @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Production Year:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$vehicle->ppmmyyy ?? ''}}
            </div>
        </div>
      @endif

		</div>
    <div class="col-sm-3">
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
        @endphp
        @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong>Variant:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$varaints_name ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong>Variant Detail:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$varaints_detail ?? ''}}
            </div>
        </div>
        @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('enginee-view');
        @endphp
        @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Engine:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$vehicle->engine ?? ''}}
            </div>
        </div>
        @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('conversion-view');
        @endphp
        @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Conversion:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$vehicle->conversion ?? ''}}
            </div>
        </div>
        @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('territory-view');
        @endphp
        @if ($hasPermission)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>Territory:</strong>
                 </div>
                <div class="col-lg-8 value">
                    {{$vehicle->territory ?? ''}}
                </div>
            </div>
         @endif
    </div>
    <div class="col-sm-3">
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
        @endphp
        @if ($hasPermission)
            <div class="row">
            <div class="col-lg-4 label">
            <strong> Steering:</strong>
                 </div>
            <div class="col-lg-8 value">
                {{$varaints_steering ?? ''}}</div>
            </div>
            <div class="row">
            <div class="col-lg-4 label">
            <strong>Fuel Type:</strong>
                 </div>
            <div class="col-lg-8 value">
                {{$varaints_fuel_type ?? ''}}</div>
            </div>
            <div class="row">
            <div class="col-lg-4 label">
            <strong> Transmission:</strong>
                 </div>
            <div class="col-lg-8 value">
                {{$varaints_gearbox ?? ''}}</div>
            </div>
            <div class="row">
            <div class="col-lg-4 label">
            <strong>Seats:</strong>
                 </div>
            <div class="col-lg-8 value">
                {{$varaints_seat ?? ''}}</div>
            </div>
        @endif
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('price-view');
        @endphp
        @if ($hasPermission)
            <div class="row">
                <div class="col-lg-4 label">
                    <strong>Price:</strong>
                </div>
                <div class="col-lg-8 value">
                {{$vehicle->price ?? ''}}</div>
            </div>
        @endif
    </div>
    <div class="col-sm-3">
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-view');
        @endphp
        @if ($hasPermission)
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Exterior Colour:</strong>
            </div>
            <div class="col-lg-8 value">
                {{$ex_colours ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Interior Colour:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$int_colours ?? ''}}
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 label">
                <strong> Upholstery:</strong>
             </div>
            <div class="col-lg-8 value">
                {{$varaints_upholestry ?? ''}}
            </div>
        </div>
        @endif
      </div>
  </div>
</div>
  <br>
  <div class="bordered-section">
     <div class="row">
        <div class="col-sm-3">
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
            @endphp
            @if ($hasPermission)
                <div class="row">
                    <div class="col-lg-4 label">
                        <strong> Warehouse:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        {{$warehouses ?? ''}}
                    </div>
                </div>
            @endif
            @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('bl-view');
            @endphp
            @if ($hasPermission)
                <div class="row">
                    <div class="col-lg-4 label">
                        <strong> BL Number:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        {{$bl_number ?? ''}}
                    </div>
                </div>
            @endif
        </div>
         @php
             $hasPermission = Auth::user()->hasPermissionForSelectedRole('document-view');
         @endphp
         @if ($hasPermission)
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-lg-4 label">
                        <strong>Import Doc Type:</strong>
                         </div>
                    <div class="col-lg-8 value">
                        {{$import_type ?? ''}}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-lg-4 label">
                        <strong> Doc Owership:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        {{$owership ?? ''}}
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <div class="col-lg-4 label">
                        <strong> Doc With:</strong>
                    </div>
                    <div class="col-lg-8 value">
                        {{$document_with ?? ''}}
                    </div>
                </div>
            </div>
         @endif
    </div>
  </div>

  <br>
  <br>
    @php
        $hasPermissionVehicleDetailApprove = Auth::user()->hasPermissionForSelectedRole('vehicles-detail-approve');
        $hasPermissionEngineApprove = Auth::user()->hasPermissionForSelectedRole('engine-approve');
        $hasPermissionSOApprove = Auth::user()->hasPermissionForSelectedRole('approve-so');
        $hasPermissionInspectionApprove = Auth::user()->hasPermissionForSelectedRole('inspection-approve');
        $hasPermissionReservationApprove = Auth::user()->hasPermissionForSelectedRole('approve-reservation');
    @endphp
         @if($hasPermissionInspectionApprove || $hasPermissionVehicleDetailApprove || $hasPermissionSOApprove || $hasPermissionEngineApprove)
        <!-- <div class="card">
            <div class="card-header">
                <h4 class="card-title">Vehicle Detail Approval Requests</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="dtBasicExample3" class="table table-striped table-editable table table-bordered">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Date & Time</th>
                            <th>Updated By</th>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Status</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($pendingVehicleDetailApprovalRequests as $pendingVehicleDetailApprovalRequest)
                            <tr>
									@if($hasPermissionVehicleDetailApprove)
                                    @if(in_array($pendingVehicleDetailApprovalRequest->field, ['ex_colour','int_colour','varaints_id','ppmmyyy', 'engine', 'grn_remark', 'pdi_remarks']))
                                    <td>{{ Carbon::parse($pendingVehicleDetailApprovalRequest->created_at)->format('d M y, H:i:s') }}</td>
                                    <td>{{ $pendingVehicleDetailApprovalRequest->updatedBy->name }}</td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->field == 'ex_colour')
                                            Exterior Colour
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'int_colour')
                                            Interior Colour
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'varaints_id')
                                            Variant
										@elseif($pendingVehicleDetailApprovalRequest->field == 'ppmmyyy')
                                            Production Year
                                        @else
                                            {{ str_replace('_', ' ', ucwords( $pendingVehicleDetailApprovalRequest->field))}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->field == 'ex_colour')
                                            {{ $pendingVehicleDetailApprovalRequest->old_exterior ?? ''}}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'int_colour')
                                            {{ $pendingVehicleDetailApprovalRequest->old_interior ?? ''}}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'varaints_id')
                                            {{ $pendingVehicleDetailApprovalRequest->old_variant  ?? ''}}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'sales_person_id')
                                            {{ $pendingVehicleDetailApprovalRequest->old_sales_person ?? ''  }}
                                        @else
                                            {{ $pendingVehicleDetailApprovalRequest->old_value }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->field == 'ex_colour')
                                            {{ $pendingVehicleDetailApprovalRequest->new_exterior ?? ''}}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'int_colour')
                                            {{ $pendingVehicleDetailApprovalRequest->new_interior ?? '' }}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'varaints_id')
                                            {{ $pendingVehicleDetailApprovalRequest->new_variant ?? '' }}
                                        @elseif($pendingVehicleDetailApprovalRequest->field == 'sales_person_id')
                                            {{ $pendingVehicleDetailApprovalRequest->new_sales_person ?? ''  }}
                                        @else
                                            {{ $pendingVehicleDetailApprovalRequest->new_value ?? ''}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->status == 'approved')
                                            Approved
                                        @elseif($pendingVehicleDetailApprovalRequest->status == 'rejected')
                                            Rejected
                                        @else
                                        <button type="button" class="btn btn-success btn-sm "  data-bs-toggle="modal"
                                        data-bs-target="#approve-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}">
                                        Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#reject-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}">
                                        Reject
                                        </button>
										</td>
                                        @endif
                                        @endif
                                        @endif
                                        @if($hasPermissionReservationApprove)
                                        @if(in_array($pendingVehicleDetailApprovalRequest->field, ['so_number','so_date','sales_person_id','reservation_end_date','reservation_start_date']))
                                        <td>{{ Carbon::parse($pendingVehicleDetailApprovalRequest->created_at)->format('d M y, H:i:s') }}</td>
                                    <td>{{ $pendingVehicleDetailApprovalRequest->updatedBy->name }}</td>
                                    <td>
                                            {{ str_replace('_', ' ', ucwords( $pendingVehicleDetailApprovalRequest->field))}}
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->field == 'sales_person_id')
                                            {{ $pendingVehicleDetailApprovalRequest->old_sales_person ?? ''  }}
                                        @else
                                            {{ $pendingVehicleDetailApprovalRequest->old_value }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->field == 'sales_person_id')
                                            {{ $pendingVehicleDetailApprovalRequest->new_sales_person ?? ''  }}
                                        @else
                                            {{ $pendingVehicleDetailApprovalRequest->new_value ?? ''}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($pendingVehicleDetailApprovalRequest->status == 'approved')
                                            Approved
                                        @elseif($pendingVehicleDetailApprovalRequest->status == 'rejected')
                                            Rejected
                                        @else
                                        <button type="button" class="btn btn-success btn-sm "  data-bs-toggle="modal"
                                        data-bs-target="#approve-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}">
                                        Approve
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#reject-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}">
                                        Reject
                                        </button>
										</td>
                                        @endif
										@endif
										@endif
                                    @php
                                        if($pendingVehicleDetailApprovalRequest->field == 'ex_colour') {
                                            $new_value =  $pendingVehicleDetailApprovalRequest->new_exterior ?? '';
                                           }
                                          elseif($pendingVehicleDetailApprovalRequest->field == 'int_colour') {
                                              $new_value = $pendingVehicleDetailApprovalRequest->new_interior ?? '';
                                          }
                                          elseif($pendingVehicleDetailApprovalRequest->field == 'varaints_id') {
                                               $new_value =  $pendingVehicleDetailApprovalRequest->new_variant ?? '';
                                          }
                                        elseif($pendingVehicleDetailApprovalRequest->field == 'sales_person_id') {
                                               $old_value =  $pendingVehicleDetailApprovalRequest->new_sales_person ?? '';
                                          }
                                          else {
                                            $new_value = $pendingVehicleDetailApprovalRequest->new_value ?? '';
                                          }
                                    @endphp
                                    @php
                                        if($pendingVehicleDetailApprovalRequest->field == 'ex_colour') {
                                            $old_value =  $pendingVehicleDetailApprovalRequest->old_exterior ?? '';
                                           }
                                          elseif($pendingVehicleDetailApprovalRequest->field == 'int_colour') {
                                              $old_value = $pendingVehicleDetailApprovalRequest->old_interior ?? '';
                                          }
                                          elseif($pendingVehicleDetailApprovalRequest->field == 'varaints_id') {
                                               $old_value =  $pendingVehicleDetailApprovalRequest->old_variant ?? '';
                                          }
                                          elseif($pendingVehicleDetailApprovalRequest->field == 'sales_person_id') {
                                               $old_value =  $pendingVehicleDetailApprovalRequest->old_sales_person ?? '';
                                          }
                                          else {
                                            $old_value = $pendingVehicleDetailApprovalRequest->old_value ?? '';
                                          }
                                    @endphp
                                    <div class="modal fade" id="approve-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Approve</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Old Value</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">

                                                                        <input type="text" value="{{  $old_value ?? ''}}"
                                                                               class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Value</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">

                                                                        <input type="text" value="{{ $new_value ?? ''}}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary approve-button"
                                                            data-id="{{ $pendingVehicleDetailApprovalRequest->id }}" data-status="approved">Approve</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade" id="reject-vehicle-detail-{{$pendingVehicleDetailApprovalRequest->id}}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog ">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Selling Price Rejection</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13 text-center">Old Value</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{  $old_value ?? ''}}"
                                                                               class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                                <div class="row mt-2">
                                                                    <div class="col-lg-3 col-md-12 col-sm-12">
                                                                        <label class="form-label font-size-13">New Value</label>
                                                                    </div>
                                                                    <div class="col-lg-9 col-md-12 col-sm-12">
                                                                        <input type="text" value="{{ $new_value ?? ''}}"
                                                                               id="updated-price"  class="form-control" readonly >
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary reject-button" data-id="{{ $pendingVehicleDetailApprovalRequest->id }}"
                                                            data-status="rejected" >Reject</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div> -->
        @endif
        <hr>
        @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehousest-view');
            @endphp
            @if ($hasPermission)
        <h5>Warehouse Remarks</h5>
        <div class="table-responsive" >
                    <table id="dtBasicExample2" class="table table-striped table-editable table-edits table table-bordered">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Remarks</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Updated By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($remarks as $remark)
						<tr>
                            <td>{{$remark->remarks}}</td>
                            <td>{{ date('d-m-Y', strtotime($remark->date)) }}</td>
                            <td>{{$remark->time}}</td>
                            <td>
                            @php
                                        $created = DB::table('users')->where('id', $remark->created_by)->first();
                                        if($created) {
                                             $change_bysn = $created->name;
                                        }
                                    @endphp
                                    {{ $change_bysn ?? '' }}</td>
                                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <br>
                @php
                $hasPermission = Auth::user()->hasPermissionForSelectedRole('warehouse-edit');
            @endphp
            @if ($hasPermission)  
        <form id="approveForm" action="{{ route('warehouse.updatewarehouseremarks') }}" method="POST">
    @csrf
    <input type="hidden" id="buttonValue" name="buttonValue" value="">
    <div class="form-group">
        <input type="hidden" name="id" id="id" value="{{$vehicle->id ?? ''}}"/>
        <textarea id="remarks" name="remarks" class="form-control" rows="4" placeholder="Enter Remarks ..."></textarea>
    </div>
    <br>
    <a style="float: right;" class="btn btn-success" onclick="setButtonValue('approve'); document.getElementById('approveForm').submit();">
        <i class="fa fa-check" aria-hidden="true"></i> Send Remarks
    </a>
</form>
<br>
@endif
<br>
<hr>
@endif
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Changes Log Details</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive" >
                    <table id="dtBasicExample1" class="table table-striped table-editable table-edits table table-bordered">
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Updated Date</th>
                            <th>Updated By</th>
                            <th>Role</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mergedLogs as $vehiclesLog)
                            <tr data-id="1">
                                <td>
                                    @if($vehiclesLog->field == 'ex_colour')
                                        Exterior Colour
                                    @elseif($vehiclesLog->field == 'int_colour')
                                        Interior Colour
                                    @elseif($vehiclesLog->field == 'varaints_id')
                                        Variant
                                    @elseif($vehiclesLog->field == 'sales_person_id')
                                       Sales Person
                                    @else
                                        {{ str_replace('_', ' ', ucwords( $vehiclesLog->field))}}
                                    @endif
                                </td>
                                <td>
                                    @if($vehiclesLog->field == 'ex_colour')
                                        @php
                                            $newExterior = \App\Models\ColorCode::find($vehiclesLog->old_value);
                                        @endphp
                                        {{ $newExterior->name ?? ''}}
                                    @elseif($vehiclesLog->field == 'int_colour')
                                        @php
                                            $newInterior = \App\Models\ColorCode::find($vehiclesLog->old_value);
                                        @endphp
                                        {{ $newInterior->name ?? ''}}
                                    @elseif($vehiclesLog->field == 'varaints_id')
                                        @php
                                            $variant = \App\Models\Varaint::find($vehiclesLog->old_value);
                                        @endphp
                                        {{ $variant->name ?? '' }}
                                    @elseif($vehiclesLog->field == 'sales_person_id')
                                        @php
                                            $user = \App\Models\User::find($vehiclesLog->old_value);
                                        @endphp
                                        {{ $user->name ?? '' }}
                                    @else
                                        {{ $vehiclesLog->old_value ?? ''}}
                                    @endif
                                </td>
                                <td>
                                    @if($vehiclesLog->field == 'ex_colour')
                                        @php
                                            $newExterior = \App\Models\ColorCode::find($vehiclesLog->new_value);
                                        @endphp
                                        {{ $newExterior->name ?? ''}}
                                    @elseif($vehiclesLog->field == 'int_colour')
                                        @php
                                            $newInterior = \App\Models\ColorCode::find($vehiclesLog->new_value);
                                        @endphp
                                        {{ $newInterior->name ?? ''}}
                                    @elseif($vehiclesLog->field == 'varaints_id')
                                        @php
                                            $variant = \App\Models\Varaint::find($vehiclesLog->new_value);
                                        @endphp
                                        {{ $variant->name ?? '' }}
                                    @elseif($vehiclesLog->field == 'sales_person_id')
                                        @php
                                            $user = \App\Models\User::find($vehiclesLog->new_value);
                                        @endphp
                                        {{ $user->name ?? '' }}
                                    @else
                                        {{ $vehiclesLog->new_value ?? '' }}
                                    @endif
{{--                                    {{ $vehiclesLog->new_value }}--}}
                                </td>
                                <td>{{ date('d-m-Y', strtotime($vehiclesLog->date)) }} {{ $vehiclesLog->time }}</td>
                                <td>
                                    @php
                                        $change_by = DB::table('users')->where('id', $vehiclesLog->created_by)->first();
                                        if($change_by) {
                                             $change_bys = $change_by->name;
                                        }
                                    @endphp
                                    {{ $change_bys ?? '' }}
                                </td>
                                <td>{{ $vehiclesLog->roleName->name ?? ''}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.reject-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');

                updateValue(id, status)
            })
            $('.approve-button').click(function (e) {
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-status');
                updateValue(id, status)
            })
            function updateValue(id, status) {
                let url =  '{{ route('vehicle-detail.update') }}';
                if(status == 'rejected') {
                    var message = 'Reject';
                }else{
                    var message = 'Approve';
                }
                // var confirm = alertify.confirm('Are you sure you want to '+ message +' this item ?',function (e) {
                //     if (e) {
                $.ajax({
                    type: "POST",
                    url: url,
                    dataType: "json",
                    data: {
                        id:id,
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (data) {
                        window.location.reload();
                        alertify.success("Vehicle Detail"+status + " Successfully")
                    }
                });
                // }

                // }).set({title:"Update Vehicle Detail!"})
            }
            $('.select2').select2();
            var dataTable = $('#dtBasicExample1').DataTable({
                "order": [[3, "desc"]],
                pageLength: 10,
                initComplete: function() {
                    this.api().columns().every(function(d) {
                        var column = this;
                        var columnId = column.index();
                        var columnName = $(column.header()).attr('id');
                        if (columnName === "statuss") {
                            return;
                        }

                        var selectWrapper = $('<div class="select-wrapper"></div>');
                        var select = $('<select class="form-control my-1" multiple><option value="">All</option></select>')
                            .appendTo(selectWrapper)
                            .select2({
                                width: '100%',
                                dropdownCssClass: 'select2-blue'
                            });
                        select.on('change', function() {
                            var selectedValues = $(this).val();
                            column.search(selectedValues ? selectedValues.join('|') : '', true, false).draw();
                        });

                        selectWrapper.appendTo($(column.header()));
                        $(column.header()).addClass('nowrap-td');

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    });
                }
            });
            $('.dataTables_filter input').on('keyup', function() {
                dataTable.search(this.value).draw();
            });
        });
    </script>
    <script>
        // Set timer for error message
        setTimeout(function() {
            $('#error-message').fadeOut('slow');
        }, 2000);

        // Set timer for success message
        setTimeout(function() {
            $('#success-message').fadeOut('slow');
        }, 2000);
    </script>
    <script>
        function confirmCancel() {
            var confirmDialog = confirm("Are you sure you want to cancel this purchase order?");
            if (confirmDialog) {
                return true;
            } else {
                return false;
            }
        }
    </script>
    <script>
function setButtonValue(value) {
    document.getElementById('buttonValue').value = value;
}
</script>
@endsection
@push('scripts')
@endpush

