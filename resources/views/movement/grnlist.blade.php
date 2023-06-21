@extends('layouts.main')
<style>
        .drag-drop-area {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .drag-drop-area.drag-drop-over {
            background-color: #f2f2f2;
        }
        .drag-drop-text {
            font-size: 16px;
            color: #888;
            margin-bottom: 10px;
        }
        #file-name-display {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
@section('content')
@if (Auth::user()->selectedRole === '5' || Auth::user()->selectedRole === '6')
<div class="card-header">
        <h4 class="card-title">Adding Netsuit GRN List</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a> 
    </div>
    <div class="card-body">
    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('grnlist.grnsimplefile') }}" text-align: right><i class="fa fa-download" aria-hidden="true"></i> Download Sample File</a>
<br>
@if(session('error'))
    <div id="error-message" class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
@if(session('success'))
    <div id="success-message" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
        <form action="{{ route('grnlist.grnfilepost') }}" method="POST" id="purchasing-order" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="form-group">
                    <label for="file-input" class="form-label">Upload Excel File:</label>
                    <input type="file" name="file" class="form-control-file">
                </div>
            </div>
        </div>
        <br>
        <div id="file-name-display"></div>
        <br>
        <div class="col-lg-12 col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success" />
        </div>
    </form>
    </div>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
    @endsection
@push('scripts')
@endpush