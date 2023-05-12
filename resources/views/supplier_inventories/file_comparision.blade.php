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
    <div class="card-header">
        <h4 class="card-title">File Comparision</h4>
    </div>
    <div class="card-body">
        @if(Session::has('message'))
            <div class="alert alert-danger">
                {{Session::get('message')}}
            </div>
        @endif
        <form action="{{ route('supplier-inventories.file-comparision-report') }}">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                        <select class="form-control" data-trigger name="supplier" id="supplier">
                            <option value="" disabled>Select The Supplier</option>
                            <option value="TTC" {{ ( request()->supplier == 'TTC') ? 'selected' : '' }}>TTC</option>
                            <option value="AMS" {{ ( request()->supplier == 'AMS') ? 'selected' : '' }}>AMS</option>
                            <option value="CPS" {{ ( request()->supplier == 'CPS') ? 'selected' : '' }}>CPS</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Dealers</label>
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
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">First File</label>
                        <select class="form-control" data-trigger name="first_file" id="first-file">
                            <option value="" >Select First File</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Second File</label>
                        <select class="form-control" name="second_file" id="second-file" >
                            <option value="" >Select Second File</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-dark mt-4 compare-button"> Compare </button>
                <a href="{{ route('supplier-inventories.file-comparision-report') }}">
                    <button type="button" class="btn btn-dark mt-4 "> Refresh </button>
                </a>
            </div>
            </br>
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
@endsection
@push('scripts')
    <script>
        getDates();
        $('#supplier').select2();
        $('#wholesaler').select2();
        getDates();

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
                    supplier: supplier,
                    wholesaler: wholesaler
                },
                success:function (data) {
                    jQuery.each(data, function(key,value){
                        let first = '{{ request()->first_file }}';
                        let second = '{{ request()->second_file }}';
                        var key = key + 1;
                        $("#first-file option[value='" + first + "']").prop("selected",true);
                        $("#second-file option[value='" + second + "']").prop("selected",true);
                        $('select[name="first_file"]').append('<option value="'+ value +'"> File '+ key +'(' + value + ')'+'</option>');
                        $('select[name="second_file"]').append('<option value="'+ value +'"> File '+ key +'(' + value + ')'+'</option>');
                    });
                }
            });
            }
    </script>
@endpush



