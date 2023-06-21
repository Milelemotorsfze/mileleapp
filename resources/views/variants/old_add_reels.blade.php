@extends('layouts.main')
<style>
    /* Video player container */
.video-container {
  position: relative;
  width: 100%;
  max-width: 640px; /* Adjust as needed */
  margin: 20px auto;
}

/* Video player */
.video-container video {
  display: block;
  width: 100%;
  height: auto;
}
    </style>
@section('content')
@if (Auth::user()->selectedRole === '3' || Auth::user()->selectedRole === '4')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Variant Reels</h4>
    </div>
    <div class="card-body">
    @if(isset($variantsreelss) && $variantsreelss->isNotEmpty())
    <div class="gallery">
    <h5>Uploaded Reel</h5>
        @foreach($variantsreelss as $reels)
            <div class="gallery-item">
            <form action="{{ route('variant_pictures.destroy', ['variant_picture' => $reels->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-dark">&times;</button>
                </form>
                <div class="video-container">
  <a href="{{ asset('storage/' . $reels->reel_path) }}" target="_blank">
    <video controls>
      <source src="{{ asset('storage/' . $reels->reel_path) }}" type="video/mp4">
      Your browser does not support the video tag.
    </video>
  </a>
</div>
            </div>
        @endforeach
    </div>
@endif
    <div id="uploadedVideoContainer" style="display: none;">
                <h5>Uploaded Reel</h5>
                <video id="uploadedVideo" controls></video>
            </div>
        <div class="upload-form">
            <form action="{{ route('variant_pictures.uploadingreal') }}" class="dropzone" id="video-dropzone">
                @csrf
                <div class="dz-message needsclick">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <span>Drag and drop video files here or click to upload</span>
                </div>
                </form>
                <div class="text-center mt-3">
                <a href="{{ route('variant_pictures.index') }}"><button type="submit" class="btn btn-primary">Finish</button></a>
                </div>
                </div>
                </div>
                </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        Dropzone.options.videoDropzone = {
            paramName: 'video',
            maxFilesize: 200, 
            acceptedFiles: 'video/mp4, video/quicktime',
            init: function() {
                var self = this;
                this.on('success', function(file, response) {
                    console.log(response);
                    var uploadedVideo = document.getElementById('uploadedVideo');
                    uploadedVideo.src = response.path;
                    uploadedVideo.load();
                    document.getElementById('uploadedVideoContainer').style.display = 'block';
                    self.removeAllFiles();
                });
                this.on('error', function(file, response) {
                    console.log(response);
                    alert('Error uploading the video.');
                });
            }
        };
        var videoDropzone = new Dropzone('#video-dropzone');
        videoDropzone.options.url = '{{ route('variant_pictures.uploadingreal') }}';
    </script>
    @else
    @php
        redirect()->route('home')->send();
    @endphp
@endif
@endsection
<style>
    .card {
    margin: 0 auto;
    width: 60%;
}
    .card-header {
        background-color: #007bff;
        padding: 10px 20px;
        border-radius: 5px 5px 0 0;
        color: #fff;
    }
    .card-title {
        margin: 0;
        font-size: 20px;
    }
    .card-body {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
    .upload-form {
        margin-top: 20px;
    }

    .dz-message {
        text-align: center;
        margin-bottom: 10px;
    }

    .fas {
        font-size: 40px;
        margin-bottom: 10px;
        color: #007bff;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
    }
    #uploadedVideoContainer {
        display: none;
        margin-top: 20px;
    }

    #uploadedVideo {
        width: 100%;
    }
</style>
