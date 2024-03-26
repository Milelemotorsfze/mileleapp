@extends('layouts.main')
@section('content')
<style>
        .hidden {
            display: none;
        }
    </style>
<div class="card-header">
    <h4 class="card-title">Create a Posting</h4>
    <a style="float: right;" class="btn btn-sm btn-info" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
</div>
<div class="card-body">
    <div class="row">
    <form action="{{ route('posting.storeposting', ['leadssource_id' => $leadssource_id]) }}" method="post" enctype="multipart/form-data">
    @csrf
            <div class="row">
            <div class="col-lg-4 col-md-6">
                    <label for="variant-input" class="form-label">Variant</label>
                    <select name="variant" class="form-control" id="variant-input">
                        <option value="" disabled selected>Select The Variant</option>
                        @foreach($variants as $variant)
                            <option value="{{ $variant->id }}">{{ $variant->name }}</option>
                        @endforeach
                    </select>
                </div>
                </div>
                <br>
                <div class="row">
                <div class="col-lg-2 col-md-6" id="price-container">
                    <label for="price-input" class="form-label">Video</label>
                    <select class="form-control" id="video" name="video" required>
                    <option value="" disabled selected>Select an Option</option>
                    <option value="Done" data-value="Done">Done</option>
                    <option value="Pending" data-value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="destruction-input" class="form-label">Reel</label>
                    <select class="form-control" id="reels" name="reels" required>
                    <option value="" disabled selected>Select an Option</option>
                    <option value="Done" data-value="Done">Done</option>
                    <option value="Pending" data-value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="destruction-input" class="form-label">Pictures</label>
                    <select class="form-control" id="pictures" name="pictures" required>
                    <option value="" disabled selected>Select an Option</option>
                    <option value="Done" data-value="Done">Done</option>
                    <option value="Pending" data-value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="destruction-input" class="form-label">Ads</label>
                    <select class="form-control" id="ads" name="ads" required>
                    <option value="" disabled selected>Select an Option</option>
                    <option value="Done" data-value="Done">Done</option>
                    <option value="Pending" data-value="Pending">Pending</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label for="destruction-input" class="form-label">Stories</label>
                    <select class="form-control" id="stories" name="stories" required>
                    <option value="" disabled selected>Select an Option</option>
                    <option value="Done" data-value="Done">Done</option>
                    <option value="Pending" data-value="Pending">Pending</option>
                    </select>
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
        $(document).ready(function() {
            $('#variant-input').select2();
            $('#platform-input').select2();
        });
    </script>
@endpush