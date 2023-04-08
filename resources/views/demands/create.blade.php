@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add New Demands</h4>
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
            <div class="row">
                <div class="row demand-div">
                    <div class="col-lg-4 col-md-6">
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
                    <div class="col-lg-4 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                            <select class="form-control" data-trigger name="whole_saler" id="whole-saler">
                                <option value="Trans Cars">Trans Cars</option>
                                <option value="Milele Motors">Milele Motors</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                            <select class="form-control" data-trigger name="steering" id="steering">
                                <option value="LHD">LHD</option>
                                <option value='RHD'>RHD</option>
                            </select>
                        </div>
                    </div>
                    </br>
                <div class="col-lg-12 col-md-12">
                    <button type="submit" class="btn btn-dark btncenter" id="add-demand">Submit</button>
                </div>
                </div>
            </div>
            <br/>
            <div class="d-none" id="monthly-demand-list-div">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Select The Supplier</label>
                            <input type="text" value="" id="supplier-row" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Dealers</label>
                            <input type="text" value="" id="whole-saler-row" class="form-control" readonly/>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="mb-3">
                            <label for="choices-single-default" class="form-label font-size-13 text-muted">Steering</label>
                            <input type="text" value="" id="steering-row" class="form-control" readonly/>
                        </div>
                    </div>
                </div>
                <div class=""></div>
                <form action="{{ route('demand-lists.store') }}" method="POST" enctype="multipart/form-data" >
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
                                    <input type="text"  class="form-control" readonly value="" id="total" name="total">
                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                     <input type="hidden" value="" id="demand-id">
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

    $('#model').select2();

    $('#add-demand').click(function() {
        let supplier = $('#supplier').val();
        let whole_saler = $('#whole-saler').val();
        let steering = $('#steering').val();
        let url = '{{route('demands.store')}}';
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: {
                supplier: supplier,
                whole_saler: whole_saler,
                steering: steering,
                _token: '{{ csrf_token() }}'
            },
            success: function (data) {
                $('.demand-div').hide();
                $('#demand-id').val(data.id);
                $('#supplier-row').val(data.supplier);
                $('#whole-saler-row').val(data.whole_saler);
                $('#steering-row').val(data.steering);
                $('#monthly-demand-list-div').removeClass('d-none');
            }
        });
    });
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
