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
    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id], 'enctype' => 'multipart/form-data']) !!}
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
            <select name="languages[]" id="languages" class="form-select" multiple>
                @foreach($language as $lang)
                    <option value="{{ $lang }}" {{ in_array($lang, old('languages', $userLanguages)) ? 'selected' : '' }}>{{ $lang }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="sales_rap" name="sales_rap" value="yes" {{ old('sales_rap', $user->sales_rap) == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="sales_rap">Sales REP</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="is_sales_rep" name="is_sales_rep" value="yes" {{ old('is_sales_rep', $user->is_sales_rep) == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="is_sales_rep">Is Sales Rep. ?</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="is_dubai_sales_rep" name="is_dubai_sales_rep" {{ old('is_dubai_sales_rep', $user->is_dubai_sales_rep ?? '') == 'Yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="is_dubai_sales_rep">Dubai Sales Rep</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="can_send_wo_email" name="can_send_wo_email" value="yes" {{ old('can_send_wo_email', $user->can_send_wo_email) == 'yes' ? 'checked' : '' }}>
                <label class="form-check-label" for="can_send_wo_email">Can Send Work Order Email ?</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="manual_lead_assign" name="manual_lead_assign" value="1" {{ old('manual_lead_assign', $user->manual_lead_assign) == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="manual_lead_assign">Can Use In Manual Lead Assign ?</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-check mt-4">
                <input class="form-check-input" type="checkbox" id="pfi_access" name="pfi_access" value="1" {{ old('pfi_access', $user->pfi_access) == '1' ? 'checked' : '' }}>
                <label class="form-check-label" for="pfi_access">Can Use As Sales Person In PFI/Quotation ?</label>
            </div>
        </div>
        <p></p>
        <div class="col-md-4">
            <label for="user_image" class="form-label">User Image</label>
            @if($user->empProfile && $user->empProfile->image_path)
                <div class="mb-3">
                    <img src="{{ asset($user->empProfile->image_path) }}" alt="User Image" class="img-thumbnail" width="150">
                </div>
            @endif
            <input type="file" name="user_image" id="user_image" class="form-control" accept=".jpg, .jpeg, .png">
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
        $('#languages').select2();
        $('#department').select2();
        $('#designation').select2();
    });
</script>
@endsection