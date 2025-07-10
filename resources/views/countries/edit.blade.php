@extends('layouts.table')

@section('content')

<div class="card-header">
    <h4 class="card-title">Edit Country</h4>
    <a class="btn btn-sm btn-info float-end" href="{{ route('countries.index') }}">
        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back
    </a>
</div>

<div class="card-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('countries.update', $country->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label class="form-label">Country Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name" value="{{ old('name', $country->name) }}" class="form-control" required>
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label class="form-label">Nationality</label>
            <input type="text" name="nationality" value="{{ old('nationality', $country->nationality) }}" class="form-control">
        </div>

        <div class="mb-3 col-lg-4 col-md-4 col-sm-6 col-12">
            <label class="form-label">ISO 3166 Code</label>
            <input type="text" name="iso_3166_code" value="{{ old('iso_3166_code', $country->iso_3166_code) }}" class="form-control">
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_african_country" value="0">
            <input type="checkbox" class="form-check-input" id="is_african_country" name="is_african_country" value="1"
                {{ old('is_african_country', $country->is_african_country) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_african_country">Is African Country?</label>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('countries.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const form = nameInput.closest('form');
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorDiv = document.createElement('div');
        errorDiv.classList.add('text-danger', 'mt-1');
        nameInput.parentNode.appendChild(errorDiv);

        let dirty = false;

        function validateName(value) {
            const trimmed = value.trim();
            const hasDoubleHyphen = /--/.test(value);
            const hasDoubleSpace = /\s{2,}/.test(value);
            const hasDoubleBrackets = /\(\)|\)\)|\(\(/.test(value);
            const invalidChars = /[^A-Za-z0-9\s\-()]/.test(value);
            const hyphenCount = (value.match(/-/g) || []).length;

            if (value !== trimmed) return "No leading or trailing spaces allowed.";
            if (hasDoubleSpace) return "Avoid double spaces.";
            if (hasDoubleHyphen) return "Only one hyphen allowed.";
            if (hyphenCount > 1) return "Only one hyphen allowed.";
            if (hasDoubleBrackets) return "Avoid empty or double brackets.";
            if (invalidChars) return "Only letters, numbers, space, one hyphen and brackets allowed.";

            return "";
        }

        function updateValidation() {
            const value = nameInput.value;
            const error = validateName(value);
            if (dirty && error) {
                errorDiv.textContent = error;
                submitBtn.disabled = true;
            } else {
                errorDiv.textContent = "";
                submitBtn.disabled = false;
            }
        }

        nameInput.addEventListener('input', function() {
            dirty = true;
            updateValidation();
        });

        form.addEventListener('submit', function(e) {
            updateValidation();
            if (submitBtn.disabled) {
                e.preventDefault();
                nameInput.focus();
            }
        });

        submitBtn.disabled = false; // Prevents initial disable
    });
</script>
@endpush