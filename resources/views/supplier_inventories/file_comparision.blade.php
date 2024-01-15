@extends('layouts.main')
@section('content')
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #1c1b1b;
            text-align: left;
            padding: 8px;
        }
        .heading {
            text-align: center;
            font-weight: bold;
        }
        .count-span {
            text-align: center;
            font-weight: bold;
        }
        .new-row {
            background-color: #bdd5f3;
        }
        .updated-row {
            background-color: #d8d4ea;
        }
        .deleted-row {
            background-color: #e5beb2;
        }
    </style>
    @can('supplier-inventory-report-view')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('supplier-inventory-report-view');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Inventory File Comparision</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>

            </div>
            <div class="card-body">
                @if(Session::has('message'))
                    <div class="alert alert-danger">
                        {{Session::get('message')}}
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert"></button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br>
                        <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form id="form-compare" action="{{ route('supplier-inventories.file-comparision-report') }}">
                    <div class="row">
                        <div class="col-lg-3 col-md-3">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Supplier</label>
                                <select class="form-control" autofocus name="supplier_id" id="supplier">
                                    <option value="" disabled>Select The Supplier</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->supplier }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted"> Dealers</label>
                                <select class="form-control" data-trigger name="whole_sales" id="wholesaler">
                                    <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}"
                                        {{ ( request()->whole_sales == \App\Models\SupplierInventory::DEALER_TRANS_CARS ) ? 'selected' : '' }}>
                                        Trans Cars
                                    </option>
                                    <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}"
                                        {{ ( request()->whole_sales == \App\Models\SupplierInventory::DEALER_MILELE_MOTORS ) ? 'selected' : '' }}>
                                        Milele Motors
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">First File</label>
                                <select class="form-control text-dark first" required data-trigger name="first_file" id="first-file">
                                    <option value="" disabled>First File</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Second File</label>
                                <select class="form-control text-dark" required  name="second_file" id="second-file" >
                                    <option value="" disabled>Second File</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 justify-content-center">
                        <button type="submit" class="btn btn-primary mt-4 text-center compare-button"> Compare </button>
                        <a href="{{ route('supplier-inventories.file-comparision') }}">
                            <button type="button" class="btn btn-info text-center mt-4 "> Refresh </button>
                        </a>
                    </div>

                </form>
                <div class="container report-div" >
                       <h2 style="text-align: center">Supplier Inventory Reports</h2>
                       <table class="new-row">
                           <tr>
                               <td colspan="5" class="heading">Newly Added Rows - {{ count($newlyAddedRows) }}</td>
                           </tr>
                           <tr>
                               <th>Model</th>
                               <th>SFX</th>
                               <th>Chasis</th>
                               <th>Engine Number</th>
                               <th>Color Code</th>
                           </tr>
                           @foreach($newlyAddedRows as $newlyAddedRow)
                               <tr>
                                   <td>{{ $newlyAddedRow['model'] }}</td>
                                   <td>{{ $newlyAddedRow['sfx'] }}</td>
                                   <td>{{ $newlyAddedRow['chasis'] }}</td>
                                   <td>{{ $newlyAddedRow['engine_number'] }}</td>
                                   <td>{{ $newlyAddedRow['color_code'] }}</td>
                               </tr>
                           @endforeach
                           @if(!$newlyAddedRows)
                               <tr>
                                   <td colspan="5" style="text-align: center" >No data Added</td>
                               </tr>
                           @endif
                       </table>
                       <br><br>
                       <table class="updated-row">
                           <tr>
                               <td colspan="5" class="heading">Updated Added Rows - {{ count($updatedRows) }}</td>
                           </tr>
                           <tr>
                               <th>Model</th>
                               <th>SFX</th>
                               <th>Chasis</th>
                               <th>Engine Number</th>
                               <th>Color Code</th>
                           </tr>
                           @foreach($updatedRows as $updatedRow)
                               <tr>
                                   <td>{{ $updatedRow['model'] }}</td>
                                   <td>{{ $updatedRow['sfx'] }}</td>
                                   <td>{{ $updatedRow['chasis'] }}</td>
                                   <td>{{ $updatedRow['engine_number'] }}</td>
                                   <td>{{ $updatedRow['color_code'] }}</td>
                               </tr>
                           @endforeach
                           @if(!$updatedRows)
                               <tr>
                                   <td colspan="5" style="text-align: center" >No data Updated</td>
                               </tr>
                           @endif
                       </table>
                       <br><br>
                       <table class="deleted-row">
                           <tr>
                               <td colspan="5" class="heading">Deleted Rows - {{ count($deletedRows) }}</td>
                           </tr>
                           <tr>
                               <th>Model</th>
                               <th>SFX</th>
                               <th>Chasis</th>
                               <th>Engine Number </th>
                               <th>Color Code</th>
                           </tr>

                           @foreach($deletedRows as $deletedRow)
                               <tr>
                                   <td>{{ $deletedRow['model'] }}</td>
                                   <td>{{ $deletedRow['sfx'] }}</td>
                                   <td>{{ $deletedRow['chasis']  }}</td>
                                   <td>{{ $deletedRow['engine_number'] }}</td>
                                   <td>{{ $deletedRow['color_code'] }}</td>
                               </tr>
                           @endforeach
                           @if(!$deletedRows)
                               <tr>
                                   <td colspan="5" style="text-align: center" >No data Deleted</td>
                               </tr>
                           @endif
                       </table>
                    </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script>
        $('#supplier').select2();
        $('#wholesaler').select2();
        getDates();
        jQuery.validator.addMethod("greaterStart", function (value, element, params) {
            var startDate = $('#first-file').val();
            var endDate = $('#second-file').val();

           if( startDate >= endDate) {
               return false;
           }else{
               return true;
           }
        },'Must be greater than first file date.');

        $("#form-compare").validate({
            ignore: [],
            rules: {
                first_file: {
                    required: true,
                },
                second_file: {
                    required: true,
                    greaterStart: true
                },
            },
            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + element.attr("id") + "-container").parent();
                    error.insertAfter(element).addClass('mt-2 mb-0 text-danger');
                }else {
                    error.insertAfter(element).addClass('text-danger ');
                }
            }
        });
        $('#supplier').change(function () {
            $('select[name="first_file"]').empty();
            $('select[name="second_file"]').empty();
             getDates();
        })
        $('#wholesaler').change(function () {
            $('select[name="first_file"]').empty();
            $('select[name="second_file"]').empty();
             getDates();
        })

        function getDates() {
            let supplier = $('#supplier').val();
            let wholesaler = $('#wholesaler').val();

            let url = '{{ route('supplier-inventories.get-dates') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    supplier_id: supplier,
                    wholesaler: wholesaler
                },
                success:function (data) {
                    let first = '{{ request()->first_file }}';
                    let second = '{{ request()->second_file }}';
                    if(first) {
                        $('#first-file').append($('<option>', {
                            value: first,
                            text: first
                        }));
                        // $("#first-file option[value='" + first + "']").attr("selected","selected");
                    }else{
                        $('select[name="first_file"]').html('<option value="" > Select First File </option>');
                    }
                    if(second) {
                        $('#second-file').append($('<option>', {
                            value: second,
                            text: second
                        }));
                    }else{
                        $('select[name="second_file"]').html('<option value="" > Select Second File </option>');

                    }
                    jQuery.each(data, function(key,value){
                        // getSelectedDates();
                        var key = key + 1;
                        $('select[name="first_file"]').append('<option value="'+ value +'" > File '+ key +'(' + value + ')'+'</option>');
                        $('select[name="second_file"]').append('<option value="'+ value +'"> File '+ key +'(' + value + ')'+'</option>');
                    });
                }
            });
            }
    </script>
@endpush



