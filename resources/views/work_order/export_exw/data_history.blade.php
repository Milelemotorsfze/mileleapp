<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="card-body">
    <div class="row m-0">
        <div class="col-xxl-2 col-lg-2 col-md-6 col-sm-12 mb-2" style="background-color: #e6f1ff" >
            <label class="col-form-label">Filter Region</label>
        </div>
        <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
            <label class="col-form-label">User</label>
        </div>
        <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
            <select name="user_id" id="user_id" multiple="true" class="form-control widthinput">
                @foreach($users as $user)
                <option value="{{$user->id ?? ''}}">{{$user->name ?? ''}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
            <label class="col-form-label">Field</label>
        </div>
        <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
            <input type="text" class="form-control widthinput" placeholder="Field">
        </div>
        <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
            <label class="col-form-label">History Type</label>
        </div>
        <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
            <input type="text" class="form-control widthinput" placeholder="History Type">
        </div>
    </div>
    <div class="row mt-1">
        <div class="table-responsive">
            <table class="table table-striped table-editable table-edits table table-condensed my-datatable" >
                <thead style="background-color: #e6f1ff">
                    <tr>
                        <th>Date</th>
                        <th>User</th>
                        <th>History Type</th>
                        <th>Old Value</th>
                        <th>New Value</th>
                    </tr>
                </thead>
                <tbody>
                @if(isset($workOrder) && isset($workOrder->dataHistories) && count($workOrder->dataHistories) > 0)
                    @foreach($workOrder->dataHistories as $dataHistory)
                        <tr>
                            <td>{{ $dataHistory->changed_at->format('d M Y, H:i:s') }}</td>
                            <td>{{ $dataHistory->user->name }}</td> <!-- Assuming you have a user relationship set up -->
                            <td>{{ $dataHistory->field_name }}</td>
                            <td>{{ $dataHistory->old_value }}</td>
                            <td>{{ $dataHistory->new_value }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">No data history available.</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script type="text/javascript">

</script>