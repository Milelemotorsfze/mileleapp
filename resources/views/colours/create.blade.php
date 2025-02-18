@extends('layouts.main')

<style>
    .error,
    #name-error,
    .is-invalid,
    .custom-error {
        color: red;
    }

    .hidden-error {
        display: none !important;
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
                <label for="name"><strong>Color Name</strong><span class="error"> *</span></label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Color Name" value="{{ old('name') }}" required>
                <div id="nameError" class="error"></div>
            </div>
            <div class="col-md-4">
                <label for="belong_to"><strong>Belong To</strong><span class="error"> *</span></label>
                <select class="form-control" name="belong_to" required>
                    <option value="int">Interior</option>
                    <option value="ex">Exterior</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="parent"><strong>Parent Color</strong><span class="error"> *</span></label>
                <select class="form-control" name="parent" required>
                    <option value="Black">Black</option>
                    <option value="White">White</option>
                    <option value="Grey">Grey</option>
                </select>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-4">
                <label><strong>Color Code Required?</strong></label>
                <div class="d-flex flex-column">
                    <span>
                        <input type="radio" id="color_code_yes" name="color_code_required" value="yes" >
                        <label for="color_code_yes">Yes</label>
                    </span>
                    <span>
                        <input type="radio" id="color_code_no" name="color_code_required" value="no" checked>
                        <label for="color_code_no">No</label>
                    </span>
                </div>
            </div>
        </div>

        <div id="color-code-section" class="row col-12 mt-3">
            <label for="color_codes"><strong>Color Codes</strong><span class="error"> *</span></label>
            <div id="color-code-container" class="col-xxl-3 col-md-4 col-sm-6 col-12">
                <div class="input-group mb-2">
                    <input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
                    <button type="button" class="btn btn-success add-color-code">+</button>
                </div>
            </div>
            <div id="codeErrors" class="error mt-1" style="visibility: hidden;"></div>
        </div>

        <div id="codeErrors" class="error mt-1" style="visibility: hidden;"></div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        const nameInput = $('#name');
        nameInput.on('keyup', function() {
            clearTimeout(this.delay);
            this.delay = setTimeout(function() {
                checkNameAvailability(nameInput.val().trim());
            }, 500);
        });

        function checkNameAvailability(name) {
            if (name.length === 0) {
                $('#nameError').text('');
                return;
            }
            $.ajax({
                url: '{{ route("color-codes.check-name") }}',
                type: 'POST',
                data: {
                    name: name,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.exists) {
                        $('#nameError').text('This color name is already in use.');
                        $('#form-create').find(':submit').prop('disabled', true);
                    } else {
                        $('#nameError').text('');
                        $('#form-create').find(':submit').prop('disabled', false);
                    }
                }
            });
        }

        $('input[type=radio][name=color_code_required]').change(function() {
            toggleColorCodes();
        });

        function toggleColorCodes() {
            if ($('#color_code_no').is(':checked')) {
                $('#color-code-section').hide();
                $('input[name="color_codes[]"]').each(function() {
                    $(this).val('');
                    $(this).prop('disabled', true);
                    $(this).removeClass('error');
                });
                $('#codeErrors').css('visibility', 'hidden');
            } else {
                $('#color-code-section').show();
                $('input[name="color_codes[]"]').each(function() {
                    $(this).prop('disabled', false);
                    $(this).rules("add", {
                        required: true,
                        // uniqueColorCode: true,
                        messages: {
                            required: "Please enter a color code.",
                            uniqueColorCode: "Color Code Duplication or Empty."
                        }
                    });
                });
            }
        }

        const container = document.getElementById('color-code-container');
        $('.add-color-code').on('click', function() {
            addColorCodeInput();
        });

        $(container).on('click', '.remove-color-code', function() {
            $(this).closest('.input-group').remove();
            updateColorCodeErrors();
        });

        function addColorCodeInput() {
            let newInputDiv = document.createElement('div');
            newInputDiv.className = 'input-group mb-2 col-xxl-3 col-md-4 col-sm-6 col-12';
            newInputDiv.innerHTML = `<input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
                <button type="button" class="btn btn-danger remove-color-code"> &#xd7; </button>`;
            container.appendChild(newInputDiv);
            $(newInputDiv).find('input').rules("add", {
                required: true,
                uniqueColorCode: true
            });
            updateColorCodeErrors();
        }

        function updateColorCodeErrors() {
            let codes = $('input[name="color_codes[]"]').map(function() {
                return $(this).val().trim();
            }).get();
            let duplicates = codes.filter((item, index) => codes.indexOf(item) != index && item !== '');
            let message = duplicates.length > 0 ? "Duplicate color codes found: " + [...new Set(duplicates)].join(', ') + ". Please enter each code only once." : '';
            $('#codeErrors').text(message).css('visibility', message ? 'visible' : 'hidden');
        }

        function findDuplicates() {
            let allCodes = $('input[name="color_codes[]"]').map(function() {
                return $(this).val().trim();
            }).get();
            let counts = {};
            allCodes.forEach(function(code) {
                counts[code] = (counts[code] || 0) + 1;
            });
            let duplicates = [];
            for (let code in counts) {
                if (counts[code] > 1) duplicates.push(code);
            }
            return duplicates;
        }

        $('#form-create').validate({
            ignore: ":hidden:not(.do-not-ignore), .ignore",
            rules: {
                name: "required",
                belong_to: "required",
                parent: "required",
                'color_codes[]': {
                    required: function() {
                        return $('#color_code_yes').is(':checked');
                    },
                    uniqueColorCode: true
                }
            },
            messages: {
                name: "Please enter the color name",
                belong_to: "Please select where the color belongs",
                parent: "Please select a parent color",
                'color_codes[]': "Color code is required"
            },
            errorPlacement: function(error, element) {
                if (error.text() === "Color Code Duplication or Empty.") {
                    error.addClass('hidden-error');
                }
                if (element.attr("name") == "color_codes[]") {
                    error.insertAfter($('#color-code-container'));
                } else {
                    error.insertAfter(element);
                }
            },
            highlight: function(element) {
                $(element).closest('.input-group').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.input-group').removeClass('is-invalid');
                updateColorCodeErrors();
            },
            invalidHandler: function(event, validator) {
                updateColorCodeErrors();
            },
            submitHandler: function(form) {
                form.submit();
            }
        });

        toggleColorCodes();

        jQuery.validator.addMethod("uniqueColorCode", function(value, element, params) {
            if (value.trim() === '') return true;
            var allInputs = $('input[name="' + element.name + '"]');
            var allValues = allInputs.map(function() {
                return $(this).val().trim();
            }).get();
            var isUnique = new Set(allValues).size === allValues.length;
            return this.optional(element) || isUnique;
        }, );

    });
</script>

@endpush
@endsection