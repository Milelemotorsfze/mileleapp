<!doctype html>
<html lang="en">
  <head>
    @include('partials/head-css') 
    <style>
      .modal-content {
        position:fixed;
        top: 50%;
        left: 50%;
        width:30em;
        height:18em;
        margin-top: -9em; /*set to a negative number 1/2 of your height*/
        margin-left: -15em; /*set to a negative number 1/2 of your width*/
        border: 2px solid #e3e4f1;
        background-color: white;
      }
      .modal-title {
        margin-top: 10px;
        margin-bottom: 5px;
      }
      .modal-paragraph {
        margin-top: 10px;
        margin-bottom: 10px;
        text-align: center;
      }
      .modal-button-class {
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
      }
      .icon-right {
        z-index: 10;  
        position: absolute;  
        right: 0;  
        top: 0;
      }
    </style>
  </head>
  <body data-layout="horizontal" id="closed">
  <div id="layout-wrapper">
      @include('partials/horizontal') 
      <div class="main-content">
        <div class="page-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">
                      Users Info
                        </h4>
                        @can('user-create')
                        <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('users.create') }}" text-align: right>
                            <i class="fa fa-plus" aria-hidden="true"></i> New User
                        </a>
                        @endcan
                        <ul class="nav nav-pills nav-fill">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Active Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab2">Inactive Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="pill" href="#tab3">Deleted Users</a>
                        </li>
                        </ul>      
                  </div>
                  <div class="tab-content">
                    @can('user-list-active')
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
                                  <th>Status</th>
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
                                            <label class="badge badge-soft-info">{{ $v }}</label>
                                          @endforeach
                                        @endif
                                      </td>
                                      <td>
                                        <label class="badge badge-soft-success">
                                          {{ $user->status }}
                                        </label>
                                      </td>
                                      <td>
                                        @can('user-view')
                                          <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('user-edit')
                                          <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        @endcan
                                        @can('user-delete')
                                          <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="deleteActiveUser{{$user->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                          <div class="modal modal-class" id="deleteActiveUser{{$user->id}}" >
                                            <div class="modal-content">
                                              <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                                              <h3 class="modal-title" style="text-align:center;"> Delete Active User </h3>
                                              <div class="dropdown-divider"></div>
                                              <h4 class="modal-paragraph"> Are you sure,</h4>
                                              <h5 class="modal-paragraph"> You want to delete the active user ?</h5>
                                              <div class="dropdown-divider"></div>
                                              <div class="row modal-button-class">                                           
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                  <a href="{{ route('users.delete',$user->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                                </div>
                                              </div>                                          
                                            </div>
                                          </div>
                                        @endcan
                                        @can('user-make-inactive')
                                          <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" data-modal-id="makeInactiveUser{{$user->id}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
                                          <div class="modal modal-class" id="makeInactiveUser{{$user->id}}" >
                                            <div class="modal-content">
                                              <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                                              <h3 class="modal-title" style="text-align:center;"> Make Inactive User </h3>
                                              <div class="dropdown-divider"></div>
                                              <h4 class="modal-paragraph"> Are you sure,</h4>
                                              <h5 class="modal-paragraph"> You want to make inactive ?</h5>
                                              <div class="dropdown-divider"></div>
                                              <div class="row modal-button-class">                                           
                                              <div class="col-xs-6 col-sm-6 col-md-6">
                                                <a href="{{ route('users.updateStatus',$user->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                              </div>
                                            </div>                                          
                                          </div> 
                                        @endcan                              
                                      </td>                
                                    </tr>
                                  @endforeach
                            </tbody>
                          </table>
                        </div>  
                      </div>  
                    </div>  
                    @endcan
                    @can('user-list-inactive')
                    <div class="tab-pane fade show" id="tab2">
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="dtBasicExample2" class="table table-striped table-editable table-edits table">
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
                                      @foreach ($inactive_users as $key => $user)
                                        <tr data-id="1">
                                          <td>{{ ++$i }}</td>
                                          <td>{{ $user->name }}</td>
                                          <td>{{ $user->email }}</td>
                                          <td>
                                            @if(!empty($user->getRoleNames()))
                                              @foreach($user->getRoleNames() as $v)
                                                <label class="badge badge-soft-success">{{ $v }}</label>
                                              @endforeach
                                            @endif
                                          </td>
                                          <td><label class="badge badge-soft-danger">{{ $user->status }}</label></td>
                                          <td>
                                            @can('user-view')
                                            <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                            @endcan
                                            @can(user-edit)
                                              <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            @endcan
                                            @can('user-delete')
                                              <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('users.destroy',$user->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                            @endcan
                                            @can('user-make-active')  
                                              <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-primary modal-button" data-modal-id="makeActiveUser{{$user->id}}"><i class="fa fa-check" aria-hidden="true"></i></a>
                                              <div class="modal modal-class" id="makeActiveUser{{$user->id}}" >
                                                <div class="modal-content">
                                                <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                                                <h3 class="modal-title" style="text-align:center;"> Make Active User </h3>
                                                <div class="dropdown-divider"></div>
                                                <h4 class="modal-paragraph"> Are you sure,</h4>
                                                <h5 class="modal-paragraph"> You want to make active ?</h5>
                                                <div class="dropdown-divider"></div>
                                                <div class="row modal-button-class">                                           
                                                <div class="col-xs-6 col-sm-6 col-md-6">
                                                  <a href="{{ route('users.makeActive', $user->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                                </div>
                                              </div> 
                                            @endcan 
                                          </td>                
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                        </div> 
                      </div>  
                    </div> 
                    @endcan
                    @can('user-list-deleted')
                    <div class="tab-pane fade show" id="tab3">
                      <div class="card-body">
                        <div class="table-responsive">
                          <table id="dtBasicExample3" class="table table-striped table-editable table-edits table">
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
                                        @foreach ($deleted_users as $key => $user)
                                          <tr data-id="1">
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                              @if(!empty($user->getRoleNames()))
                                                @foreach($user->getRoleNames() as $v)
                                                  <label class="badge badge-soft-success">{{ $v }}</label>
                                                @endforeach
                                              @endif
                                            </td>
                                            <td><label class="badge badge-soft-danger">deleted</label></td>
                                            <td>
                                              @can('user-view')
                                                <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                              @endcan
                                              @can('user-edit')
                                                <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                              @endcan
                                              @can('user-delete')
                                                <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger" href="{{ route('users.destroy',$user->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                                              @endcan
                                              @can('user-restore')
                                                <a data-toggle="popover" data-trigger="hover" title="Restore" data-placement="top" class="btn btn-sm btn-primary modal-button" data-modal-id="restoreUser{{$user->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>
                                                <div class="modal modal-class" id="restoreUser{{$user->id}}" >
                                                  <div class="modal-content">
                                                    <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                                                    <h3 class="modal-title" style="text-align:center;"> Make Active User </h3>
                                                    <div class="dropdown-divider"></div>
                                                    <h4 class="modal-paragraph"> Are you sure,</h4>
                                                    <h5 class="modal-paragraph"> You want to make active ?</h5>
                                                    <div class="dropdown-divider"></div>
                                                    <div class="row modal-button-class">                                           
                                                    <div class="col-xs-6 col-sm-6 col-md-6">
                                                      <a href="{{ route('users.restore', $user->id) }}" style="float: right;" class="btn btn-sm btn-success "><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>        
                                                    </div>
                                                  </div>                                          
                                                </div>
                                              @endcan
                                            </td>                
                                          </tr>
                                        @endforeach
                                      </tbody>
                                    </table>
                        </div>
                      </div>
                    </div>
                    @endcan
                  </div><!-- end tab-content-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('partials/right-sidebar') 
    @include('partials/vendor-scripts') 
    <script src="{{ asset('libs/table-edits/build/table-edits.min.js')}}"></script>
    <script src="{{ asset('js/pages/table-editable.int.js')}}"></script>
    <script src="{{ asset('js/app.js')}}"></script>
    <script src="{{ asset('libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script>
      $(document).ready(function () 
      {
        $('#dtBasicExample1').DataTable();
        $('#dtBasicExample2').DataTable();
        $('#dtBasicExample3').DataTable();
        $('.modal-button').on('click', function()
        {
          var modalId = $(this).data('modal-id');
          $('#' + modalId).addClass('modalshow');
          $('#' + modalId).removeClass('modalhide');
        });
        $('.close').on('click', function()
        {
          $('.modal').addClass('modalhide');
          $('.modal').removeClass('modalshow');
        });
      });
      function closemodal()
      {    
        $('.modal').removeClass('modalshow');
        $('.modal').addClass('modalhide');
      }
    </script>      
  </body>
</html>