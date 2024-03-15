@extends('layouts.main')
@section('content')
    <style>
        .widthinput
        {
            height:32px!important;
        }

    </style>
    @can('demand-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Update Demand</h4>
                @can('demand-list')
                    @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole('demand-list');
                    @endphp
                    @if ($hasPermission)
                        <a  class="btn btn-sm btn-info float-end" href="{{ route('demands.index') }}" >
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                    @endif
                @endcan
            </div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert"> x </button>
                        {{ Session::get('message') }}
                    </div>
                @endif
                <div id="monthly-demand-list-div">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Vendor</label>
                                <input type="text" value="{{ $demand->supplier->supplier }}" id="supplier-row" class="form-control widthinput" readonly/>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Dealer</label>
                                <input type="text" value="{{ $demand->whole_saler }}" id="whole-saler-row" class="form-control widthinput" readonly/>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label text-muted">Steering</label>
                                <input type="text" value="{{ $demand->steering }}" id="steering-row" class="form-control widthinput" readonly/>
                            </div>
                        </div>
                    </div>

        {{--                 hide in small, medium(view)--}}
                        <div class="d-none d-lg-block d-xl-block d-xxl-block">
                            <div class="d-flex">
                                <div class="col-lg-7 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6 col-sm-12">
                                            <label for="basicpill-firstname-input" class="form-label">Model</label>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                        </div>
                                        <div class="col-lg-2 col-md-6 col-sm-12">
                                            <label for="basicpill-firstname-input" class="form-label">Model Year</label>
                                        </div>
                                        <div class="col-lg-5 col-md-5 col-sm-12">
                                            <label for="basicpill-firstname-input" class="form-label">Variant</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-12 col-sm-12">
                                    <div class="row" style="margin-left: 10px">
                                        @foreach($months as $key => $month)
                                            <div class="col-lg-1 col-md-2 col-sm-2">
                                                <label> {{ $month }} </label>
                                            </div>
                                        @endforeach
                                        <div class="col-lg-1 col-md-2 col-sm-2" >
                                            <label>Total </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @if($demandLists->count() > 0)
                        @foreach($demandLists as $value => $demandList)
                        <div class="d-flex mt-2">
                            <div class="col-lg-7 col-md-9 col-sm-9 col-9">
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                        <input type="text" value="{{ $demandList->masterModel->model ?? ''}}"  readonly class="form-control widthinput" >
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">SFX</label>
                                        <input type="text" value="{{ $demandList->masterModel->sfx ?? ''}}" readonly class="form-control widthinput">
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">Model Year</label>
                                        <input type="text" value="{{ $demandList->masterModel->model_year ?? ''}}" readonly class="form-control widthinput">
                                    </div>
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <label class="form-label d-lg-none d-xl-none d-xxl-none">Variant</label>
                                        <input type="text" value="{{ $demandList->masterModel->variant->name ?? '' }}" readonly class="form-control widthinput">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-3 col-sm-3 col-3">
                                <div class="row" style="margin-left: 10px">
                                    @foreach($months as $key => $month)
                                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                                            <label class="d-lg-none d-xl-none d-xxl-none"> {{ $month }} </label>
                                                <input type="number" value="{{ $demandList->fiveMonthDemands[$key]->quantity  ?? 0}}" id="demand-quantity-{{$value}}-{{$key}}"
                                                       min="0" class="form-control widthinput demand-list-quantity-{{ $key }}" readonly
                                                       oninput="validity.valid||(value='');" step="1" />
                                        </div>
                                    @endforeach

                                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12" >
                                            <label class="d-lg-none d-xl-none d-xxl-none">Total </label>
                                            <input type="number" class="form-control widthinput mb-3" readonly value="{{ $demandList->fiveMonthDemands()->sum('quantity') }}" >
                                        </div>
                                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                                            <button type="button" class="btn btn-danger widthinput demand-list-delete sm-mt-3"  data-id="{{ $demandList->id }}"
                                                    data-url="{{ route('demand-lists.destroy', $demandList->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                    <br/>
                    <form id="form-demand" action="{{ route('demand-lists.store') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" value="{{ $demand->id }}" name="demand_id" id="demand-id">
                        <input type="hidden" name="module" value="Demand">
                        <div class="d-flex">
                            <div class="col-lg-7 col-md-9 col-sm-9 col-9">
                                <div class="row">
                                    <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12">
                                        <label  class="form-label d-lg-none d-xl-none d-xxl-none">Model</label>
                                         <select class="form-select text-dark widthinput" name="model" id="model" multiple autofocus="autofocus">
                                             <option></option>
                                            @foreach($models as $model)
                                                <option value="{{ $model->model }}">{{ $model->model }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-6 col-sm-12 col-xs-12">
                                        <label  class="form-label d-lg-none d-xl-none d-xxl-none mt-1" >SFX</label>
                                        <select class="form-select text-dark widthinput" name="sfx" id="sfx" multiple >
                                            <option></option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
                                        <label  class="form-label d-lg-none d-xl-none d-xxl-none mt-1">Model Year</label>
                                        <select class="form-select text-dark" name="model_year" id="model-year" multiple>
                                        </select>
                                    </div>
                                    <div class="col-lg-5 col-md-12 col-sm-12 col-xs-12">
                                        <label  class="form-label d-lg-none d-xl-none d-xxl-none mt-1">Variant</label>
                                        <input type="text" readonly class="form-control  widthinput text-dark" id="variant" >

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-3 col-sm-12 col-3">
                                <div class="row" style="margin-left: 10px">
                                    @foreach($months as $key => $month)
                                        <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                                            <label  class="form-label d-lg-none d-xl-none d-xxl-none">{{$month}}</label>
                                            <input type="hidden" value="{{$month}}" name="month[]" id="month-year"/>
                                            <input type="number" value="0" id="count-{{$key}}" name="quantity[]" step="1" oninput="validity.valid||(value='');"
                                                   class="form-control widthinput quantity" min="0"/>
                                        </div>
                                    @endforeach
                                    <div class="col-lg-1 col-md-12 col-sm-12 col-xs-12">
                                        <label  class="form-label d-lg-none d-xl-none d-xxl-none">Total</label>
                                        <input type="text" class="form-control widthinput mb-3" readonly value="" id="total"  name="total">
                                    </div>
        {{--                                hide in samll view--}}
                                    <div class="col-lg-2 col-sm-12 col-xs-12 d-none d-sm-block">
                                        <button type="submit" class="btn widthinput btn-info"> Add  </button>
                                    </div>
                                </div>
                            </div>
                        </div>
        {{--                // show only in xs or small view--}}
                        <div class="col-12 text-center d-block d-sm-none">
                            <button type="submit" class="btn btn-info "> Add </button>
                        </div>
                        <br/>
                        <div class="d-none d-lg-block d-xl-block d-xxl-block">
                            <div class="d-flex">
                                <div class="col-lg-7 col-md-7 col-sm-7">
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-12" style="margin-left: 25px">
                                    <div class="row">
                                        @foreach($months as $key => $month)
                                            <div class="col-lg-1 col-md-1 col-sm-12">
                                                  <span id="monthly-total-{{$key}}">
                                                      {{ $totalYearlyQuantities[$key] }}
                                                  </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br/>
                    </form>
                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary" id="update-monthly-demands">Update Quantity</button>
                    </div>
                </div>
            </div>
        @endif
    @endcan
@endsection
@push('scripts')
    <script type="text/javascript">
        $('#count-0').attr('readonly', true);
        $('#count-1').attr('readonly', true);
        $('.demand-list-quantity-2').attr('readonly', false);
        $('.demand-list-quantity-3').attr('readonly', false);
        $('.demand-list-quantity-4').attr('readonly', false);
        $('#model-error').addClass('marigin-top','10px');

        $('#model').select2({
            placeholder : 'Select Model',
            allowClear: true,
            maximumSelectionLength: 1
        });
        $('#sfx').select2({
            placeholder : 'Select SFX',
            allowClear: true,
            maximumSelectionLength: 1
        });

        $('#model-year').select2({
            placeholder : 'Select Model Year',
            allowClear: true,
            maximumSelectionLength: 1
        });

        $("#form-demand").validate({
            ignore: [],
            rules: {
                model: {
                    required: true,
                },
                sfx: {
                    required: true,
                },
                variant: {
                    required: true,
                },
            },
            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    element = $("#select2-" + element.attr("id") + "-container").parent();
                    error.insertAfter(element).addClass('mt-2 text-danger');
                }else {
                    error.insertAfter(element).addClass('text-danger');
                }
            }
        });
        $('#variant-name').on('change',function() {
            $('#variant-name-error').remove();
        })
        $('#model').on('change',function(){
            $('#model-error').remove();
            let model = $(this).val();
            let demandId = '{{ $demand->id }}';
            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model,
                    demand_id: demandId,
                    module: 'Demand'
                },
                success:function (data) {
                    $('select[name="sfx"]').empty();
                    $('#model-year').empty();
                    $('#variant').val('');
                    $('#sfx').html('<option value=""> Select SFX </option>');
                    $('#model-year').html('<option value=""> Select Model Year </option>');
                    jQuery.each(data, function(key,value){
                        $('select[name="sfx"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        });

        $('#sfx').on('change',function(){
            $('#sfx-error').remove();
            let sfx = $(this).val();
            let model = $('#model').val();
            let url = '{{ route('demand.get-model-year') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx,
                    model:model,
                },
                success:function (data) {
                    $('#model-year').empty();
                    $('#model-year').html('<option value=""> Select Model Year </option>');
                    $('#variant').val('');
                    jQuery.each(data, function(key,value){
                        console.log(value);
                        $('#model-year').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });

        });

        $('#model-year').on('change',function(){
            $('#model-year-error').remove();
            let modelYear = $(this).val();
            let sfx = $('#sfx').val();
            let model = $('#model').val();
            let url = '{{ route('demand.get-loi-description') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx,
                    model:model,
                    model_year:modelYear[0],
                    module:'DEMAND'
                },
                success:function (data) {
                    console.log(data);
                    $('#variant').val(data.variant);
                }
            });
        });

        for($j=0;$j<=5;$j++) {
            $('#count-'+$j).on('keyup',function() {
                Total();
            });
            $('#count-'+$j).on('click',function() {
                Total();
            });
        }
        function Total(){
            var value0 = $('#count-0').val();
            var value1 = $('#count-1').val();
            var value2=  $('#count-2').val();
            var value3 = $('#count-3').val();
            var value4 = $('#count-4').val();
            var total = +value0 + +value1 + +value2 + +value3 + +value4;
            $('#total').val(total);
        }
        $('#update-monthly-demands').click(function() {
            var count = '{{ $demandLists->count() }}';
            var demand_id = $('#demand-id').val();
            var quantities = [];
            for($i=0;$i<count;$i++) {
                var quantity0 = $('#demand-quantity-'+$i+'-0').val();
                var quantity1 = $('#demand-quantity-'+$i+'-1').val();
                var quantity2 = $('#demand-quantity-'+$i+'-2').val();
                var quantity3 = $('#demand-quantity-'+$i+'-3').val();
                var quantity4 = $('#demand-quantity-'+$i+'-4').val();
                quantities.push(quantity0);
                quantities.push(quantity1);
                quantities.push(quantity2);
                quantities.push(quantity3);
                quantities.push(quantity4);
            }
            let url = '{{ route('monthly-demands.store') }}';
            let redirect_url = '{{ route('demands.create') }}';
            $.ajax({
                url: url,
                type: "POST",
                data:{
                    demand_id: demand_id,
                    quantities:quantities,
                    _token: '{{ csrf_token() }}'
                },
                success: function (data) {
                    location.reload();
                    alertify.success('Quantity Updated Successfully.');
                    // window.location.href = redirect_url;
                }
            });
        });
        $('.demand-list-delete').on('click',function(){
            let id = $(this).attr('data-id');
            let url =  $(this).attr('data-url');
            var confirm = alertify.confirm('Are you sure you want to Delete this item ?',function (e) {
                if (e) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        dataType: "json",
                        data: {
                            _method: 'DELETE',
                            id: 'id',
                            _token: '{{ csrf_token() }}'
                        },
                        success:function (data) {
                            location.reload();
                            alertify.success('Item Deleted successfully.');
                        }
                    });
                }
            }).set({title:"Delete Item"})
        });
    </script>
@endpush
