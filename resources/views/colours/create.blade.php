@extends('layouts.main')

<style>
    .error, .custom-error {
        color: red;
    }

</style>

@section('content')
<div class="card-header">
    <h4 class="card-title">Add New Color</h4>
    <a class="btn btn-sm btn-info float-end" href="{{ url()->previous() }}"><i class="fa fa-arrow-left"></i> Back</a>
</div>
<div class="card-body">
    <form id="form-create" action="{{ route('colourcode.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label for="name"><strong>Color Name</strong><span class="error"> * </span></label>
                <input type="text" name="name" class="form-control" placeholder="Color Name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="belong_to"><strong>Belong To</strong><span class="error"> * </span></label>
                <select class="form-control" name="belong_to" required>
                    <option value="int">Interior</option>
                    <option value="ex">Exterior</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="parent"><strong>Parent Color</strong><span class="error"> * </span></label>
                <select class="form-control" name="parent" required>
                    <option value="Black">Black</option>
                    <option value="White">White</option>
                    <option value="Grey">Grey</option>
                    <option value="Beige">Beige</option>
                    <option value="Blue">Blue</option>
                    <option value="Red">Red</option>
                    <option value="Brown">Brown</option>
                    <option value="Orange">Orange</option>
                    <option value="Green">Green</option>
                    <option value="Yellow">Yellow</option>
                    <option value="Others">Others</option>
                </select>
            </div>
        </div>

        <div class="row col-12 mt-3">
            <label for="color_codes"><strong>Color Codes</strong><span class="error"> * </span></label>
            <div id="color-code-container" class="col-xxl-3 col-md-4 col-sm-6 col-12">
                <div class="input-group mb-2">
                    <input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
                    <button type="button" class="btn btn-success add-color-code">+</button>
                </div>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.add-color-code').addEventListener('click', function() {
            let container = document.getElementById('color-code-container');
            let newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `<input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
                                  <button type="button" class="btn btn-danger remove-color-code">-</button>`;
            container.appendChild(newInput);
        });

        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-color-code')) {
                event.target.parentElement.remove();
            }
        });
    });
</script>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#form-create').validate({
            rules: {
                name: "required",
                belong_to: "required",
                parent: "required",
                'color_codes[]': {
                    required: true,
                    minlength: 1  
                }
            },
            messages: {
                name: "Please enter the color name",
                belong_to: "Please select where the color belongs",
                parent: "Please select a parent color",
                'color_codes[]': "Please enter at least one color code"
            },
            errorPlacement: function(error, element) {
                error.addClass('custom-error');
                error.insertAfter(element);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid'); 
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endpush

@endsection
