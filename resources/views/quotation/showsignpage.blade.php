<!DOCTYPE html>
<html>
<head>
<script src="https://unpkg.com/konva@9.2.1/konva.min.js"></script>
    <title>Quotation with Signature</title>
</head>
<body>
    <center>
<img src="{{ public_path('images/proforma/milele_logo.png') }}" width="300px" height="80px" ><span class="logo-txt"></span>
<h1>Quotation Details</h1>
</center>
<iframe src="{{ $pdfPath }}" style="width: 80vw; height: 80vh; margin: auto; display: block;"></iframe>
<div class="row">
    <div class="col-md-12">
    <input type="hidden" id="canvas-image" name="canvas_image" />
    <div id="canvas-container" style="margin-top: 20px;"></div>
    <button type="button" id="reset-button" class="btn btn-secondary btncenter">Reset</button>
    </div>
</div>
</body>
</html>
