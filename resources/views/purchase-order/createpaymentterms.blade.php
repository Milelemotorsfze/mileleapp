@extends('layouts.main')
@section('content')
<style>
    .hidden {
        display: none;
    }

    .milestone-table th,
    .milestone-table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }

    .milestone-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .milestone-table td.select-cell {
        width: 30%; 
    }

</style>
<div class="card-header">
    <h4 class="card-title">Create New Payment Terms</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
        <form action="{{ route('paymentterms.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-lg-2 col-md-6">
                    <label for="from_port" class="form-label"> <span class="text-danger">*</span> <strong>Payment Term Name</strong></label>
                    <input type="text" name="name" class="form-control" required/>
                </div>
                <div class="col-lg-6 col-md-6">
                    <label for="from_port" class="form-label"><strong>Payment Term Description</strong></label>
                    <input type="text" name="description" class="form-control" required/>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table class="table table-bordered milestone-table">
                    <thead>
                        <tr>
                            <th>Milestone</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                $milestones = ['Advance', 'VIN', 'Colours', 'Shipping', 'Inbounding', 'GRN', 'Inspection'];
                @endphp
                @foreach($milestones as $milestone)
                <tr>
                    <td>
                        <h5>{{ $milestone }}</h5>
                    </td>
                    <td class="select-cell">
                        <select name="milestones[{{ ($milestone) }}]" class="form-select milestone-percentage milestone-percentage-select" id="{{ ($milestone) }}" onchange="calculateTotalPercentage()">
                            <option value="" selected>Select a Percentage</option>
                            @php
                            for ($i = 5; $i <= 100; $i += 5) {
                                echo "<option value=\"$i\">$i%</option>";
                            }
                            @endphp
                        </select>
                    </td>
                </tr>
                @endforeach
                    </tbody>
                </table>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <p><strong>Total Percentage:</strong> <span id="totalPercentage">0%</span></p>
                </div>
            </div>
            <br><br>
            <div class="col-lg-12 col-md-12">
                <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter">
            </div>
        </form>
    </div>
    <br>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('.milestone-percentage').select2();
        $('.select2-container').parent().css('width', '70%');
    });
    

    function calculateTotalPercentage() {
        var totalPercentage = 0;
        $('.milestone-percentage').each(function () {
            totalPercentage += parseInt($(this).val()) || 0;
        });
        $('#totalPercentage').text(totalPercentage + '%');
        $('input[name="submit"]').prop('disabled', totalPercentage !== 100);
    }
</script>
@endpush