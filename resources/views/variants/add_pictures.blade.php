@extends('layouts.main')
<style>
     /* Modal Styles */
     .modal {
    display: none; /* Hide the modal by default */
    position: fixed;
    z-index: 9999;
    padding-top: 50px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
  }

  .modal-content {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90%;
  }

  .close {
    color: white;
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 35px;
    font-weight: bold;
    cursor: pointer;
  }

  .close:hover,
  .close:focus {
    color: #999;
    text-decoration: none;
    cursor: pointer;
  }
  .modal-navigation {
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  transform: translateY(-50%);
  display: flex;
  justify-content: space-between;
  padding: 0 20px;
  z-index: 1;
}
.modal-navigation button {
  background-color: #4CAF50;
  color: white;
  border: none;
  padding: 8px 16px;
  cursor: pointer;
}

.modal-navigation button:hover {
  background-color: #45a049;
}

.modal-navigation button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.modal {
  padding-bottom: 60px;
}

.modal-content {
  /* ... Existing styles ... */
  max-width: 90%;
  max-height: calc(90vh - 60px); /* Adjust maximum height based on modal padding-bottom */
  object-fit: contain; /* Adjust object-fit property for better image display */
}
  .file-upload {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 200px;
    border: 2px dashed #ccc;
    cursor: pointer;
    position: relative;
    z-index: 10;
  }

  .file-upload .input-upload {
    position: absolute;
    top: 0;
    right: 0;
    margin: 0;
    padding: 0;
    font-size: 20px;
    cursor: pointer;
    opacity: 0;
    filter: alpha(opacity=0);
    border: 1px solid #222;
    width: 100%;
    height: 100%;
    z-index: 12;
  }

  .file-label {
    font-size: 16px;
    color: #666;
    position: relative;
    z-index: 11;
  }

  .file-preview {
    display: flex;
    flex-wrap: wrap;
    margin-top: 20px; /* Added margin to separate from drag and drop section */
  }

  .file-item {
    display: flex;
    flex-direction: column;
    margin: 10px;
    position: relative;
    z-index: 13;
  }

  .file-remove {
    margin-top: 5px;
    cursor: pointer;
    position: absolute;
    top: -30px;
    font-size: 17px;
    width: 22%;
    text-align: center;
    right: 8px;
    color: #fff;
    background-color: #222;
    border-radius: 100px;
  }

  .file-remove:hover {
    color: red;
  }

  .file-preview img {
    width: 100px;
    height: 100px;
    margin-right: 10px;
    object-fit: cover;
  }

  /* Responsive Styles */

  @media (max-width: 768px) {
    .file-preview img {
      width: 80px;
      height: 80px;
    }

    .file-item {
      margin: 5px;
    }

    .file-remove {
      font-size: 14px;
      width: 30%;
      top: -20px;
      right: 4px;
    }
  }

  @media (max-width: 576px) {
    .file-preview img {
      width: 60px;
      height: 60px;
    }

    .file-item {
      margin: 2px;
    }

    .file-remove {
      font-size: 12px;
      width: 25%;
      top: -15px;
      right: 2px;
    }
  }
  .gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.gallery-item {
    width: 200px;
    height: 200px;
    overflow: hidden;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.gallery-image:hover {
    transform: scale(1.1);
}
  .gallery {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin: 20px 0;
    min-height: 200px;
    max-height: 500px;
    width: 100%;
    overflow-y: auto;
}

.gallery-item {
    flex-basis: calc(33.33% - 10px);
    margin-bottom: 20px;
}

.gallery-item img {
    display: block;
    width: 100%;
    height: auto;
    border: 1px solid gray;
}
.image-upload-container {
  position: relative;
  overflow: hidden;
  width: 300px;
  height: 200px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.image-upload-container input[type=file] {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  opacity: 0;
  cursor: pointer;
}

.image-preview {
  width: 100%;
  height: 100%;
  background-size: contain;
  background-repeat: no-repeat;
  background-position: center;
}

label {
  display: block;
  margin-bottom: 10px;
}
.drag-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  font-size: 18px;
  color: #999;
}

.image-upload-container.drag-over .drag-text {
  display: none;
}
  </style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Add Variants Pictures</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('variant_pictures.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
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
@if(isset($variantsPictures) && $variantsPictures->isNotEmpty())
    <div class="gallery">
        @foreach($variantsPictures as $picture)
            <div class="gallery-item">
            <form action="{{ route('variant_pictures.destroy', ['variant_picture' => $picture->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-dark">&times;</button>
                </form>
                <a href="{{ asset($picture->image_path) }}" target="_blank">
                    <img src="{{ asset($picture->image_path) }}" alt="Picture">
</a>
            </div>
        @endforeach
    </div>
@endif
<form action="{{ route('variant_pictures.store') }}" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-lg-9">
      <div class="form-group">
        <label for="images">Other Images:</label>
        <div class="file-upload">
          <input type="file" id="images" name="images[]" multiple class="input-upload">
          <label for="images" class="file-label">Drag and drop Images here or click to browse</label>
        </div>
      </div>
    </div>
    <div class="col-lg-3">
      <div class="form-group">
        <label for="image-upload">Feature Image</label>
        <div class="image-upload-container">
          <div class="drag-text">Drag and Drop Here</div>
          <input type="file" id="image-upload" name="feature_image" class="form-control-file" accept="image/*" onchange="previewImage(event)">
          <div class="image-preview">
          </div>
        </div>
      </div>
    </div>
    <div id="preview" class="file-preview"></div>
  </div>
  <br>
  <input type="hidden" name="available_colour_id" value="{{ $id }}">
  <div class="col-lg-12 col-md-12 mt-3">
    <input type="submit" name="submit" value="Submit" class="btn btn-success btncenter" />
  </div>
</form>
<!-- Modal -->
<div id="imageModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImage">
  <div class="modal-navigation">
    <button id="prevBtn">&lt; Prev</button>
    <button id="nextBtn">Next &gt;</button>
  </div>
</div>
 <script>
const imagesInput = document.getElementById('images');
const preview = document.getElementById('preview');
const modal = document.getElementById('imageModal');
const modalImg = document.getElementById('modalImage');
const closeBtn = document.getElementsByClassName('close')[0];
const prevBtn = document.getElementById('prevBtn');
const nextBtn = document.getElementById('nextBtn');
let currentIndex = 0;
let imageList = [];
imagesInput.addEventListener('change', function () {
  const files = Array.from(imagesInput.files);
  files.forEach(file => {
    const reader = new FileReader();
    reader.onload = function () {
      const fileItem = document.createElement('div');
      fileItem.className = 'file-item';
      const filePreview = document.createElement('img');
      filePreview.src = reader.result;
      filePreview.alt = file.name;
      filePreview.className = 'file-preview';
      filePreview.addEventListener('click', function () {
        currentIndex = imageList.findIndex(image => image.src === reader.result);
        openModal();
      });
      const fileRemove = document.createElement('span');
fileRemove.innerText = '×';
fileRemove.className = 'file-remove';
fileRemove.addEventListener('click', function () {
  const index = imageList.findIndex(image => image.src === reader.result);
  fileItem.remove();
  const updatedFiles = Array.from(imagesInput.files).filter(f => f !== file);
  const newFileList = new DataTransfer();
  updatedFiles.forEach(file => newFileList.items.add(file));
  imagesInput.files = newFileList.files;
  imageList.splice(index, 1);
  currentIndex = 0;
});
      fileItem.appendChild(filePreview);
      fileItem.appendChild(fileRemove);
      preview.appendChild(fileItem);
      imageList.push(filePreview);
    };
    reader.readAsDataURL(file);
  });
});
closeBtn.addEventListener('click', closeModal);
prevBtn.addEventListener('click', showPrevImage);
nextBtn.addEventListener('click', showNextImage);
function openModal() {
  modal.style.display = 'block';
  modalImg.src = imageList[currentIndex].src;
}
function closeModal() {
  modal.style.display = 'none';
}
function showPrevImage() {
  currentIndex = (currentIndex - 1 + imageList.length) % imageList.length;
  modalImg.src = imageList[currentIndex].src;
}
function showNextImage() {
  currentIndex = (currentIndex + 1) % imageList.length;
  modalImg.src = imageList[currentIndex].src;
}
function previewImage(event) {
  var reader = new FileReader();
  reader.onload = function() {
    var output = document.querySelector('.image-preview');
    output.style.backgroundImage = "url('" + reader.result + "')";
  }
  reader.readAsDataURL(event.target.files[0]);
  document.querySelector('.image-upload-container').classList.add('drag-over');
}
    </script>
@endsection
