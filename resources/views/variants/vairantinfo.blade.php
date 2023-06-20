@extends('layouts.table')
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
    <div class="card-header">
        <h4 class="card-title">
            Variants With Colours Info
        </h4>
        @can('Calls-modified')
      <a class="btn btn-sm btn-success float-end" href="{{ route('calls.createnewvarinats') }}" text-align: right>
        <i class="fa fa-plus" aria-hidden="true"></i> Add New Variants With Colours
      </a>
      <div class="clearfix"></div>
<br>
    @endcan
    </div>
    <div class="card-body">
        <div class="table-responsive" >
            <table id="dtBasicSupplierInventory" class="table table-striped table-editable table-edits table">
                <thead class="bg-soft-secondary">
                <tr>
                    <th>ID</th>
                    <th>Variants Name</th>
                    <th>Interior Colour</th>
                    <th>Exterior Colour</th>
                </tr>
                </thead>
                <tbody>
                <div hidden>{{$i=0;}}
                </div>
                @foreach ($Variants as $Variants)
                <tr data-id="1">
                 <td>{{ $Variants->id}}</td>
                 @php
                     $varaints = DB::table('varaints')->where('id', $Variants->varaint_id)->first();
                     $name = $varaints->name;
                     @endphp   
                        <td>{{ $name }}</td>
                        <td>{{ $Variants->int_colour }}</td>
                        <td>{{ $Variants->ext_colour }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
