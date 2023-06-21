@extends('layouts.main')
<style>
.video-container {
    position: relative;
    padding-bottom: 15%; /* 16:9 aspect ratio */
    overflow: hidden;
    border: 1px solid black;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 5%;
    width: 90%;
    height:315px;
    display: block;
}

.video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    grid-gap: 20px;
}
.delete-btn {
  position: absolute;
  top: 5px;
  right: 5px;
  background-color: red;
  color: white;
  padding: 5px 10px;
  border-radius: 5px;
  cursor: pointer;
}

.btn-danger {
    color: #fff;
    background-color: #fd625e;
    border-color: #fd625e;
    position: absolute;
}
</style>
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
<div class="card-header">
        <h4 class="card-title">Add New Videos</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('variant_pictures.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body row">
    @if(!empty($videos))
    <h2>16 x 9 Videos</h2>
    @foreach ($videos as $index => $embedCode)
        @if ($embedCode !== null)
            <div class="video-container col-md-3">
                {!! $embedCode !!}
                <form method="POST" action="{{ route('delete_video', $variantsreelss[$index]->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-button">Delete</button>
                </form>
            </div>
        @endif
    @endforeach
@else
    <p>No video available.</p>
@endif
    <hr>
    <div class="card-body row">
    @if(!empty($reel))
        <h2>9 x 16 Reels</h2>
        @foreach ($reel as $index => $embedCodereel)
        @if ($embedCodereel !== null)
            <div class="video-container col-md-3">
                {!! $embedCodereel !!}
                <form method="POST" action="{{ route('delete_reel', $variantsreelss[$index]->id) }}" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger delete-button">Delete</button>
                </form>
            </div>
            @endif
        @endforeach
    @endif
</div>
<hr>
			<form id="variant-pictures-form" method="POST" action="{{ route('variant_pictures.uploadingreal') }}" enctype="multipart/form-data">
    @csrf
    <!-- Existing input fields -->
    <div class="maindd">
    <div class="row">
            <div class="col-lg-6 col-md-6">
                <label for="reel-path" class="form-label">Reel Link:</label>
                <input type="text" name="reel_path[]" class="form-control reel-path-input" value="">
                <input type="hidden" name="available_colour_id" value="{{ $id }}">
            </div>
            <div class="col-lg-6 col-md-6">
                <label for="video-path" class="form-label">Full Video Link:</label>
                <input type="text" name="video_path[]" class="form-control video-path-input" value="">
            </div>
</div>
</div>
    <!-- Submit button -->
<div class="col-lg-12 col-md-12 mt-3 d-flex justify-content-end">
        <div class="btn btn-primary add-row-btn">
            <i class="fas fa-plus"></i> Add New Row
        </div>
    </div>
    <div class="col-lg-12 col-md-12 mt-3">
        <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
    </div>
</form>
    <script>
    $(document).ready(function() {
        // Add row on plus button click
        $(document).on("click", ".add-row-btn", function() {
            var newRow = '<div class="row">'+
            '<div class="col-lg-6 col-md-6">'+
                        '<label for="reel-path" class="form-label">Reel Link:</label>' +
                            '<input type="text" name="reel_path[]" class="form-control reel-path-input" value="">' +
                            '</div>' +
                            '<div class="col-lg-6 col-md-6">' +
                                '<label for="video-path" class="form-label">Full Video Link:</label>' +
                                '<input type="text" name="video_path[]" class="form-control video-path-input" value="">'+
                                '</div>';
            $('.maindd').append(newRow);
        });
    });
</script>
@else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection