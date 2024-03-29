@extends('layouts.table')
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
  <div class="card-header">
    <h4 class="card-title">
     Variants Info
    </h4>
    @can('Calls-view')
    <ul class="nav nav-pills nav-fill">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#tab1">New / Missing Images Variants</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab2">Variants with Missing Videos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#tab3">All Variants With Pictures & Videos</a>
      </li>
    </ul>      
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>Variant Name</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Exterior Colour</th>
                  <th>Interior Colour</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
                @foreach ($rows as $variantsp)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ $variantsp->name }}</td> 
                    @php
                     $brand = DB::table('brands')->where('id', $variantsp->brands_id)->first();
                     $brand_name = $brand->brand_name;
                     @endphp    
                    <td>{{ $brand_name }}</td>
                    @php
                     $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
                     $model_line = $model->model_line;
                     @endphp 
                    <td>{{ $model_line }}</td>
                    <td>{{ $variantsp->int_colour }}</td> 
                    <td>{{ $variantsp->ext_colour }}</td>
                   <td><a data-placement="top" class="btn btn-sm btn-success" href="{{ route('variant_pictures.edit',$variantsp->id) }}"><i class="fa fa-camera" aria-hidden="true"></i></a>
                   </td>        
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  
      <div class="tab-pane fade show" id="tab2">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                  <th>S.No</th>
                  <th>Variant Name</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Exterior Colour</th>
                  <th>Interior Colour</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>              @foreach ($reels as $variantsp)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ $variantsp->name }}</td> 
                    @php
                     $brand = DB::table('brands')->where('id', $variantsp->brands_id)->first();
                     $brand_name = $brand->brand_name;
                     @endphp    
                    <td>{{ $brand_name }}</td>
                    @php
                     $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
                     $model_line = $model->model_line;
                     @endphp 
                    <td>{{ $model_line }}</td>
                    @php
                     $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
                     $model_line = $model->model_line;
                     @endphp 
                    <td>{{ $variantsp->int_colour }}</td> 
                    <td>{{ $variantsp->ext_colour }}</td>
                    <td><a data-placement="top" class="btn btn-sm btn-info" href="{{ route('variant_pictures.editreels',$variantsp->id) }}"><i class="fa fa-film" aria-hidden="true"></i></a></td>       
                  </tr>
                @endforeach
              
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      <div class="tab-pane fade show" id="tab3">
        <div class="card-body">
          <div class="table-responsive">
            <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
            <thead>
            <tr>
                  <th>S.No</th>
                  <th>Variant Name</th>
                  <th>Brand</th>
                  <th>Model</th>
                  <th>Exterior Colour</th>
                  <th>Interior Colour</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <div hidden>{{$i=0;}}</div>
              @foreach ($rowwithpictures as $variantsp)
                  <tr data-id="1">
                  <td>{{ ++$i }}</td>
                    <td>{{ $variantsp->name }}</td> 
                    @php
                     $brand = DB::table('brands')->where('id', $variantsp->brands_id)->first();
                     $brand_name = $brand->brand_name;
                     @endphp    
                    <td>{{ $brand_name }}</td>
                    @php
                     $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
                     $model_line = $model->model_line;
                     @endphp 
                    <td>{{ $model_line }}</td>
                    @php
                     $model = DB::table('master_model_lines')->where('id', $variantsp->master_model_lines_id)->first();
                     $model_line = $model->model_line;
                     @endphp 
                    <td>{{ $variantsp->int_colour }}</td> 
                    <td>{{ $variantsp->ext_colour }}</td>
                    <td>
                    @if(DB::table('variants_pictures')->where('available_colour_id', $variantsp->id)->exists())
    <a data-placement="top" class="btn btn-sm btn-success" href="{{ route('variant_pictures.edit', $variantsp->id) }}">
      <i class="fa fa-camera" aria-hidden="true"></i>
    </a>
  @endif
  @if(DB::table('variants_reels')->where('available_colour_id', $variantsp->id)->exists())
                    <a data-placement="top" class="btn btn-sm btn-info" href="{{ route('variant_pictures.editreels',$variantsp->id) }}"><i class="fa fa-film" aria-hidden="true"></i></a>
                    @endif
                  </td>         
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div> 
        </div>  
      </div> 
      @endcan
      </div>
    </div>
  </div>
  @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection