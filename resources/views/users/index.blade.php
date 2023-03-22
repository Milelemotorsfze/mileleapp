<!doctype html>
<html lang="en">
  <head>
    @include('partials/head-css') 
    <style>
/* Popup container - can be anything you want */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 100px;
  background-color: #555;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}
</style>
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
                    <h4 class="card-title">Users</h4>
                    <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('users.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New User</a>
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
                              <th>Email</th>
                              <th>Role</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <div hidden>{{$i=0;}}</div>
                            @foreach ($data as $key => $user)
                          <tr data-id="1">
                          <td>{{ ++$i }}</td>
                          <td>{{ $user->name }}</td>
                          <td>{{ $user->email }}</td>
                          <td>
                            @if(!empty($user->getRoleNames()))
                              @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                              @endforeach
                            @endif
                          </td>
                          <td>
                            <a class="btn btn-sm btn-success popup" onmouseover="myFunctionShow()" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i><span class="popuptext" id="show">Show</span></a>
                            <a class="btn btn-sm btn-info popup" onmouseover="myFunctionEdit()" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i><span class="popuptext" id="edit">Edit</span></a>
                            <a class="btn btn-sm btn-danger popup" onmouseover="myFunctionDelete()" href="{{ route('users.destroy',$user->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i><span class="popuptext" id="delete">Delete</span></a>
                          </td>                
                          </tr>
                          @endforeach
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