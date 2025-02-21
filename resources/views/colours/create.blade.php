@extends('layouts.main')

<style>
.error {
    color: red;
}
</style>

@section('content')
@can('create-color-code')
    @php
        $hasPermission = Auth::user()->hasPermissionForSelectedRole('create-color-code');
    @endphp
    @if ($hasPermission)
        <div class="card-header">
            <h4 class="card-title">Add New Master Colours</h4>
            <a  class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
        </div>
        <div class="card-body">
        <div class="row">
                <p><span style="float:right;" class="error">* Required Field</span></p>
                </div>
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
            @if (Session::has('success'))
                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                    {{ Session::get('success') }}
                </div>
            @endif
            <form id="form-create" action="{{ route('colourcode.store') }}" method="POST">
                @csrf
                    <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Code</label>
                                <input type="text" value="{{ old('code') }}" name="code" class="form-control " placeholder="Colour Code" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error">* </span>
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" value="{{ old('name') }}" name="name" class="form-control " placeholder="Colour Name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error">* </span>
                                <label for="choices-single-default" class="form-label">Belong To</label>
                                <select class="form-control" autofocus name="belong_to" id="model">
                                    <option value="int" {{ old('belong_to') == 'int' ? 'selected' : '' }}>Interior</option>
                                    <option value="ex" {{ old('belong_to') == 'ex' ? 'selected' : '' }}>Exterior</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error">* </span>
                                <label for="choices-single-default" class="form-label">Parent Colour</label>
                                <select class="form-control" autofocus name="parent" id="model">
                                    <option value="Black" {{ old('parent') == 'Black' ? 'selected' : '' }}>Black</option>
                                    <option value="White" {{ old('parent') == 'White' ? 'selected' : '' }}>White</option>
                                    <option value="Grey" {{ old('parent') == 'Grey' ? 'selected' : '' }}>Grey</option>
                                    <option value="Beige" {{ old('parent') == 'Beige' ? 'selected' : '' }}>Beige</option>
                                    <option value="Blue" {{ old('parent') == 'Blue' ? 'selected' : '' }}>Blue</option>
                                    <option value="Red" {{ old('parent') == 'Red' ? 'selected' : '' }}>Red</option>
                                    <option value="Brown" {{ old('parent') == 'Brown' ? 'selected' : '' }}>Brown</option>
                                    <option value="Orange" {{ old('parent') == 'Orange' ? 'selected' : '' }}>Orange</option>
                                    <option value="Green" {{ old('parent') == 'Green' ? 'selected' : '' }}>Green</option>
                                    <option value="Yellow" {{ old('parent') == 'Yellow' ? 'selected' : '' }}>Yellow</option>
                                    <option value="Others" {{ old('parent') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                            <span class="error">* </span>
                                <label for="choices-single-default" class="form-label">Status</label>
                                <select class="form-control" autofocus name="status" id="model">
                                    <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="De-Active" {{ old('status') == 'De-Active' ? 'selected' : '' }}>De-Active</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
            </form>
        </div>
        </div>
    @endif
    @endcan
@endsection
@push('scripts')
@endpush