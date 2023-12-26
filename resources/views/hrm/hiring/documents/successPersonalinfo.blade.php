<!doctype html>
<html lang="en">
    <head>
        @include('partials/head-css')
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body data-layout="horizontal">
        <div class="card-header" style="background-color:#005ba1!important;">
            <div class="dropdown d-inline-block align-items-center" style="position: absolute; left: 13px; top:7px; z-index: 500;">
                <img src="{{ asset('logo.png') }}" width="20" height="40" alt="Logo" class="mx-auto">
            </div>
            <h1 class="card-title" style="color:white!important;"><center>MILELE</center></h1>
		</div>
        <div id="layout-wrapper">
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <!-- <div class="card-header" style="background-color:#d2e7f9!important;">
										<h4 class="card-title"><center>CANDIDATE PERSONAL INFORMATION & DOCUMENTS SHAREING FORM</center></h4>
									</div> -->
                                    <div class="card-body">{{$successMessage}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>