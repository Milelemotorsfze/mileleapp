@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">
      Supplier Addon prices Info
    </h4>
   
       
  </div>
  <div class="tab-content">
      <div class="tab-pane fade show active" id="tab1"> 
        <div class="card-body">
          <div class="table-responsive">
            <table id="supplierAddonPrices" class="table table-striped table-editable table-edits table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <div hidden>{{$i=0;}}</div>
                @foreach ($supplierAddons as $key => $user)
                  <tr data-id="1">
                    <td>{{ ++$i }}</td>
                        
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>  
        </div>  
      </div>  

   
    
      </div><!-- end tab-content-->
    </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function ()
    {
      $('#supplierAddonPrices').DataTable();
    });
   
  </script>
@endsection

   
