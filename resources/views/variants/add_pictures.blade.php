@extends('layouts.main')

@section('content')
    <div class="card-header">
        <h4 class="card-title">Add Variants Pictures</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{ route('variant_pictures.index') }}" text-align: right><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
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
        <form action="{{ route('variant_pictures.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="images">Images:</label>
                <div class="file-upload">
                  <input type="file" id="images" name="images[]" multiple>
                  <label for="images" class="file-label">Drag and drop files here or click to browse</label>
                  <div id="preview" class="file-preview"></div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <script>
        const imagesInput = document.getElementById('images');
        const preview = document.getElementById('preview');
        imagesInput.addEventListener('change', function() {
            const files = Array.from(imagesInput.files);
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function() {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'file-item';

                    const filePreview = document.createElement('img');
                    filePreview.src = reader.result;
                    filePreview.alt = file.name;
                    filePreview.className = 'file-preview';

                    const fileRemove = document.createElement('span');
                    fileRemove.innerText = '×';
                    fileRemove.className = 'file-remove';
                    fileRemove.addEventListener('click', function() {
                        fileItem.remove();
                    });

                    fileItem.appendChild(filePreview);
                    fileItem.appendChild(fileRemove);
                    preview.appendChild(fileItem);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endsection

<style>
    .file-upload {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 200px;
        border: 2px dashed #ccc;
        cursor: pointer;
    }
    .file-label {
        font-size: 16px;
        color: #666;
        padding: 20px;
    }
    .file-preview {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20px;
    }
    .file-item {
        display: flex;
        flex-direction: column;
        margin: 10px;
    }
    .file-remove {
        margin-top: 5px;
        cursor: pointer;
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
</style>
