@extends('layouts.main')
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create New Bill of Lading Form</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('blform.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
            <div class="row">
			</div>
			<form action="" method="post" enctype="multipart/form-data">
                <div class="row">
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Number</label>
                        <input type="text" name="bl_number" class="form-control" placeholder="Enter Bill of Lading Number" id="BLFormNumber">
                    </div>
					<div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">SO Number</label>
                        <input type="text" name="so_number" class="form-control" placeholder="Enter Sales Order Number">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">No of Containers</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT NO OF CONTAINERS - </option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Trackable on Web</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT OPTION - </option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Looks Genuine</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT OPTION - </option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Is the Shipper Dealer</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT OPTION - </option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">VIN Number</label>
                        <button type="button" class="form-control btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add VIN</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Multiple VINs</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="" id="addNewVINForm">
                                            <div id="newVINRowinModal">
                                                <input type="text" name="vin_number" placeholder="Enter VIN Number" class="form-control" required>
                                            </div>
                                        </form>
                                        <br>
                                        <a class="btn btn-success" id="addNewVINField">Add New VIN</a>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">SO Destination Country</label>
                        <select class="select2 form-control" id="so_destination_country" name="sodescountry"></select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Vehicle Exit Country</label>
                        <select class="select2 form-control" id="veh_exit_country" name="sodescountry"></select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Destination Country</label>
                        <select class="select2 form-control" id="bl_destination_country" name="sodescountry"></select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Port</label>
                        <input type="text" name="bl_number" class="form-control" placeholder="Enter Port Name">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Date</label>
                        <input type="date" name="bl_number" class="form-control">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Real - NonReal</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT OPTION - </option>
                            <option value="Real">Real</option>
                            <option value="Non-Real">Non-Real</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">Status</label>
                        <select class="form-control" name="no_ofcontainers">
                            <option selected disabled value=""> - SELECT OPTION - </option>
                            <option value="Submitted">Submitted</option>
                            <option value="Not-Submitted">Not-Submitted</option>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="basicpill-firstname-input" class="form-label">BL Attachment</label>
                        <input type="file" name="bl_number" class="form-control" placeholder="Enter Bill of Lading Number">
                    </div>
			     </div>
                </br>
                </br>
		        <div class="col-lg-12 col-md-12">
                    <input type="submit" name="submit" value="submit" class="btn btn-success btn-sm btncenter" />
		        </div>
            </form>
		</br>
    </div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#addNewVINField').click(function(e) {
            e.preventDefault();
            var $prevInput = $("#newVINRowinModal input[type='text']:last");
            if ($prevInput.length > 0) {
                $prevInput.prop('readonly', true);
            }
            $("#newVINRowinModal").append('<br><input type="text" name="vin_number" placeholder="Enter VIN Number" class="form-control" required>');
            var vins_numbers = $prevInput.val();
            var bl_number = $("#BLFormNumber").val();
            console.log(vins_numbers);
            console.log(bl_number);
            $.ajax({
                url: '{{ route('store.data') }}',
                type: 'POST',
                data: {
                    'bl_number': bl_number,
                    'vin_number': vins_numbers,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                success: function (data) {
                    alert('Data saved successfully.');
                },
                error: function (xhr, status, error) {
                    alert('Error saving data: ' + error);
                }
            });

            // $.ajax({
            //     url: '',
            //     method: 'POST',
            //     data: {bl_number: bl_number, vins_numbers: vins_numbers},
            //     success: function(response) {
            //         var newInput = '<br><input type="text" name="vin_number" placeholder="Enter VIN Number" class="form-control" value="' + response.vins_numbers + '">';
            //         $("#newVINRowinModal").append(newInput);
            //     },
            //     error: function(jqXHR, textStatus, errorThrown) {
            //         alert('Error storing VIN: ' + errorThrown);
            //     }
            // });
        });
    });

    $(document).ready(function() {
      $("#saveChanges").click(function() {
        $("#BLFormNumber").prop("readonly", true);
      });
    });
    $(document).ready(function() {
        var countries = [
            { id: 1, text: 'Afghanistan' },
            { id: 2, text: 'Aland Islands' },
            { id: 3, text: 'Albania' },
            { id: 4, text: 'Algeria' },
            { id: 5, text: 'American Samoa' },
            { id: 6, text: 'Andorra' },
            { id: 7, text: 'Angola' },
            { id: 8, text: 'Anguilla' },
            { id: 9, text: 'Antarctica' },
            { id: 10, text: 'Antigua and Barbuda' },
            { id: 11, text: 'Argentina' },
            { id: 12, text: 'Armenia' },
            { id: 13, text: 'Aruba' },
            { id: 14, text: 'Australia' },
            { id: 15, text: 'Austria' },
            { id: 16, text: 'Azerbaijan' },
            { id: 17, text: 'Bahamas' },
            { id: 18, text: 'Bahrain' },
            { id: 19, text: 'Bangladesh' },
            { id: 20, text: 'Barbados' },
            { id: 21, text: 'Belarus' },
            { id: 22, text: 'Belgium' },
            { id: 23, text: 'Belize' },
            { id: 24, text: 'Benin' },
            { id: 25, text: 'Bermuda' },
            { id: 26, text: 'Bhutan' },
            { id: 27, text: 'Bolivia' },
            { id: 28, text: 'Bonaire, Sint Eustatius and Saba' },
            { id: 29, text: 'Bosnia and Herzegovina' },
            { id: 30, text: 'Botswana' },
            { id: 31, text: 'Bouvet Island' },
            { id: 32, text: 'Brazil' },
            { id: 33, text: 'British Indian Ocean Territory' },
            { id: 34, text: 'Brunei Darussalam' },
            { id: 35, text: 'Bulgaria' },
            { id: 36, text: 'Burkina Faso' },
            { id: 37, text: 'Burundi' },
            { id: 38, text: 'Cambodia' },
            { id: 39, text: 'Cameroon' },
            { id: 40, text: 'Canada' },
            { id: 41, text: 'Cape Verde' },
            { id: 42, text: 'Cayman Islands' },
            { id: 43, text: 'Central African Republic' },
            { id: 44, text: 'Chad' },
            { id: 45, text: 'Chile' },
            { id: 46, text: 'China' },
            { id: 47, text: 'Christmas Island' },
            { id: 48, text: 'Cocos (Keeling) Islands' },
            { id: 49, text: 'Colombia' },
            { id: 50, text: 'Comoros' },
            { id: 51, text: 'Congo' },
            { id: 52, text: 'Congo, Democratic Republic of the Congo' },
            { id: 53, text: 'Cook Islands' },
            { id: 54, text: 'Costa Rica' },
            { id: 55, text: 'Cote DIvoire' },
            { id: 56, text: 'Croatia' },
            { id: 57, text: 'Cuba' },
            { id: 58, text: 'Curacao' },
            { id: 59, text: 'Cyprus' },
            { id: 60, text: 'Czech Republic' },
            { id: 61, text: 'Denmark' },
            { id: 62, text: 'Djibouti' },
            { id: 63, text: 'Dominica' },
            { id: 64, text: 'Dominican Republic' },
            { id: 65, text: 'Ecuador' },
            { id: 66, text: 'Egypt' },
            { id: 67, text: 'El Salvador' },
            { id: 68, text: 'Equatorial Guinea' },
            { id: 69, text: 'Eritrea' },
            { id: 70, text: 'Estonia' },
            { id: 71, text: 'Ethiopia' },
            { id: 72, text: 'Falkland Islands (Malvinas)' },
            { id: 73, text: 'Faroe Islands' },
            { id: 74, text: 'Fiji' },
            { id: 75, text: 'Finland' },
            { id: 76, text: 'France' },
            { id: 77, text: 'French Guiana' },
            { id: 78, text: 'French Polynesia' },
            { id: 79, text: 'French Southern Territories' },
            { id: 80, text: 'Gabon' },
            { id: 81, text: 'Gambia' },
            { id: 82, text: 'Georgia' },
            { id: 83, text: 'Germany' },
            { id: 84, text: 'Ghana' },
            { id: 85, text: 'Gibraltar' },
            { id: 86, text: 'Greece' },
            { id: 87, text: 'Greenland' },
            { id: 88, text: 'Grenada' },
            { id: 89, text: 'Guadeloupe' },
            { id: 90, text: 'Guam' },
            { id: 91, text: 'Guatemala' },
            { id: 92, text: 'Guernsey' },
            { id: 93, text: 'Guinea' },
            { id: 94, text: 'Guinea-Bissau' },
            { id: 95, text: 'Guyana' },
            { id: 96, text: 'Haiti' },
            { id: 97, text: 'Heard Island and Mcdonald Islands' },
            { id: 98, text: 'Holy See (Vatican City State)' },
            { id: 99, text: 'Honduras' },
            { id: 100, text: 'Hong Kong' },
            { id: 101, text: 'Hungary' },
            { id: 102, text: 'Iceland' },
            { id: 103, text: 'India' },
            { id: 104, text: 'Indonesia' },
            { id: 105, text: 'Iran, Islamic Republic of' },
            { id: 106, text: 'Iraq' },
            { id: 107, text: 'Ireland' },
            { id: 108, text: 'Isle of Man' },
            { id: 109, text: 'Israel' },
            { id: 110, text: 'Italy' },
            { id: 111, text: 'Jamaica' },
            { id: 112, text: 'Japan' },
            { id: 113, text: 'Jersey' },
            { id: 114, text: 'Jordan' },
            { id: 115, text: 'Kazakhstan' },
            { id: 116, text: 'Kenya' },
            { id: 117, text: 'Kiribati' },
            { id: 118, text: 'Korea, Democratic People Republic of' },
            { id: 119, text: 'Korea, Republic of' },
            { id: 120, text: 'Kosovo' },
            { id: 121, text: 'Kuwait' },
            { id: 122, text: 'Kyrgyzstan' },
            { id: 123, text: 'Lao People Democratic Republic' },
            { id: 124, text: 'Latvia' },
            { id: 125, text: 'Lebanon' },
            { id: 126, text: 'Lesotho' },
            { id: 127, text: 'Liberia' },
            { id: 128, text: 'Libyan Arab Jamahiriya' },
            { id: 129, text: 'Liechtenstein' },
            { id: 130, text: 'Lithuania' },
            { id: 131, text: 'Luxembourg' },
            { id: 132, text: 'Macao' },
            { id: 133, text: 'Macedonia, the Former Yugoslav Republic of' },
            { id: 134, text: 'Madagascar' },
            { id: 135, text: 'Malawi' },
            { id: 136, text: 'Malaysia' },
            { id: 137, text: 'Maldives' },
            { id: 138, text: 'Mali' },
            { id: 139, text: 'Malta' },
            { id: 140, text: 'Marshall Islands' },
            { id: 141, text: 'Martinique' },
            { id: 142, text: 'Mauritania' },
            { id: 143, text: 'Mauritius' },
            { id: 144, text: 'Mayotte' },
            { id: 145, text: 'Mexico' },
            { id: 146, text: 'Micronesia, Federated States of' },
            { id: 147, text: 'Moldova, Republic of' },
            { id: 148, text: 'Monaco' },
            { id: 149, text: 'Mongolia' },
            { id: 150, text: 'Montenegro' },
            { id: 151, text: 'Montserrat' },
            { id: 152, text: 'Morocco' },
            { id: 153, text: 'Mozambique' },
            { id: 154, text: 'Myanmar' },
            { id: 155, text: 'Namibia' },
            { id: 156, text: 'Nauru' },
            { id: 157, text: 'Nepal' },
            { id: 158, text: 'Netherlands' },
            { id: 159, text: 'Netherlands Antilles' },
            { id: 160, text: 'New Caledonia' },
            { id: 161, text: 'New Zealand' },
            { id: 162, text: 'Nicaragua' },
            { id: 163, text: 'Niger' },
            { id: 164, text: 'Nigeria' },
            { id: 165, text: 'Niue' },
            { id: 166, text: 'Norfolk Island' },
            { id: 167, text: 'Northern Mariana Islands' },
            { id: 168, text: 'Norway' },
            { id: 169, text: 'Oman' },
            { id: 170, text: 'Pakistan' },
            { id: 171, text: 'Palau' },
            { id: 172, text: 'Palestinian Territory, Occupied' },
            { id: 173, text: 'Panama' },
            { id: 174, text: 'Papua New Guinea' },
            { id: 175, text: 'Paraguay' },
            { id: 176, text: 'Peru' },
            { id: 177, text: 'Philippines' },
            { id: 178, text: 'Pitcairn' },
            { id: 179, text: 'Poland' },
            { id: 180, text: 'Portugal' },
            { id: 181, text: 'Puerto Rico' },
            { id: 182, text: 'Qatar' },
            { id: 183, text: 'Reunion' },
            { id: 184, text: 'Romania' },
            { id: 185, text: 'Russian Federation' },
            { id: 186, text: 'Rwanda' },
            { id: 187, text: 'Saint Barthelemy' },
            { id: 188, text: 'Saint Helena' },
            { id: 189, text: 'Saint Kitts and Nevis' },
            { id: 190, text: 'Saint Lucia' },
            { id: 191, text: 'Saint Martin' },
            { id: 192, text: 'Saint Pierre and Miquelon' },
            { id: 193, text: 'Saint Vincent and the Grenadines' },
            { id: 194, text: 'Samoa' },
            { id: 195, text: 'San Marino' },
            { id: 196, text: 'Sao Tome and Principe' },
            { id: 197, text: 'Saudi Arabia' },
            { id: 198, text: 'Senegal' },
            { id: 199, text: 'Serbia' },
            { id: 200, text: 'Serbia and Montenegro' },
            { id: 201, text: 'Seychelles' },
            { id: 202, text: 'Sierra Leone' },
            { id: 203, text: 'Singapore' },
            { id: 204, text: 'Sint Maarten' },
            { id: 205, text: 'Slovakia' },
            { id: 206, text: 'Slovenia' },
            { id: 207, text: 'Solomon Islands' },
            { id: 208, text: 'Somalia' },
            { id: 209, text: 'South Africa' },
            { id: 210, text: 'South Georgia and the South Sandwich Islands' },
            { id: 211, text: 'South Sudan' },
            { id: 212, text: 'Spain' },
            { id: 213, text: 'Sri Lanka' },
            { id: 214, text: 'Sudan' },
            { id: 215, text: 'Suriname' },
            { id: 216, text: 'Svalbard and Jan Mayen' },
            { id: 217, text: 'Swaziland' },
            { id: 218, text: 'Sweden' },
            { id: 219, text: 'Switzerland' },
            { id: 220, text: 'Syrian Arab Republic' },
            { id: 221, text: 'Taiwan, Province of China' },
            { id: 222, text: 'Tajikistan' },
            { id: 223, text: 'Tanzania, United Republic of' },
            { id: 224, text: 'Thailand' },
            { id: 225, text: 'Timor-Leste' },
            { id: 226, text: 'Togo' },
            { id: 227, text: 'Tokelau' },
            { id: 228, text: 'Tonga' },
            { id: 229, text: 'Trinidad and Tobago' },
            { id: 230, text: 'Tunisia' },
            { id: 231, text: 'Turkey' },
            { id: 232, text: 'Turkmenistan' },
            { id: 233, text: 'Turks and Caicos Islands' },
            { id: 234, text: 'Tuvalu' },
            { id: 235, text: 'Uganda' },
            { id: 236, text: 'Ukraine' },
            { id: 237, text: 'United Arab Emirates. UAE' },
            { id: 238, text: 'United Kingdom. UK' },
            { id: 239, text: 'United States of America. USA' },
            { id: 240, text: 'United States Minor Outlying Islands' },
            { id: 241, text: 'Uruguay' },
            { id: 242, text: 'Uzbekistan' },
            { id: 243, text: 'Vanuatu' },
            { id: 244, text: 'Venezuela' },
            { id: 245, text: 'Viet Nam' },
            { id: 246, text: 'Virgin Islands, British' },
            { id: 247, text: 'Virgin Islands, U.s.' },
            { id: 248, text: 'Wallis and Futuna' },
            { id: 249, text: 'Western Sahara' },
            { id: 250, text: 'Yemen' },
            { id: 251, text: 'Zambia' },
            { id: 252, text: 'Zimbabwe' },
        ];
        $('#so_destination_country').select2({
            data: countries
        });
        $('#veh_exit_country').select2({
            data: countries
        });
        $('#bl_destination_country').select2({
            data: countries
        });
    });

</script>
@endpush




