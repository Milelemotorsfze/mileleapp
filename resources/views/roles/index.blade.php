<!doctype html>
<html lang="en">
  <head>
    @include('partials/head-css') 
  </head>
  <body data-layout="horizontal" id="closed" window.onclick = "function(closemodal);">
    <div id="layout-wrapper">
      @include('partials/horizontal') 
      <div class="main-content">
        <div class="page-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Role Management</h4>
                    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('roles.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Role</a>
                  </div>
                  <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab1"> 
                      <div class="card-body">
                      <div class="table-responsive">
                        <table id="dtBasicExample1" class="table table-striped table-editable table-edits table">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Name</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <div hidden>{{$i=0;}}</div>
                            @foreach ($roles as $key => $role)
                          <tr data-id="1">
                          <td>{{ ++$i }}</td>
                          <td>{{ $role->name }}</td>
                          <td>
                          <a class="btn btn-sm btn-success"  href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                @can('role-edit')
                                    <a class="btn btn-sm btn-info"  href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                @endcan
                                @can('role-delete')
                                    {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                        <!-- {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!} -->
                                        <a class="btn btn-sm btn-danger"  href="{{ route('roles.destroy',$role->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                    {!! Form::close() !!}
                                @endcan
                            </td>               
                          </tr>
                          @endforeach
                          {!! $roles->render() !!}
                        </tbody>
                      </table>
                    </div>
                  </div>          
                </div>
                <div class="tab-pane fade show" id="tab2">
                  <div class="card-body">  
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @include('partials/footer') 
  </div>
  </div>
    @include('partials/right-sidebar') 
    @include('partials/vendor-scripts') 
    <script src="{{ asset('libs/table-edits/build/table-edits.min.js') }}"></script>
    <script src="{{ asset('js/pages/table-editable.int.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
      $(document).ready(function () {
          $('#dtBasicExample1').DataTable();
      });
    </script>
    <!-- <script>
// When the user clicks on div, open the popup
function myFunctionShow() {
  var popup = document.getElementById("show");
  popup.classList.toggle("show");
  var popup = document.getElementById("edit");
  popup.classList.toggle("hide");
  var popup = document.getElementById("delete");
  popup.classList.toggle("hide");
}
function myFunctionEdit() {
  var popup = document.getElementById("edit");
  popup.classList.toggle("show");
  var popup = document.getElementById("show");
  popup.classList.toggle("hide");
  var popup = document.getElementById("delete");
  popup.classList.toggle("hide");
}
function myFunctionDelete() {
  var popup = document.getElementById("delete");
  popup.classList.toggle("show");
  var popup = document.getElementById("show");
  popup.classList.toggle("hide");
  var popup = document.getElementById("edit");
  popup.classList.toggle("hide");
}
</script> -->
  </body>
</html>