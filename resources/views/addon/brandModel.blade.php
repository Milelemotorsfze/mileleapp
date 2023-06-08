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
<div class="card">
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
                                           @include('addon.brandModelLine')
                                           @include('addon.brandModelLineNumber')
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