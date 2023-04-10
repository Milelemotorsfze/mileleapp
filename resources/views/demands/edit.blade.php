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
        <div id="monthly-demand-list-div">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                        <input type="text" value="{{ $demand->supplier }}" id="supplier-row" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                        <input type="text" value="{{ $demand->whole_saler }}" id="whole-saler-row" class="form-control" readonly/>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="mb-3">
                        <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                        <input type="text" value="{{ $demand->steering }}" id="steering-row" class="form-control" readonly/>
                    </div>
                </div>
            </div>
            @if($demandLists->count() > 0)
                <span>welcome</span>
                @foreach($demandLists as $demandList)
                    <div class="d-flex">
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <label for="basicpill-firstname-input" class="form-label">Model</label>
                                    <input type="text" value="{{ $demandList->model }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                    <input type="text" value="{{ $demandList->sfx }}" readonly class="form-control">
                                </div>
                                <div class="col-lg-4 col-md-4">
                                    <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                    <input type="text" value="{{ $demandList->variant_name }}" readonly class="form-control">
                                </div>
                            </div>
                        </div>
                        <p>&nbsp;&nbsp;&nbsp;</p>
                        <div class="col-lg-8 col-md-3">
                            <div class ="row">
                                @foreach($demandList->monthlyDemands as $key => $monthlyDemand)
                                    <div class="col-lg-1">
                                        <label for="basicpill-firstname-input" class="form-label">
                                            {{ $monthlyDemand->month }} {{ $monthlyDemand->year }}
                                        </label>
                                        <input type="number" value="{{ $monthlyDemand->quantity }}" id="" name="demand_quanties[]"
                                               class="form-control demand-list-quantity-{{ $key }}" readonly />
                                    </div>
                                @endforeach
                                    <div class="col-lg-1">
                                        <label for="basicpill-firstname-input" class="form-label">Total</label>
                                        <input type="text" class="form-control" readonly value="{{ $demandList->monthlyDemands()->sum('quantity') }}" >
                                    </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                @endforeach
            @endif
            <form id="form-demand" action="{{ route('demand-lists.store') }}" method="POST" enctype="multipart/form-data" class="form-demand">
                @csrf
                <div class="d-flex">
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">Model</label>
                                <select class="form-select" name="model" id="model">
                                    <option value="" disabled>Select The Model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model->model }}">{{ $model->model }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">SFX</label>
                                <select class="form-control" name="sfx" id="sfx"></select>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <label for="basicpill-firstname-input" class="form-label">Varients</label>
                                <select class="form-control" name="variant_name" id="variant-name"></select>
                            </div>
                        </div>
                    </div>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <div class="col-lg-8 col-md-3">
                        <div class ="row">
                            @foreach($months as $key => $month)
                                <div class="col-lg-1">
                                    <label for="basicpill-firstname-input" class="form-label">{{ $month }}
                                    </label>
                                    <input type="hidden" value="{{$month}}" name="month[]" id="month-year"/>
                                    <input type="number" value="0" id="count-{{$key}}" name="quantity[]" class="form-control quantity"/>
                                </div>
                            @endforeach
                            <div class="col-lg-1">
                                <label for="basicpill-firstname-input" class="form-label">Total</label>
                                <input type="text"  class="form-control" readonly  value="" id="total" name="total">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-3">
                </div>
                <br/>
                <div class="d-flex">
                    <div class="col-lg-8">
                    </div>
                    <p>&nbsp;&nbsp;&nbsp;</p>
                    <div class="col-lg-4 col-md-3">
                        <div class ="row">
                            @foreach($months as $key => $month)
                                <div class="col-lg-2 col-md-3">
                                  <span id="monthly-total-{{$key}}">
                                  </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <br/>
                <input type="hidden" value="{{ $demand->id }}" name="demand_id" id="demand-id">
                <div class="col-lg-12 col-md-12">
                    <button type="submit" class="btn btn-dark add-demand-list-details">Submit and Add New</button>
                </div>
            </form>
            <div class="col-lg-12 col-md-12">
                <button type="submit" class="btn btn-dark btnright">Finish</button>
            </div>
        </div>
    </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $('.demand-list-quantity-2').attr('readonly', false);
        $('.demand-list-quantity-3').attr('readonly', false);
        $('.demand-list-quantity-4').attr('readonly', false);

        $i=0;
        for($i=0;$i<=5;$i++) {
            $('#monthly-total-'+$i).val();
            $('#monthly-total-'+$i).append(10);
            // $("#destination").val(sum);
        }

        $('#model').select2();
        $('#model').on('change',function(e){
            $('#sfx').empty();
            let model = $(this).val();
            let url = '{{ route('demand.get-sfx') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    model: model
                },
                success:function (data) {
                    jQuery.each(data, function(key,value){
                        $('select[name="sfx"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        });
        $('#sfx').on('change',function(e){
            let sfx = $(this).val();
            let url = '{{ route('demand.get-variant') }}';
            $.ajax({
                type: "GET",
                url: url,
                dataType: "json",
                data: {
                    sfx: sfx
                },
                success:function (data) {
                    jQuery.each(data, function(key,value){
                        $('select[name="variant_name"]').append('<option value="'+ value +'">'+ value +'</option>');
                    });
                }
            });
        });
        for($i=0;$i<=5;$i++) {
            $('#count-'+$i).on('keyup',function() {
                Total();
            });
            $('#count-'+$i).on('click',function() {
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
        $('.add-demand-list-details').click(function() {
            let url = '{{ route('demand-lists.store') }}';
            $.ajax({
                url: url,
                data:$('form.form-demand').serializeArray(),
                success: function (data) {
                    location.reload();

                }
            });
        });

    </script>
@endpush
