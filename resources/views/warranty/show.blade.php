@extends('layouts.table')
@section('content')
    <div class="card-header">
        <h4 class="card-title"> Warranty Details</h4>
        <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <button type="button" class="btn-close p-0 close text-end" data-dismiss="alert"></button>
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
        @if (Session::has('success'))
            <div class="alert alert-success" id="success-alert">
                <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                {{ Session::get('success') }}
            </div>
        @endif
    </div>

    @can('warranty-view')
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Policy Name</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->PolicyName->name}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Supplier</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->supplier->supplier}}</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Vehicle Category 1</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>
                         @if($premium->vehicle_category1 == 'non_electric')
                            Non Electric /
                        @elseif($premium->vehicle_category1 == 'electric')
                            Electric /
                        @endif
                         @if($premium->vehicle_category2 == 'normal_and_premium')
                             Normal And Premium
                         @elseif($premium->vehicle_category2 == 'lux_sport_exotic')
                             Lux Sport Exotic
                         @endif
                    </span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label">Eligibility Years</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{ $premium->eligibility_year }} Years</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Eligibility Mileage</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->eligibility_milage}} KM</span>
                </div>
            </div>
            @if($premium->is_open_milage == false)
                <div class="row">
                    <div class="col-lg-2 col-md-3 col-sm-12">
                        <label for="choices-single-default" class="form-label">  Extended Warranty Mileage</label>
                    </div>
                    <div class="col-lg-6 col-md-9 col-sm-12">
                        <span>{{ $premium->extended_warranty_milage }} KM</span>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Extended Warranty Period</label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->extended_warranty_period}} Months</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-2 col-md-3 col-sm-12">
                    <label for="choices-single-default" class="form-label"> Claim Limit </label>
                </div>
                <div class="col-lg-6 col-md-9 col-sm-12">
                    <span>{{$premium->claim_limit_in_aed}} AED</span>
                </div>
            </div>
        </div>
        <div class="card" style="margin-bottom: 0" >
            <div class="card-header">
                <h2 class="card-title">Warranty Brands</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="warranty-brands-table" class="table table-striped table-editable table-edits table table-condensed" >
                        <thead class="bg-soft-secondary">
                        <tr>
                            <th>S.NO</th>
                            <th>Brand</th>
                            <th>Purchase Price</th>
                            <th>Selling Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <div hidden>{{$i=0;}}
                        </div>
                        @foreach ($warrantBrands as $key => $warrantBrand)
                            <tr>
                                <td> {{ ++$i }}</td>
                                <td>{{ $warrantBrand->brand->brand_name }}</td>
                                <td>{{ $warrantBrand->price }} AED</td>
                                <td>{{ $warrantBrand->selling_price }} AED</td>
                                <td>
                                    @can('warranty-view')
                                        <a href="{{ route('warranty-price-histories.index',['id' => $warrantBrand->id]) }}" class="btn btn-info btn-sm ">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    @endcan
                                    @can('warranty-edit')
                                        <button type="button" class="btn btn-primary btn-sm " data-bs-toggle="modal" data-bs-target="#edit-price-{{$warrantBrand->id}}">
                                            <i class="fa fa-edit"></i></button>
                                    @endcan
                                    @can('warranty-delete')
                                        <button type="button" class="btn btn-danger btn-sm delete-button" data-id="{{ $warrantBrand->id }}"
                                                data-url="{{ route('warranty-brands.destroy', $warrantBrand->id) }}">
                                            <i class="fa fa-trash"></i></button>
                                    @endcan
                                </td>
                                <div class="modal fade" id="edit-price-{{$warrantBrand->id}}"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog ">
                                        <form id="form-update" action="{{ route('warranty-brands.update', $warrantBrand->id) }}" method="POST" >
                                            @method('PUT')
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Update Prices</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-3">
                                                    <div class="col-lg-12">
                                                        <div class="row">
                                                            <div class="row mt-2">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Purchase Price</label>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="input-group">
                                                                        <input type="number" name="price" class="form-control" id="price"  placeholder="Enter Purchase Price"
                                                                           oninput="validity.valid||(value='');" min="0">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                        </div>
                                                                    </div>
                                                                    @error('price')
                                                                    <div role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="row mt-2">
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <label class="form-label font-size-13 text-muted">Selling Price</label>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                                    <div class="input-group">
                                                                        <input type="number" name="selling_price" class="form-control" placeholder="Enter Selling Price"
                                                                               oninput="validity.valid||(value='');" min="0">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text widthinput" id="basic-addon2">AED</span>
                                                                        </div>
                                                                    </div>

                                                                    @error('selling_price')
                                                                    <div role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary ">Submit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endcan
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#exampleModalLabel  form").validate({
                ignore: [],
                rules: {
                    price: {
                        required: true,
                    }
                },
            });
            $('.delete-button').on('click',function(){
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
        })

    </script>
@endpush

