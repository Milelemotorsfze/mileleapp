@extends('layouts.table')

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

    .select2-container--default .select2-selection--multiple {
        padding: 4px 0px 9px 4px !important;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 1px solid #cfd4d9 !important;
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
                <div class="row">
                    <label for="parent_colour_id"><strong>Select Parent Color</strong><span class="error"> *</span></label>

                    <div class="input-group d-flex flex-nowrap parent_color_name">
                        <select class="form-control select2" id="parent_colour_id" name="parent_colour_id" style="width: 100%" multiple>
                            <option disabled selected>Select Parent Colour</option>
                            @foreach($parentColours as $parentColour)
                            <option value="{{ $parentColour->id }}">{{ $parentColour->name }}</option>
                            @endforeach

                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-success" type="button" onclick="$('#addParentColorModal').modal('show');">
                                +
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-4">
                <label><strong>Color Code Required?</strong></label>
                <div class="d-flex flex-column">
                    <span>
                        <input type="radio" id="color_code_yes" name="color_code_required" value="yes">
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
            <div id="color-code-container" class="col-xxl-4 col-md-4 col-sm-6 col-12">
                <div class="color-code-div-container d-flex align-items-center col-6">
                    <div class="input-group mb-2">
                        <input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
                        <button type="button" class="btn btn-success add-color-code">+</button>
                    </div>
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

@include('parentcolours.modal_add')

@push('scripts')

<script>
    $(document).ready(function() {
        $('#parent_colour_id').select2({
            placeholder: "Select Parent Colour",
            maximumSelectionLength: 1,
        });

        $('#parent_colour_id').on('change', function() {
            if ($(this).val().length > 0) {
                $('.select2-selection').removeClass('is-invalid');
                $('.select2-selection__rendered').css('color', 'black');
                $('#parent-error').hide();
            }
        });

        function fetchParentColors() {
            $.ajax({
                url: "{{ route('parentColours.fetch') }}",
                method: 'GET',
                success: function(data) {
                    $('#parent_colour_id').empty();
                    data.forEach(function(color) {
                        $('#parent_colour_id').append(new Option(color.name, color.id));
                    });
                },
                error: function(error) {
                    console.error('Failed to fetch parent colors', error);
                }
            });
        }

        fetchParentColors();

        $('#parent_colour_id').on('input', function() {
            $('#parent_colour_id-error').hide();
        });

        function validateParentColorName() {
            let parentName = $('#parent_color_name').val().trim();

            if (parentName.length === 0) {
                $('#parentColorError').text('Parent color name is required.').show();
                return false;
            } else if (!validateColorName(parentName)) {
                $('#parentColorError').text('Invalid format! Only alphabets and single "/" allowed with single spacing.').show();
                console.log("wrong parent color name")
                return false;
            }

            $('#parentColorError').text('').hide();
            $('#parent_colour_id-error').hide();
            return true;
        }

        $('#parent_color_name').on('blur', function() {
            let formattedParentName = formatColorName($(this).val());
            $(this).val(formattedParentName);
            validateParentColorName();

        });

        $('#form-add-parent-color').on('submit', function(e) {

            if (!validateParentColorName()) {
                console.log("False output");
                e.preventDefault();
                return false;
            }

            var formData = $(this).serialize();

            $.post("{{ route('parentColours.store') }}", formData)
                .done(function(response) {
                    if (response.status === 'success') {
                        console.log("In success of submit parent color name")
                        $('#parent_colour_id').val(null).trigger('change');
                        $('#parent_colour_id').append(new Option(response.name, response.id, false, true)).trigger('change');

                        $('#addParentColorModal').modal('hide');
                        $('#form-add-parent-color')[0].reset();
                        $('#parentColorError').hide();
                    }
                })
                .fail(function(xhr) {
                    var errorMessage = xhr.responseJSON?.message || 'Failed to add parent color. Please try again.';
                    $('#parentColorError').text(errorMessage).show();
                });
            return false;
        });

        $('#addParentColorModal').on('shown.bs.modal', function() {
            $('#parentColorError').hide();
            $('#form-add-parent-color')[0].reset();
        });
    });
</script>

<script>
    function validateColorName(name) {
        const regex = /^(?! )[A-Za-z]+(?:\s[A-Za-z]+)*(?:\s\/\s[A-Za-z]+(?:\s[A-Za-z]+)*)*(?! )$/;
        return regex.test(name);
    }

    function formatColorName(name) {
        return name
            .replace(/\s+/g, ' ')
            .replace(/\s*\/\s*/g, ' / ')
            .trim()
            .replace(/\b\w/g, char => char.toUpperCase())
            .replace(/\B[A-Z]/g, char => char.toLowerCase());
    }


    $(document).ready(function() {
        const nameInput = $('#name');
        nameInput.on('keyup', function() {
            clearTimeout(this.delay);
            this.delay = setTimeout(function() {
                checkNameAvailability(nameInput.val().trim());
            }, 500);
        });

        function toggleColorCodes() {
            if ($('#color_code_no').is(':checked')) {
                $('#color-code-section').hide();
                $('input[name="color_codes[]"]').each(function() {
                    $(this).val('').prop('disabled', true).removeClass('is-invalid');
                });

                $('input[name="color_codes[]"]').each(function() {
                    $(this).rules("remove", "required uniqueColorCode");
                });

                $('.color-code-error').remove();
                $('#codeErrors').css('display', 'none');

            } else {
                $('#color-code-section').show();
                $('input[name="color_codes[]"]').each(function() {
                    $(this).prop('disabled', false);

                    $(this).rules("add", {
                        required: true,
                        uniqueColorCode: true,
                        messages: {
                            required: "Color code is required.",
                            uniqueColorCode: ""
                        }
                    });
                });

                updateColorCodeErrors();
            }
        }

        function checkNameAvailability(name) {
            if (name.length === 0) {
                $('#nameError').text('');
                $('#form-create').find(':submit').prop('disabled', false);
                return;
            }

            if (!validateColorName(name)) {
                $('#nameError').text('Invalid format! Only alphabets and single "/" allowed with correct spacing.');
                $('#form-create').find(':submit').prop('disabled', true);
                return;
            }

            $.ajax({
                url: '{{ route("color-codes.check-name") }}',
                type: 'POST',
                data: {
                    name: name,
                    belong_to: $('select[name="belong_to"]').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data.exists) {
                        $('#nameError').text('This color name already exists for the selected category.');
                        $('#form-create').find(':submit').prop('disabled', true);
                    } else {
                        $('#nameError').text('');
                        $('#form-create').find(':submit').prop('disabled', false);
                    }
                }
            });
        }


        $('#name').on('blur', function() {
            let formattedName = formatColorName($(this).val());
            $(this).val(formattedName);
            checkNameAvailability(formattedName);
        });

        $('select[name="belong_to"]').on('change', function() {
            let nameInput = $('#name').val().trim();
            if (nameInput.length > 0) {
                checkNameAvailability(nameInput);
            }
        });


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
                $('#codeErrors').css('display', 'none');
            } else {
                $('#color-code-section').show();
                $('input[name="color_codes[]"]').each(function() {
                    $(this).prop('disabled', false);
                    $(this).rules("add", {
                        required: true,
                        uniqueColorCode: true,
                        messages: {
                            required: "",
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
            newInputDiv.className = 'color-code-div-container d-flex align-items-center col-6';

            newInputDiv.innerHTML = `
        <div class="input-group mb-2">
            <input type="text" name="color_codes[]" class="form-control" placeholder="Enter Color Code" required>
            <button type="button" class="btn btn-danger remove-color-code"> &#xd7; </button>
        </div>`;

            document.getElementById('color-code-container').appendChild(newInputDiv);

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

            let emptyFields = codes.some(code => code === "");
            let duplicateCounts = {};

            codes.forEach(code => {
                if (code !== "") {
                    duplicateCounts[code] = (duplicateCounts[code] || 0) + 1;
                }
            });

            let duplicates = Object.keys(duplicateCounts).filter(code => duplicateCounts[code] > 1);

            $('.color-code-error').remove();

            $('input[name="color_codes[]"]').each(function() {
                let parentDiv = $(this).closest('.color-code-div-container');
                let currentValue = $(this).val().trim();

                if (currentValue === "") {
                    $(this).closest('.input-group').addClass('is-invalid');
                    $(this).closest('.input-group').after('<p class="color-code-error text-danger text-center col-6">Color code is missing.</p>');
                    parentDiv.removeClass('col-6').addClass('col-12');
                } else if (duplicates.includes(currentValue)) {
                    $(this).closest('.input-group').addClass('is-invalid');
                    $(this).closest('.input-group').after(`<p class="color-code-error text-danger text-center col-6">Duplicate color code: ${currentValue}.</p>`);
                    parentDiv.removeClass('col-6').addClass('col-12');
                } else {
                    $(this).closest('.input-group').removeClass('is-invalid');
                    parentDiv.removeClass('col-12').addClass('col-6');
                }
            });

            if (emptyFields) {
                $('#codeErrors').text("Color code is missing.").css('visibility', 'visible');
            } else if (duplicates.length > 0) {
                $('#codeErrors').text("Duplicate color codes found: " + duplicates.join(', ') + ". Please enter each code only once.")
                    .css('visibility', 'visible');
            } else {
                $('#codeErrors').text('').css('display', 'none');
            }
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
                parent_colour_id: "required",
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
                parent_colour_id: "Please select a parent colorss",
                // 'color_codes[]': "Color code is required."
            },
            errorPlacement: function(error, element) {
                if (error.text() === "Color Code Duplication or Empty.") {
                    error.addClass('hidden-error').css('display', 'none');
                }
                if (element.attr("name") === "parent_colour_id") {
                    error.insertAfter($('.parent_color_name'));
                } else if (element.attr("name") == "color_codes[]") {
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