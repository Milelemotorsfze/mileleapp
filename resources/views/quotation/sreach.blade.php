@extends('layouts.datatable')
@section('content')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Sreach Vehicles</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="dtBasicExample" class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                <select name="brand[]" id="brand" class="form-control" multiple>
                                                    @foreach ($brand as $brand)
                                                   <option value="{{ $brand->id }}">{{ $brand->brand_name }}</option>
                                                    @endforeach
                                                  </select></th>
                                                <th>
                                                <select name="model[]" id="model" class="form-control" multiple>
                                                    @foreach ($variants as $variants)
                                                   <option value="{{ $variants->master_model_lines_id }}">{{ $variants->name }}</option>
                                                    @endforeach
                                                  </select>
                                                </th>
                                                <th></th>
                                                <th><select name="sub_model[]" id="sub_model" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="variant[]" id="variant" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="my[]" id="my" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="steering[]" id="steering" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="seats[]" id="seats" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="fuel[]" id="fuel" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="ex_colour[]" id="ex_colour" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="int_colour[]" id="int_colour" class="form-control" multiple>
                                                  </select></th>
                                                  <th><select name="upholestry[]" id="upholestry" class="form-control" multiple>
                                                  </select></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr data-id="1">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
												<td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                               </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
@push('scripts')
<script type="text/javascript">
$('#brand').select2({
    multiple: true,
    placeholder: "Select Brand"
});
$('#model').select2({
    multiple: true,
    placeholder: "Select Model"
});
$('#sub_model').select2({
    multiple: true,
    placeholder: "Select Sub Model"
});
$('#variant').select2({
    multiple: true,
    placeholder: "Select Variant"
});
$('#my').select2({
    multiple: true,
    placeholder: "Select MY"
});
$('#steering').select2({
    multiple: true,
    placeholder: "Select Steering"
});
$('#seats').select2({
    multiple: true,
    placeholder: "Select Seats"
});
$('#fuel').select2({
    multiple: true,
    placeholder: "Select Fuel"
});
$('#ex_colour').select2({
    multiple: true,
    placeholder: "Select Ex Colour"
});
$('#int_colour').select2({
    multiple: true,
    placeholder: "Select Int Colour"
});
$('#upholestry').select2({
    multiple: true,
    placeholder: "Select Upholestry"
});
</script>
@endpush