@extends('layouts.table')
@section('content')
@canany(['user-create','user-list-active','user-list-inactive','user-list-deleted'])
@php
$hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-create','user-list-active','user-list-inactive','user-list-deleted']);
@endphp
@if ($hasPermission)
<div class="card-header">
   <h4 class="card-title">
      Users Info
   </h4>
   @can('user-create')
   @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-create']);
   @endphp
   @if ($hasPermission)
   <a class="btn btn-sm btn-success float-end" href="{{ route('users.create') }}" text-align: right>
   <i class="fa fa-plus" aria-hidden="true"></i> New User
   </a>
   <p class="float-end">&nbsp;&nbsp;&nbsp;</p>
 
   <!-- <a class="btn btn-sm btn-success float-end" href="{{ route('sales_person_languages.create') }}" text-align: right>
      <i class="fa fa-plus" aria-hidden="true"></i> Languages
      </a> -->
   <div class="clearfix"></div>
   <br>
   @endif
   @endcan
   @canany(['user-list-active','user-list-inactive','user-list-deleted'])
   @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-active','user-list-inactive','user-list-deleted']);
   @endphp
   @if ($hasPermission)
   <ul class="nav nav-pills nav-fill">
      @can('user-list-active')
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-active']);
      @endphp
      @if ($hasPermission)
      <li class="nav-item">
         <a class="nav-link active" data-bs-toggle="pill" href="#tab1">Active Users</a>
      </li>
      <li class="nav-item">
         <a class="nav-link" data-bs-toggle="pill" href="#tab4">System Access Request</a>
      </li>
      @endif
      @endcan
      @can('user-list-inactive')
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-inactive']);
      @endphp
      @if ($hasPermission)
      <li class="nav-item">
         <a class="nav-link" data-bs-toggle="pill" href="#tab2">Inactive Users</a>
      </li>
      @endif
      @endcan
      @can('user-list-deleted')
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-deleted']);
      @endphp
      @if ($hasPermission)
      <li class="nav-item">
         <a class="nav-link" data-bs-toggle="pill" href="#tab3">Deleted Users</a>
      </li>
      @endif
      @endcan
   </ul>
   @endif
   @endcanany
