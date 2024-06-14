<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="row m-0">
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Date Range</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="date_range" class="form-control widthinput" placeholder="Date Range">
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
        <label class="col-form-label">Type</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="type" class="form-control widthinput" placeholder="Type">
    </div>
    <div class="col-md-3 col-xxl-1 col-lg-1 col-sm-12">
        <label class="col-form-label">Field</label>
    </div>
    <div class="col-md-3 col-xxl-2 col-lg-2 col-sm-12">
        <input type="text" id="field" class="form-control widthinput" placeholder="Field">
    </div>
</div>
<div class="row mt-1">
    <div class="table-responsive">
        <table class="table table-striped table-editable table-edits table table-condensed my-datatable" >
            <thead style="background-color: #e6f1ff">
                <tr>
                    <th>Date & Time</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Field</th>
                    <th>Old Value</th>
                    <th>New Value</th>
                </tr>
            </thead>
            <tbody>
            @if(isset($workOrder) && isset($workOrder->dataHistories) && count($workOrder->dataHistories) > 0)
                @foreach($workOrder->dataHistories as $dataHistory)
                    <tr>
                        <td>{{ $dataHistory->changed_at->format('d M Y, H:i:s') }}</td>
                        <td>{{ $dataHistory->user->name }}</td> 
                        <td>{{ $dataHistory->type }}</td>
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
