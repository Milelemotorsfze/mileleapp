@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Update Demand</h4>
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
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Supplier</label>
                        <input type="text" value="{{ $demand->supplier->supplier }}" id="supplier-row" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                        <input type="text" value="{{ $demand->whole_saler }}" id="whole-saler-row" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                        <input type="text" value="{{ $demand->steering }}" id="steering-row" class="form-control" readonly/>
                    </div>
                </div>
            </div>
                <div class="d-flex">
                    <div class="col-lg-7 col-md-7">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">Model</label>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">SFX</label>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">Varients</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-9">
                        <div class="row" style="margin-left: 10px">
                            @foreach($months as $key => $month)
                                <div class="col-lg-1" >
                                    <label >{{ $month }} </label>
                                </div>
                            @endforeach
                                <div class="col-lg-1">
                                    <label>Total </label>
                                </div>
                        </div>
                    </div>
                </div>
            @if($demandLists->count() > 0)
                @foreach($demandLists as $value => $demandList)
                    <div class="d-flex">
                        <div class="col-lg-7 col-md-7">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <input type="text" value="{{ $demandList->model }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <input type="text" value="{{ $demandList->sfx }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <input type="text" value="{{ $demandList->variant_name }}" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <p>&nbsp;&nbsp;&nbsp;</p>
                        <div class="col-lg-9 col-md-9">
                            <div class ="row">
                                @foreach($demandList->fiveMonthDemands as $key => $monthlyDemand)
                                    <div class="col-lg-1">
                                        <input type="text" value="{{ $monthlyDemand->quantity }}" id="demand-quantity-{{$value}}-{{$key}}"
                                               class="form-control demand-list-quantity-{{ $key }}" readonly />
                                    </div>
                                @endforeach
                                    <div class="col-lg-1">
                                        <input type="number" class="form-control" readonly value="{{ $demandList->fiveMonthDemands()->sum('quantity') }}" >
                                    </div>
                                    <div class="col-lg-1">
                                        <button type="button" class="btn btn-danger demand-list-delete"  data-id="{{ $demandList->id }}"
                                                data-url="{{ route('demand-lists.destroy', $demandList->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                @endforeach
            @endif
            <form id="form-demand" action="{{ route('demand-lists.store') }}" method="POST" enctype="multipart/form-data" >
                @csrf
                <div class="d-flex">
                    <div class="col-lg-7 col-md-7">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                 <select class="form-select text-dark " name="model" id="model">
                                    <option value="" disabled>Select Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->model }}">{{ $model->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <select class="form-select text-dark" name="sfx" id="sfx">
                                    <option value="" >Select SFX</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <select class="form-select variant text-dark" name="variant_name" id="variant-name" >
                                    <option value="">Select Variant</option>
                                </select>

                            </div>
                        </div>
                    </div>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <div class="col-lg-9 col-md-9">
                        <div class ="row">
                            @foreach($months as $key => $month)
                                <div class="col-lg-1">
                                    <input type="hidden" value="{{$month}}" name="month[]" id="month-year"/>
                                    <input type="number" value="0" id="count-{{$key}}" name="quantity[]" step="1" oninput="validity.valid||(value='');"
                                           class="form-control quantity" min="0"/>
                                </div>
                            @endforeach
                            <div class="col-lg-1">
                                <input type="text" class="form-control" readonly value="" id="total"  name="total">
                            </div>
                        </div>
                    </div>
                </div>

                <br/>
                <div class="d-flex">
                    <div class="col-lg-7 col-md-7">
                    </div>
                    <div class="col-lg-9" style="margin-left: 25px">
                        <div class ="row">
                            @foreach($months as $key => $month)
                                <div class="col-lg-1 col-md-1">
                              <span id="monthly-total-{{$key}}">
                                  {{ $totalYearlyQuantities[$key] }}
                              </span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <br/>
                <input type="hidden" value="{{ $demand->id }}" name="demand_id" id="demand-id">
                <input type="hidden" name="module" value="Demand">
                <div class="col-lg-12 col-md-12">
                    <button type="submit" class="btn btn-dark add-demand-list-details">Submit and Add New</button>
                </div>
            </form>
            <div class="col-lg-12 col-md-12">
                <button type="button" class="btn btn-dark btnright" id="update-monthly-demands">Finish</button>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $('#count-0').attr('readonly', true);
        $('#count-1').attr('readonly', true);
        $('.demand-list-quantity-2').attr('readonly', false);
        $('.demand-list-quantity-3').attr('readonly', false);
        $('.demand-list-quantity-4').attr('readonly', false);

        $('#model').select2();
        // $('#sfx').select2();
        // $('.variant').select2();

        $("#form-demand").validate({
            ignore: [],

            rules: {
                model: {
                    required: true,
                },
                sfx: {
                    required: true,
                },
                variant_name: {
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
                    $('select[name="variant_name"]').empty();
                    $('#sfx').html('<option value=""> Select SFX </option>');
                    $('#variant-name').html('<option value=""> Select Variant </option>');
                    jQuery.each(data, function(key,value){
                        $('select[name="sfx"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        });
        $('#sfx').on('change',function(){
            let sfx = $(this).val();
            let model = $('#model').val();
            let url = '{{ route('demand.get-variant') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx,
                    model:model,
                    module: 'Demand'
                },
                success:function (data) {
                    var data = data.variants
                    $('select[name="variant_name"]').empty();
                    $('#variant-name').html('<option value=""> Select Variant </option>');
                    jQuery.each(data, function(key,value){
                        $('select[name="variant_name"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
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
                    window.location.href = redirect_url;
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