</div>
<div class="tab-content">
   @can('user-list-active')
   @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-active']);
   @endphp
   @if ($hasPermission)
   <div class="tab-pane fade show active" id="tab1">
      <div class="card-body">
         <div class="table-responsive">
            <table id="activeUsersList" class="table table-striped table-editable table-edits table">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Role</th>
                     <th>Status</th>
                     <th>Is Sales Rep.</th>
                     <th>Can Send WO Email</th>
                     <th>Manual Lead Assign</th>
                     <th>PFI/Quotation</th>
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
                        {{ $user->is_sales_rep ?? '' }}
                     </td>
                     <td>
                        {{ $user->can_send_wo_email ?? '' }}
                     </td>
                     <td>
                        {{ $user->manual_lead_assign == 1 ? 'Yes' : 'No' }}
                     </td>
                     <td>
                        {{ $user->pfi_access == 1 ? 'Yes' : 'No'  }}
                     </td>
                     <td>
                        @can('user-view')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-view']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" 
                           href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-edit')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-edit']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" 
                           href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-delete')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-delete']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" 
                           data-modal-id="deleteActiveUser{{$user->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
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
                                    <a href="{{ route('users.delete',$user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                    <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @endcan
                        @can('user-make-inactive')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-make-inactive']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" 
                           data-modal-id="makeInactiveUser{{$user->id}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
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
                                    <a href="{{ route('users.updateStatus',$user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                    <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @endcan                              
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   <div class="tab-pane fade" id="tab4">
      <div class="card-body">
         <div class="table-responsive">
            <table id="requestUsersList" class="table table-striped table-editable table-edits table my-datatable">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Role</th>
                     <th>Status</th>
                     <th>Is Sales Rep.</th>
                     <th>Can Send WO Email</th>
                     <th>Manual Lead Assign</th>
                     <th>PFI/Quotation</th>
                     <th>Action</th>
                  </tr>
               </thead>
               <tbody>
                  <div hidden>{{$i=0;}}</div>
                  @foreach ($accessRequests as $key => $user)
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
                        {{ $user->is_sales_rep ?? '' }}
                     </td>
                     <td>
                        {{ $user->can_send_wo_email ?? '' }}
                     </td>
                     <td>
                        {{ $user->manual_lead_assign == 1 ? 'Yes' : 'No' }}
                     </td>
                     <td>
                        {{ $user->pfi_access == 1 ? 'Yes' : 'No'  }}
                     </td>
                     <td>
                     @php
                     $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-create']);
                     @endphp
                     @if ($hasPermission)
                        <a class="btn btn-sm btn-warning" href="{{ route('users.createLogin',$user->id) }}">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        </a>
                     @endif
                        @can('user-view')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-view']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" 
                           href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-edit')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-edit']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" 
                           href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-delete')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-delete']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" 
                           data-modal-id="deleteActiveUser{{$user->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
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
                                    <a href="{{ route('users.delete',$user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                    <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @endcan
                        @can('user-make-inactive')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-make-inactive']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Make Inactive" data-placement="top" class="btn btn-sm btn-secondary modal-button" 
                           data-modal-id="makeInactiveUser{{$user->id}}"><i class="fa fa-ban" aria-hidden="true"></i></a>
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
                                    <a href="{{ route('users.updateStatus',$user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                    <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @endcan                              
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   @endif 
   @endcan
   @can('user-list-inactive')
   @php
   $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-inactive']);
   @endphp
   @if ($hasPermission)
   <div class="tab-pane fade show" id="tab2">
      <div class="card-body">
         <div class="table-responsive">
            <table id="inactiveUsersList" class="table table-striped table-editable table-edits table">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Name</th>
                     <th>Email</th>
                     <th>Role</th>
                     <th>Status</th>
                     <th>Is Sales Rep.</th>
                     <th>Can Send WO Email</th>
                     <th>Manual Lead Assign</th>
                     <th>PFI/Quotation</th>
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
                        {{ $user->is_sales_rep ?? '' }}
                     </td>
                     <td>
                        {{ $user->can_send_wo_email ?? '' }}
                     </td>
                     <td>
                        {{ $user->manual_lead_assign == 1 ? 'Yes' : 'No' }}
                     </td>
                     <td>
                        {{ $user->pfi_access == 1 ? 'Yes' : 'No'  }}
                     </td>
                     <td>
                        @can('user-view')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-view']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" 
                           href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-edit')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-edit']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" 
                           href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                        @endif
                        @endcan
                        @can('user-delete')
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-delete']);
                        @endphp
                        @if ($hasPermission)
                        <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger modal-button" 
                           data-modal-id="deleteInactiveUser{{$user->id}}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                        <div class="modal modal-class" id="deleteInactiveUser{{$user->id}}" >
                           <div class="modal-content">
                              <i class="fa fa-times icon-right" aria-hidden="true" onclick="closemodal()"></i>
                              <h3 class="modal-title" style="text-align:center;"> Delete Inactive User </h3>
                              <div class="dropdown-divider"></div>
                              <h4 class="modal-paragraph"> Are you sure,</h4>
                              <h5 class="modal-paragraph"> You want to delete the inaactive user ?</h5>
                              <div class="dropdown-divider"></div>
                              <div class="row modal-button-class">
                                 <div class="col-xs-6 col-sm-6 col-md-6">
                                    <a href="{{ route('users.delete',$user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                    <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                        @endif
                        @endcan
                        @can('user-make-active') 
                        @php
                        $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-make-active']);
                        @endphp
                        @if ($hasPermission) 
                        <a data-toggle="popover" data-trigger="hover" title="Make Active" data-placement="top" class="btn btn-sm btn-primary modal-button" 
                           data-modal-id="makeActiveUser{{$user->id}}"><i class="fa fa-check" aria-hidden="true"></i></a>
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
                           </div>
                           @endif
                           @endcan 
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
            </div> 
         </div>
      </div>
      @endif
      @endcan
      @can('user-list-deleted')
      @php
      $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-list-deleted']);
      @endphp
      @if ($hasPermission)
      <div class="tab-pane fade show" id="tab3">
         <div class="card-body">
            <div class="table-responsive">
               <table id="deletedUsersList" class="table table-striped table-editable table-edits table">
                  <thead>
                     <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Is Sales Rep.</th>
                        <th>Can Send WO Email</th>
                        <th>Manual Lead Assign</th>
                        <th>PFI/Quotation</th>
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
                        <td>{{ $user->is_sales_rep ?? '' }}</td>
                        <td>
                        {{ $user->can_send_wo_email ?? '' }}
                     </td>
                     <td>
                        {{ $user->manual_lead_assign == 1 ? 'Yes' : 'No' }}
                     </td>
                     <td>
                        {{ $user->pfi_access == 1 ? 'Yes' : 'No'  }}
                     </td>
                        <td>
                           @can('user-view')
                           @php
                           $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-view']);
                           @endphp
                           @if ($hasPermission)
                           <a data-toggle="popover" data-trigger="hover" title="View" data-placement="top" class="btn btn-sm btn-success" 
                              href="{{ route('users.show',$user->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a>
                           @endif
                           @endcan
                           @can('user-edit')
                           @php
                           $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-edit']);
                           @endphp
                           @if ($hasPermission)
                           <a data-toggle="popover" data-trigger="hover" title="Edit" data-placement="top" class="btn btn-sm btn-info" 
                              href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit" aria-hidden="true"></i></a>
                           @endif
                           @endcan
                           <!-- @can('user-delete')
                           @php
                           $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-delete']);
                           @endphp
                           @if ($hasPermission)
                           <a data-toggle="popover" data-trigger="hover" title="Delete" data-placement="top" class="btn btn-sm btn-danger" 
                              href="{{ route('users.destroy',$user->id) }}"> <i class="fa fa-trash" aria-hidden="true"></i></a>
                           @endif
                           @endcan -->
                           @can('user-restore')
                           @php
                           $hasPermission = Auth::user()->hasPermissionForSelectedRole(['user-restore']);
                           @endphp
                           @if ($hasPermission)
                           <a data-toggle="popover" data-trigger="hover" title="Restore" data-placement="top" class="btn btn-sm btn-primary modal-button" 
                              data-modal-id="restoreUser{{$user->id}}"><i class="fa fa-undo" aria-hidden="true"></i></a>
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
                                       <a href="{{ route('users.restore', $user->id) }}" style="float: right;" class="btn btn-sm btn-success ">
                                       <i class="fa fa-check" aria-hidden="true"></i> Confirm</a>        
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
                           @endcan
                        </td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
         </div>
      </div>
      @endif
      @endcan
   </div>
   <!-- end tab-content-->
</div>
</div>
@endif
@endcanany
<script type="text/javascript">
   $(document).ready(function ()
   {
     $('#activeUsersList').DataTable();
     $('#activeUsersList').on( 'click', '.modal-button', function () {
                var modalId = $(this).data('modal-id');
                ModalOpen(modalId);
            });
            function ModalOpen(modalId) {
                $('#' + modalId).addClass('modalshow');
                $('#' + modalId).removeClass('modalhide');
            }
     $('#inactiveUsersList').DataTable();
     $('#inactiveUsersList').on( 'click', '.modal-button', function () {
                var modalId = $(this).data('modal-id');
                ModalOpen(modalId);
            });
     $('#deletedUsersList').DataTable();
     $('#deletedUsersList').on( 'click', '.modal-button', function () {
                var modalId = $(this).data('modal-id');
                ModalOpen(modalId);
            });
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
<!-- <style>
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
   </style> -->
