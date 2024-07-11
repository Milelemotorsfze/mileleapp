@extends('layouts.main')
@section('content')
<div class="container">
    </br>
    <h4>Edit User</h4>
    </br>
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
    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            {!! Form::text('name', old('name', $user->name), ['placeholder' => 'Name', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            {!! Form::email('email', old('email', $user->email), ['placeholder' => 'Email', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Phone Number</label>
            {!! Form::tel('phone', old('phone', $user->empProfile->company_number ?? ''), ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="department" class="form-label">Department</label>
            <select name="department" id="department" class="form-select">
                <option value="" selected disabled>Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department', $userDepartmentId) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="designation" class="form-label">Designation</label>
            <select name="designation" id="designation" class="form-select">
                <option value="" selected disabled>Select Designation</option>
                @foreach($jobposition as $jobpositions)
                    <option value="{{ $jobpositions->id }}" {{ old('designation', $userDesignationId) == $jobpositions->id ? 'selected' : '' }}>{{ $jobpositions->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="lauguages" class="form-label">Languages</label>
            <select name="lauguages[]" id="lauguages" class="form-select" multiple>
                @foreach($language as $lang)
                    <option value="{{ $lang }}" {{ in_array($lang, old('lauguages', $userLanguages)) ? 'selected' : '' }}>{{ $lang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="sales_rap" name="sales_rap" value="yes" {{ old('sales_rap', $user->sales_rap) == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="sales_rap">Sales RAP</label>
            </div>
        </div>
        <div class="col-md-4">
            <label for="roles" class="form-label">Roles</label>
            <select name="roles[]" id="roles" class="form-select" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRole)) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-lg-12 col-md-12">
            <input type="submit" name="submit" value="Submit" class="btn btn-success btn-sm btncenter" />
        </div>
    </div>
    {!! Form::close() !!}
</div>
<script>
    $(document).ready(function () {
        $('#roles').select2();
        $('#lauguages').select2();
        $('#department').select2();
        $('#designation').select2();
    });
</script>
@endsection