<style>
    .paragraph-class 
    {
        color: red;
        font-size:11px;
    }
    .required-class
    {
        font-size:11px;
    }
</style>
<div class="card" id="branModaDiv">
    <div class="card-header">
        <h4 class="card-title">Addon Brand and Model Lines</h4>
    </div>
    <div id="London" class="tabcontent">
        <div class="row">
            <div class="card-body">
                <div class="row" >
                    <div class="card" style="background-color:#fafaff; border-color:#e6e6ff;">
                        <div id="London" class="tabcontent">
                            <div class="row">
                                <div class="card-body">
                                    <div class="col-xxl-12 col-lg-12 col-md-12">
                                        <div class="row">
                                        @if($addonDetails->addon_type_name == 'P' || $addonDetails->addon_type_name == 'K')
                                            @include('addon.edit.brandModelLine')
                                        @elseif($addonDetails->addon_type_name == 'SP')
                                            @include('addon.edit.brandModelLineNumber')
                                        @endif
                                        </div> 
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>  
        </div>
    </div>
</div>