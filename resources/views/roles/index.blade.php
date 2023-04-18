@extends('layouts.table')
@section('content')
  <div class="card-header">
    <h4 class="card-title">Role Management</h4>
    @can('role-create')
      <a style="float: right;" class="btn btn-sm btn-success" href="{{ route('roles.create') }}" text-align: right><i class="fa fa-plus" aria-hidden="true"></i> New Role</a>
    @endcan
  </div>
  @can('role-list')
    <div class="card-body">
      <div class="table-responsive">
        <table id="dtBasicExample" class="table table-striped table-editable table-edits table">
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
                  <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" href="{{ route('roles.show',$role->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                  @can('role-edit')
                    <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                  @endcan
                  @can('role-delete')
                    <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" data-modal-id="deleterole{{$role->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                    <div class="modal modal-class" id="deleterole{{$role->id}}" >
                      <div class="modal-content">
                        <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                        <h3 class="modal-title" style="text-align:center;"> Delete role </h3>
                        <div class="dropdown-divider"></div>
                        <h4 class="modal-paragraph"> Are you sure,</h4>
                        <h5 class="modal-paragraph"> You want to delete the role ?</h5>
                        <div class="dropdown-divider"></div>
                        <div class="row modal-button-class">                                           
                          <div class="col-xs-6 col-sm-6 col-md-6">
                            <a href="{{ route('roles.delete',$role->id) }}" style="float: right;" class="btn btn-sm btn-success btn-move-right"><i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                          </div>
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
  @endcan
  <script type="text/javascript">
    $(document).ready(function ()
    {
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
@endsection
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
