<!doctype html>
<html lang="en">
<head>
@include('partials.head-css')
</head>
<body data-layout="horizontal">
    <div id="layout-wrapper">
    @include('partials.horizontal')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
// Code Here
            </div>
        </div>
        @include('partials.footer')
    </div>
</div>
@include('partials.right-sidebar')
@include('partials.vendor-scripts')
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>