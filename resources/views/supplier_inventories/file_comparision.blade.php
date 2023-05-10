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
                            <option value="TTC">TTC</option>
                            <option value="AMS">AMS</option>
                            <option value="CPS">CPS</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Dealers</label>
                        <select class="form-control" data-trigger name="whole_sales" id="wholesaler">
                            <option value="{{ \App\Models\SupplierInventory::DEALER_TRANS_CARS }}">Trans Cars</option>
                            <option value="{{\App\Models\SupplierInventory::DEALER_MILELE_MOTORS}}">Milele Motors</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">First File</label>
                        <select class="form-control" data-trigger name="first_file" id="supplier">
                            <option value="" disabled>Select First File</option>
                            @foreach($supplierInventoryDates as $key => $supplierInventoryDate)
                                <option value="{{ $supplierInventoryDate }}">File {{ $key + 1 }} ({{$supplierInventoryDate}} )</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Second File</label>
                        <select class="form-control" name="second_file" data-trigger name="supplier" id="supplier">
                            <option value="" disabled>Select Second File</option>
                            @foreach($supplierInventoryDates as $key => $supplierInventoryDate)
                                <option value="{{ $supplierInventoryDate }}">File {{ $key + 1 }} ({{$supplierInventoryDate}}) </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-dark mt-4 compare-button"> Compare </button>
                <a href="{{ route('supplier-inventories.file-comparision-report') }}">
                    <button type="button" class="btn btn-dark mt-4 compare-button"> Refresh </button>
                </a>
            </div>
            </br>

        </form>

{{--        @if(count($updatedRows) > 0 ||  count($newlyAddedRows) > 0 || count($deletedRows) > 0)--}}
            <div class="container report-div">
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
{{--        @endif--}}
    </div>
@endsection
@push('scripts')
    <script>
        $('#compare-button').click(function () {
            $('.report-div').attr('hidden',false);
        })
    </script>
@endpush


