@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New User</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('users.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
            {!! Form::open(array('route' => 'users.store', 'method' => 'POST')) !!}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="name" class="form-label">Full Name</label>
                    {!! Form::text('name', null, array('placeholder' => 'Full Name', 'class' => 'form-control')) !!}
                </div>
                <div class="col-md-4">
                    <label for="email" class="form-label">Email</label>
                    {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
                </div>
                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone Number</label>
                    {!! Form::tel('phone', null, array('placeholder' => 'Phone Number', 'class' => 'form-control')) !!}
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="designation" class="form-label">Designation</label>
                    {!! Form::text('designation', null, array('placeholder' => 'Designation', 'class' => 'form-control')) !!}
                </div>
                <div class="col-md-4">
                    <label for="department" class="form-label">Department</label>
                    <select name="department" id="department" class="form-select">
                    <option value="" selected disabled>Select Department</option>
                        <option value="Admin">Admin</option>
                        <option value="HR">HR</option>
                        <option value="Logistics">Logistics</option>
                        <option value="Finance">Finance</option>
                        <option value="Demand & Planning">Demand & Planning</option>
                        <option value="IT">IT</option>
                        <option value="Sales">Sales</option>
                        <option value="QC">QC</option>
                        <option value="Procurement">Procurement</option>
                        <option value="Warehouse">Warehouse</option>
                </select>
                </div>
                <div class="col-md-4">
        <label for="lauguages" class="form-label">Lauguages</label>
        <select name="lauguages[]" id="lauguages" class="form-select" multiple>
            @foreach($language as $language)
                <option value="{{ $language }}">{{ $language }}</option>
            @endforeach
        </select>
    </div>
            </div>
            <div class="row mb-3">
            <div class="col-md-4">
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="sales_rap" name="sales_rap" value="yes">
                        <label class="form-check-label" for="sales_rap">Sales RAP</label>
                    </div>
                </div>
    <div class="col-md-8">
        <label for="roles" class="form-label">Roles</label>
        <select name="roles[]" id="roles" class="form-select" multiple>
            @foreach($roles as $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>
    </div>
</div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#roles').select2();
        $('#lauguages').select2();
    });
</script>
@endsection