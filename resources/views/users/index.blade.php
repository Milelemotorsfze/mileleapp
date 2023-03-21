<!doctype html>
<html lang="en">
  <head>
    @include('partials/head-css') 
    <!-- <style>
      a .create-new
      {
        text-align: right;
      }
    </style> -->
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
                    <a class="btn btn-sm btn-success" href="" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New User</a>
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
                            <a class="btn btn-sm btn-success" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-info" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                            <a class="btn btn-sm btn-danger" href="{{ route('users.destroy',$user->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
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
  </body>
</html>