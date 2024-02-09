<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <title>Quotation with Signature</title>
    <style>
        .custom-file-upload {
    border: 2px solid #ccc;
    display: inline-block;
    padding: 6px 12px;
    cursor: pointer;
    background-color: #f9f9f9;
    color: #333;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.custom-file-upload:hover {
    background-color: #e9e9e9;
}

.custom-file-upload i {
    margin-right: 5px;
}
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: Arial, sans-serif;
        }
        #canvas-container {
            position: relative;
            width: 50vw;
            height: 20vh;
            border: 2px solid #000;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        #signature-box {
            position: absolute;
            width: 100%;
            height: 100%;
        }
        #reset-button, #submit-button {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }
        #reset-button {
            background-color: #ddd;
            color: #333;
        }
        #reset-button:hover {
            background-color: #ccc;
        }
        #submit-button {
            background-color: #4CAF50;
            color: white;
        }
        #submit-button:hover {
            background-color: #45a049;
        }
        .container {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding-top: 56.25%;
        }
        #pdf-view {
        width: 80%;
        height: 70vh;
        border: none;
        aspect-ratio: 16/9;
                }
                .logo-with-margin {
        margin-top: 50px;
    }
    </style>
</head>
<body>
    <center>
    <img src="{{ $logo }}" width="300px" height="80px" class="logo-with-margin"><span class="logo-txt"></span>
        <h1>Quotation Details</h1>
    </center>
    <iframe id="pdf-view" src="{{ $pdfPath }}"></iframe>
    <h1>Signature Please</h1>
    <strong><p>
    By affixing my signature to this document, I hereby acknowledge, confirm, and unequivocally commit to adhering to all the terms and conditions delineated herein.
    </p></strong>
    <div class="row">
        <div class="col-md-12">
            <div class="col-md-6">
            <input type="hidden" id="canvas-image" name="canvas_image" />
            <div id="canvas-container">
                <div id="signature-box"></div>
            </div>
</div>
<div class="col-md-6">
<label for="file-upload" class="custom-file-upload">
    <i class="fas fa-cloud-upload-alt"></i> Uploading Stamp
</label>
<input type="file" id="file-upload" name="file_upload" style="display: none;" />
</div>
<center>
            <button type="button" id="reset-button">Reset</button>
            <button type="button" id="submit-button">Submit</button>
            </center>
        </div>
    </div>
    <div id="thank-you-message" style="display: none;">
        <h2>Thank you for signing!</h2>
    </div>
</br>
</br>
    <script>
        const stage = new Konva.Stage({
            container: 'signature-box',
            width: window.innerWidth * 0.5,
            height: window.innerHeight * 0.2,
        });

        const layer = new Konva.Layer();
        stage.add(layer);
        const signatureBox = new Konva.Rect({
            width: stage.width(),
            height: stage.height(),
            stroke: 'black',
            strokeWidth: 1,
        });
        layer.add(signatureBox);
        let isDrawing = false;
        let lastLine;
        stage.on('mousedown touchstart', () => {
            isDrawing = true;
            const pos = stage.getPointerPosition();
            lastLine = new Konva.Line({
                stroke: 'black',
                strokeWidth: 5,
                lineCap: 'round',
                globalCompositeOperation: 'source-over',
                points: [pos.x, pos.y],
            });
            layer.add(lastLine);
        });
        stage.on('mousemove touchmove', () => {
            if (!isDrawing) {
                return;
            }
            const pos = stage.getPointerPosition();
            let newPoints = lastLine.points().concat([pos.x, pos.y]);
            lastLine.points(newPoints);
            layer.batchDraw();
        });
        stage.on('mouseup touchend', () => {
            isDrawing = false;
        });
        document.getElementById('reset-button').addEventListener('click', () => {
            layer.destroyChildren();
            layer.add(signatureBox);
            layer.batchDraw();
        });
        document.getElementById('submit-button').addEventListener('click', () => {
            const signatureDataURL = stage.toDataURL();
            const pdfPath = '{{ $pdfPath }}';
            const qoutation_id = '{{ $qoutation_id }}';
            const stampInput = document.getElementById('file-upload');
            const stampFile = stampInput.files[0]; // Get the uploaded stamp file
            fetch('/submit-signature', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ signature_data: signatureDataURL, pdf_path: pdfPath,  qoutation_id: qoutation_id, stampFile: stampFile}),
            })
            .then(response => {
                if (response.ok) {
                            document.body.innerHTML = '';
                            document.body.innerHTML = '<h2>Thank you for Signature!</h2>';
                        } else {
                    console.error('Failed to submit signature:', response.statusText);
                }
            })
            .catch(error => {
                console.error('Error submitting signature:', error);
            });
            });
</script>
</body>
</html>