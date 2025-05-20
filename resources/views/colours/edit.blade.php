@extends('layouts.main')
<style>
.error {
    color: red;
}

    .heading-background {
  display: inline-block;
  background-color: #f2f2f2;
  padding: 5px 10px;
}
    </style>
@section('content')
    @can('colour-edit')
        @php
            $hasPermission = Auth::user()->hasPermissionForSelectedRole('colour-edit');
        @endphp
        @if ($hasPermission)
            <div class="card-header">
                <h4 class="card-title">Edit Master Colours</h4>
                <a  class="btn btn-sm btn-info float-end" href="{{route('colourcode.index')}}" ><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
                @if (Session::has('error'))
                    <div class="alert alert-danger" >
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('error') }}
                    </div>
                @endif
                @if (Session::has('success'))
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="btn-close p-0 close" data-dismiss="alert">x</button>
                        {{ Session::get('success') }}
                    </div>
                @endif
                <form id="form-update" action="{{ route('colourcode.update', $colorcodes->id) }}" method="POST" >
                    @method('PUT')
                    @csrf
                    <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Code</label>
                                <input type="text" value="{{ old('code', $colorcodes->code) }}" name="code" class="form-control " placeholder="Colour Code" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="choices-single-default" class="form-label">Name</label>
                                <input type="text" value="{{ old('name', $colorcodes->name) }}" name="name" class="form-control " placeholder="Colour Name" required autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                <span class="error">* </span>
                                    <label for="choices-single-default" class="form-label">Belong To</label>
                                    <select class="form-control" autofocus name="belong_to" id="model">
                                        <option value="int" {{ old('belong_to') == 'int' || $colorcodes->belong_to == 'int' ? 'selected' : '' }}>Interior</option>
                                        <option value="ex" {{ old('belong_to') == 'ex' || $colorcodes->belong_to == 'ex' ? 'selected' : '' }}>Exterior</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                <span class="error">* </span>
                                    <label for="choices-single-default" class="form-label">Parent Colour</label>
                                    <select class="form-control" autofocus name="parent" id="model">
                                        <option value="Black" {{ old('parent') == 'Black' || $colorcodes->parent == 'Black' ? 'selected' : '' }}>Black</option>
                                        <option value="White" {{ old('parent') == 'White' || $colorcodes->parent == 'White' ? 'selected' : '' }}>White</option>
                                        <option value="Grey" {{ old('parent') == 'Grey' || $colorcodes->parent == 'Grey' ? 'selected' : '' }}>Grey</option>
                                        <option value="Beige" {{ old('parent') == 'Beige' || $colorcodes->parent == 'Beige' ? 'selected' : '' }}>Beige</option>
                                        <option value="Blue" {{ old('parent') == 'Blue' || $colorcodes->parent == 'Blue' ? 'selected' : '' }}>Blue</option>
                                        <option value="Red" {{ old('parent') == 'Red' || $colorcodes->parent == 'Red' ? 'selected' : '' }}>Red</option>
                                        <option value="Brown" {{ old('parent') == 'Brown' || $colorcodes->parent == 'Brown' ? 'selected' : '' }}>Brown</option>
                                        <option value="Orange" {{ old('parent') == 'Orange' || $colorcodes->parent == 'Orange' ? 'selected' : '' }}>Orange</option>
                                        <option value="Green" {{ old('parent') == 'Green' || $colorcodes->parent == 'Green' ? 'selected' : '' }}>Green</option>
                                        <option value="Yellow" {{ old('parent') == 'Yellow' || $colorcodes->parent == 'Yellow' ? 'selected' : '' }}>Yellow</option>
                                        <option value="Others" {{ old('parent') == 'Others' || $colorcodes->parent == 'Others' ? 'selected' : '' }}>Others</option>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="mb-3">
                                <span class="error">* </span>
                                    <label for="choices-single-default" class="form-label">Status</label>
                                    <select class="form-control" autofocus name="status" id="model">
                                        <option value="Active" {{ old('status') == 'Active' || $colorcodes->status == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="De-Active" {{ old('status') == 'De-Active' || $colorcodes->status == 'De-Active' ? 'selected' : '' }}>De-Active</option>
                                    </select>
                                </div>
                            </div> -->
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <h4 class="card-title heading-background text-center">Logs</h4>
            <div class="card-body">
            <div class="table-responsive">
                    <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
                        <thead class="bg-soft-secondary">
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th>Changed By</th>
                                <th>Field</th>
                                <th>Old Value</th>
                                <th>New Value</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($colorlog as $colorlog)
                        <tr data-id="1">
                                <td>{{ date('d-m-Y', strtotime($colorlog->date)) }}</td>
                                <td>{{$colorlog->time}}</td>
                                <td>{{$colorlog->status}}</td>
                                <td>
                                    @php
                                        $change_by = DB::table('users')->where('id', $colorlog->created_by)->first();
                                        $change_bys = $change_by->name;
                                    @endphp
                                    {{$change_bys}}
                                </td>
                                <td>
                                    @if($colorlog->field == "belong_to")
                                        Belong To
                                    @elseif($colorlog->field == "parent")
                                    Parent Colour
                                    @elseif($colorlog->field == "status")
                                        Status
                                        @elseif($colorlog->field == "code")
                                        Colour Code
                                        @elseif($colorlog->field == "name")
                                        Colour Name
                                    @endif
                                </td>
                                @if($colorlog->field == "belong_to")
                                @if($colorlog->old_value == "int")
                                    <td>Interior</td>
                                    <td>Exterior</td>
                                @elseif($colorlog->old_value == "ex")
                                    <td>Exterior</td>
                                    <td>Interior</td>
                                @else
                                    <td>{{ $colorlog->old_value }}</td>
                                    <td>{{ $colorlog->new_value }}</td>
                                @endif
                                @else
                                <td>{{ $colorlog->old_value }}</td>
                                <td>{{ $colorlog->new_value }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        @endif
        @endcan
@endsection
