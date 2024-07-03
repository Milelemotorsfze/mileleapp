@extends('layouts.main')

@section('content')
<div class="container">
</br>
    <h4>Edit User</h4>
</br>
    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="name" class="form-label">Name</label>
            {!! Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-4">
            <label for="email" class="form-label">Email</label>
            {!! Form::email('email', null, ['placeholder' => 'Email', 'class' => 'form-control']) !!}
        </div>
        <div class="col-md-4">
            <label for="phone" class="form-label">Phone Number</label>
            {!! Form::tel('phone', $user->empProfile->company_number ?? '', ['placeholder' => 'Phone Number', 'class' => 'form-control']) !!}
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="department" class="form-label">Department</label>
            <select name="department" id="department" class="form-select">
                <option value="" selected disabled>Select Department</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $userDepartmentId == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="designation" class="form-label">Designation</label>
            <select name="designation" id="designation" class="form-select">
                <option value="" selected disabled>Select Designation</option>
                @foreach($jobposition as $jobpositions)
                    <option value="{{ $jobpositions->id }}" {{ $userDesignationId == $jobpositions->id ? 'selected' : '' }}>{{ $jobpositions->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="languages" class="form-label">Languages</label>
            <select name="languages[]" id="languages" class="form-select" multiple>
                @foreach($language as $lang)
                    <option value="{{ $lang }}" {{ in_array($lang, $userLanguages) ? 'selected' : '' }}>{{ $lang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="sales_rap" name="sales_rap" value="yes" {{ $user->sales_rap == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="sales_rap">Sales RAP</label>
            </div>
        </div>
        <div class="col-md-4">
            <label for="roles" class="form-label">Roles</label>
            <select name="roles[]" id="roles" class="form-select" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ in_array($role->id, $userRole) ? 'selected' : '' }}>{{ $role->name }}</option>
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
        $('#languages').select2();
        $('#department').select2();
        $('#designation').select2();

    });
</script>
@endsection