<!-- <script src="{{ asset('libs/jquery/jquery.min.js') }}"></script> -->
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
<!-- pace js -->
<script src="{{ asset('libs/pace-js/pace.min.js') }}"></script>
<!-- CHANGE CDN TO LOCAL PATH --><!-- add new js/custom/select2.min.js -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="{{ asset('js/custom/select2.min.js') }}"></script>
{{--<script src="{{ asset('libs/sweet-alert/sweetalert.min.js') }}"></script>--}}
<!-- CHANGE CDN TO LOCAL PATH --><!-- add new js/custom/alertify.min.js -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/AlertifyJS/1.13.1/alertify.min.js"  crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="{{ asset('js/custom/alertify.min.js') }}"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" ></script> -->
<!-- CHANGE CDN TO LOCAL PATH --><!-- add new js/custom/additional-methods.min.js -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script> -->
<script src="{{ asset('js/custom/additional-methods.min.js') }}"></script>
<script src="{{ asset('datepick/js/yearpicker.js') }}"></script>
<!-- CHANGE CDN TO LOCAL PATH --><!-- add new js/custom/moment.min.js -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script> -->
<script src="{{ asset('js/custom/moment.min.js') }}"></script>
<script src="{{ asset('js/custom/daterangepicker.min.js') }}"></script>

<script>
    jQuery.validator.setDefaults({
        errorClass: "is-invalid",
        errorElement: "p",
        errorPlacement: function ( error, element ) {
            error.addClass( "invalid-feedback font-size-16" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            }
            else if (element.hasClass("select2-hidden-accessible")) {
                element = $("#select2-" + element.attr("id") + "-container").parent();
                error.insertAfter(element);
            }
            else {
                error.insertAfter( element );
            }
        }
    });
</script>
