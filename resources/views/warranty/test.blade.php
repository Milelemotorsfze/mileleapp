@extends('layouts.main')
<style>
    .error
    {
        color: #FF0000;
    }
    input:focus
    {
        border-color: #495057!important;
    }
    select:focus
    {
        border-color: #495057!important;
    }
    .widthinput
    {
        height:32px!important;
    }
    .paragraph-class
    {
        color: red;
        font-size:11px;
    }
    .overlay
    {
        position: fixed; /* Positioning and size */
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(128,128,128,0.5); /* color */
        display: none; /* making it hidden by default */
    }
</style>
@section('content')
    <div class="card-header">
        <h4 class="card-title">Create Warranty</h4>
        <a style="float: right;" class="btn btn-sm btn-info" href="{{url()->previous()}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
    </div>
    <div class="card-body">
        <div class="rp-container">
            <a href="javascript:;" id="addNewItem">+ Add new</a>
            <div id="inputContainers"></div>
        </div>
    </div>
    <input type="hidden" id="indexValue" value="">
    <div class="overlay"></div>
    <script type="text/javascript">

        $("#addNewItem").click(function(){
            $('<div class="input-row"><span>Row '+ ($('.input-row').length + 1) +'</span> <input type="text" value="Input number '+ ($('.input-row').length + 1) +'">' +
                '<a href="javascript:;" class="remove-row" id="removeItem-' + ($('.input-row').length + 1) + '">Remove</a></div>').appendTo('#inputContainers');
        });

        jQuery(document).on('click', '.remove-row', function(){
            jQuery(this).closest('.input-row').remove();
            $('.input-row').each(function(i){
                $(this).find('span').html('Row ' + (i+1));
                $(this).find('input').val('Input number ' + (i+1));
                $(this).find('a').attr('id','removeItem-' + (i+1));
            });
        })


    </script>
@endsection
