@extends('layouts.main')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/css/intlTelInput.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/intlTelInput.min.js"></script>
<style>
  input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    -moz-appearance: none;
    appearance: none; 
    margin: 0; 
}
   .card-img-top
   {
    height: 300px;
    width: 300px;
   } 
   .tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }
  
  .tag {
    background-color: #f1f1f1;
    color: #333;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
  }
  .card {
  position: relative;
  overflow: hidden;
}

.card-overlay {
  position: relative;
}

.card-overlay::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: inherit;
  filter: blur(5px);
  opacity: 0;
  transition: opacity 0.3s ease;
}

.card-overlay:hover::before {
  opacity: 1;
}

.edit-button {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  display: flex;
  justify-content: center;
  align-items: center;
}

.edit-button .btn {
  background-color: red; /* Replace with your desired color */
  border-color: #FF0000; /* Replace with your desired color */
  color: #FFFFFF; /* Replace with your desired color */
  opacity: 0;
  transition: opacity 0.3s ease;
}

.card-overlay:hover .edit-button .btn {
  opacity: 1;
}
.upload-container {
  text-align: center;
  margin-bottom: 20px;
}

#drag-drop-area {
  border: 2px dashed #ccc;
  padding: 20px;
  cursor: pointer;
}

#image-preview img {
  max-width: 100%;
  max-height: 200px;
  margin-top: 10px;
}
    </style>
@section('content')
  <div class="col-lg-12">
    <div class="row">
    <div class="col-md-3">
  <div class="card mt-3 d-flex justify-content-center align-items-center">
    <div class="card-overlay">
      @if ($emp_profile->image_path)
      <img src="{{ asset($emp_profile->image_path) }}" class="card-img-top" alt="User">
      @else
        <div class="rounded-circle bg-primary text-white text-center" style="width: 300px; height: 300px; font-size: 100px; display: flex; align-items: center; justify-content: center;">
          {{ strtoupper(substr($emp_profile->first_name, 0, 1) . substr($emp_profile->last_name, 0, 1)) }}
        </div>
      @endif
      <div class="edit-button">
      <a href="#" class="edit-btn" data-bs-toggle="modal" data-bs-target="#PicturesModal">
  <button class="btn btn-success edit-btn">
    <i class="fas fa-camera"></i>
  </button>
</a>
</div>
    </div>
  </div>
  <div class="modal fade" id="PicturesModal" tabindex="-1" aria-labelledby="PicturesModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="PicturesModal">Edit Picture Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="upload-container">
          <input type="file" id="upload-input" accept="image/*" style="display: none">
          <div id="drag-drop-area" ondragover="handleDragOver(event)" ondrop="handleDrop(event)">
            <p>Drag and drop an image here or</p>
            <button type="button" class="btn btn-primary" onclick="browseFiles()">Browse</button>
          </div>
          <div id="image-preview"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="savePicturesChanges()">Save Changes</button>
      </div>
    </div>
  </div>
</div>
        <div class="card mt-3">
          <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Languages</h5>
    </div>
    @foreach($emp_languages as $emp_languages)
            <p class="mt-4 mb-1" style="font-size: 1rem;">{{$emp_languages->language}}</p>
            <div class="progress rounded mb-2" style="height: 25px;">
            <div class="progress-bar" role="progressbar" style="width: {{$emp_languages->percentage}}%" aria-valuenow="{{$emp_languages->percentage}}" aria-valuemin="0" aria-valuemax="100">{{$emp_languages->percentage}} %</div>
            </div>
            @endforeach
          </div>
        </div>
        <div class="card mt-3">
        <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Skills</h5>
    </div>
    @foreach ($emp_skills as $emp_skills)
    <p id="skillName" class="existing-skill-name" style="font-size: 1rem;" data-skill-name="{{$emp_skills->name}}">{{$emp_skills->name}}</p>
    <div class="progress rounded mb-1" style="height: 25px;">
    <div id="skillPercentage" class="progress-bar" role="progressbar" style="width: {{$emp_skills->percentage}}%" aria-valuenow="{{$emp_skills->percentage}}" aria-valuemin="0" aria-valuemax="100" data-skill-percentage="{{$emp_skills->percentage}}"> {{$emp_skills->percentage}} %</div>
    </div>
    <div class="text-end">
    <a href="#" onclick="confirmSkillDelete('{{ route('profile.skillDocument', ['id' => $emp_skills->id]) }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
    </div>
            @endforeach
          </div>
         <div class="text-center">
      <a href="#" data-bs-toggle="modal" data-bs-target="#skillsModal" class="btn btn-success" href="#"><i class="fas fa-plus"></i> Add New</a>
    </div>
    </div>
<div class="modal fade" id="skillsModal" tabindex="-1" aria-labelledby="skillsModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="skillsModal">Add New Skills</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">Skill Name</label>
        <input type="text" class="form-control" id="skill_name" name="skill_name" placeholder="Enter Skill name" required>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="Skill">Skill Percentage</label>
        <select id = "skill_percentage" class="form-control">
        <option value="10">10%</option>
        <option value="20">20%</option>
        <option value="30">30%</option>
        <option value="40">40%</option>
        <option value="50">50%</option>
        <option value="60">60%</option>
        <option value="70">70%</option>
        <option value="80">80%</option>
        <option value="90">90%</option>
        <option value="100">100%</option>
        </select>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="emp_profile_id" value="{{$emp_profile->id}}">
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary" onclick="saveSkillChanges()">Save Changes</button>
</div>
    </div>
  </div>
</div>
      </div>
      <div class="col-md-5">
      <div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Basic Information</h5>
      <a href="#" class="edit-icon" onclick="openEditModal()" data-bs-toggle="modal" data-bs-target="#languagesModal">
        <i class="fas fa-edit"></i>
      </a>
    </div>
    <div class="row">
      <div class="col-md-4">
        Full Name:
      </div>
      <div class="col-md-8">
        {{$emp_profile->first_name}} {{$emp_profile->last_name}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Gender:
      </div>
      <div class="col-md-8">
        {{$emp_profile->gender}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Nationality:
      </div>
      <div class="col-md-8">
        {{$emp_profile->nationality}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Religion:
      </div>
      <div class="col-md-8">
        {{$emp_profile->religion}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Passport Number:
      </div>
      <div class="col-md-8">
        {{$emp_profile->passport_number}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Passport Issue Date:
      </div>
      <div class="col-md-8">
        {{$emp_profile->passport_issue_date}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Passport Expiry Date:
      </div>
      <div class="col-md-8">
        {{$emp_profile->passport_expiry_date}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Contact Number:
      </div>
      <div class="col-md-8">
        {{$emp_profile->contact_number}}
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Company Number:
      </div>
      <div class="col-md-8">
        {{$emp_profile->company_number}}
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="languagesModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="languagesModalLabel">Edit Basic Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row mb-3">
  <div class="col-md-4">
    <label for="full-name" class="form-label">Full Name:</label>
  </div>
  <div class="col-md-8">
    <input type="text" class="form-control" id="full-name" value="{{$emp_profile->first_name}} {{$emp_profile->last_name}}">
  </div>
</div>
<div class="row mb-3">
  <div class="col-md-4">
    <label for="gender" class="form-label">Gender:</label>
  </div>
  <div class="col-md-8">
    <select class="form-select" id="gender">
      <option value="male" {{$emp_profile->gender === 'male' ? 'selected' : ''}}>Male</option>
      <option value="female" {{$emp_profile->gender === 'female' ? 'selected' : ''}}>Female</option>
    </select>
  </div>
</div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="full-name" class="form-label"> Nationality:</label>
          </div>
          <div class="col-md-8">
          <input type="text" placeholder="Location" name="location" list="loList" class="form-control" id="locationInput" value="{{$emp_profile->nationality}}">
                    <datalist id="loList">
                    @foreach ($countries as $country)
                    <option value="{{ $country }}" data-value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                    </datalist>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="gender" class="form-label">Religion:</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="religion" value="{{$emp_profile->religion}}">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="gender" class="form-label">Passport Number:</label>
          </div>
          <div class="col-md-8">
            <input type="text" class="form-control" id="passport-number" value="{{$emp_profile->passport_number}}">
          </div>
        </div>
        <div class="row mb-3">
  <div class="col-md-4">
    <label for="passport-issue-date" class="form-label">Passport Issue Date:</label>
  </div>
  <div class="col-md-8">
    <input type="date" class="form-control" id="passport-issue-date" value="{{$emp_profile->passport_issue_date}}">
  </div>
</div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="gender" class="form-label">Passport Expiry Date:</label>
          </div>
          <div class="col-md-8">
            <input type="date" class="form-control" id="passport-expiry-date" value="{{$emp_profile->passport_expiry_date}}">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="gender" class="form-label"> Contact Number:</label>
          </div>
          <div class="col-md-8">
            <input type="tel" class="form-control" id="contact-number" value="{{$emp_profile->contact_number}}">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-4">
          <label for="gender" class="form-label">Company Number:</label>
          </div>
          <div class="col-md-8">
            <input type="tel" class="form-control" id="company-number" value="{{$emp_profile->company_number}}">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="saveBasicInfo()">Save Changes</button>
      </div>
    </div>
  </div>
</div>
        <div class="card mt-3">
          <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Visa Information</h5>
    </div>
            <div class="row">
        <div class="col-md-4">
        Visa Type :
        </div>
        <div class="col-md-8">
        {{$emp_profile->visa_type}}
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Visa Status :
        </div>
        <div class="col-md-8">
        {{$emp_profile->visa_status}}
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Emirates Number :
        </div>
        <div class="col-md-8">
        {{$emp_profile->emirates_expiry}}
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Emirates Issue :
        </div>
        <div class="col-md-8">
        {{$emp_profile->emirates_expiry}}
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Emirates Expiry :
        </div>
        <div class="col-md-8">
        {{$emp_profile->emirates_expiry}}
        </div>
      </div>
          </div>
        </div>
        <div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Documents</h5>
    </div>
    @if(!empty($emp_doc))
      <table class="table">
        <thead>
          <tr>
            <th>Document Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($emp_doc as $doc)
            <tr>
              <td>{{$doc->document_name}}</td>
              <td>
              <a class="btn btn-sm btn-primary" href="{{$doc->document_path}}" target="_blank">
                  <i class="fas fa-eye"></i> View
                </a>
                <a class="btn btn-sm btn-danger" href="#" onclick="confirmDelete('{{ route('profile.deleteDocument', ['id' => $doc->id]) }}')">
                <i class="fas fa-trash"></i> Delete
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>No documents found.</p>
    @endif
    <div class="text-end">
      <a href="#" data-bs-toggle="modal" data-bs-target="#DocumentModal" class="btn btn-success" href="#"><i class="fas fa-plus"></i> Add New</a>
    </div>
  </div>
</div>
<div class="modal fade" id="DocumentModal" tabindex="-1" aria-labelledby="DocumentModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DocumentModal">Add New Documents</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">Document Name</label>
        <input type="text" class="form-control" id="document_name" name="document_name" placeholder="Enter document name">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_file">Upload Document</label>
        <input type="file" class="form-control-file" id="document_file" name="document_file">
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary" onclick="saveDocumentChanges()">Save Changes</button>
</div>
    </div>
  </div>
</div>
        </div>
        <div class="col-md-4">
        <div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Login Information</h5>
      <a href="#" class="edit-icon" onclick="openEditModallogin()" data-bs-toggle="modal" data-bs-target="#loginModal">
        <i class="fas fa-edit"></i>
      </a>
    </div>
    <div class="row">
      <div class="col-md-4">
        Email:
      </div>
      <div class="col-md-8">
        <span id="email">{{$user->email}}</span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        Password:
      </div>
      <div class="col-md-8">
        <span id="password">{{ str_repeat('*', strlen($user->password)) }}</span>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Edit Login Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row mb-3">
  <div class="col-md-4">
    <label for="emails" class="form-label">Email::</label>
  </div>
  <div class="col-md-8">
    <input type="text" class="form-control" id="fullemail" value="{{$user->email}}" readonly>
  </div>
</div>
<div class="row mb-3">
  <div class="col-md-4">
    <label for="password" class="form-label">Old Password:</label>
  </div>
  <div class="col-md-8">
  <input type="text" class="form-control" id="oldpassword" value="">
  </div>
</div>
<div class="row mb-3">
  <div class="col-md-4">
    <label for="password" class="form-label">New Password:</label>
  </div>
  <div class="col-md-8">
  <input type="text" class="form-control" id="newpassword" value="">
  </div>
</div>
<div class="row mb-3">
  <div class="col-md-4">
    <label for="password" class="form-label">Confirm Password:</label>
  </div>
  <div class="col-md-8">
  <input type="text" class="form-control" id="confirmpassword" value="">
  </div>
</div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary" onclick="savelogins()">Save Changes</button>
</div>
    </div>
  </div>
</div>
        <div class="card mt-3">
    <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Job Information</h5>
    </div>
      <div class="row">
        <div class="col-md-4">
          Department :
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->department}}
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          Joining Date :
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->joining_date}}
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Employee Type :
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->employee_type}}
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Reported To:
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->report_to}}
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Reported Designation:
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->reported_designation}}
        @endif
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
        Office Address :
        </div>
        <div class="col-md-8">
        @if ($emp_job)
        {{$emp_job->office_address}}
        @endif
        </div>
      </div>
    </div>
  </div>
  <div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Core Responsibility</h5>
    </div>
    <div class="tags">
      @foreach($core_responsibities as $core_responsibities)
      <span class="tag">{{$core_responsibities->name}}</span>
      @endforeach
    </div>
  </div>
</div>
<div class="card mt-3">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center">
      <h5 class="card-title">Work History</h5>
    </div>
    <table class="table">
      <thead>
        <tr>
          <th>Company</th>
          <th>Designation</th>
          <th>Location</th>
          <th>Employment Period</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($working_history as $working_history)
        <tr>
          <td>{{$working_history->company_name}}</td>
          <td>{{$working_history->designation}}</td>
          <td>{{$working_history->location}}</td>
          <td>{{ \Carbon\Carbon::parse($working_history->fromdate)->format('M y') }} - {{ \Carbon\Carbon::parse($working_history->todate)->format('M y') }}</td>
          <td>
            <div class="text-end">
              <a href="#" onclick="confirmHistoryDelete('{{ route('profile.historydelete', ['id' => $working_history->id]) }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    <div class="text-end">
      <a href="#" data-bs-toggle="modal" data-bs-target="#historyModal" class="btn btn-success" href="#"><i class="fas fa-plus"></i> Add New</a>
    </div>
  </div>
</div>
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="historyModal">Add Working History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">Company Name</label>
        <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Enter Company name">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">Designation</label>
        <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">Location</label>
        <input type="text" class="form-control" id="location" name="location" placeholder="Enter Location">
      </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">To Date</label>
        <input type="date" class="form-control" id="todate" name="todate" placeholder="Enter document name">
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label for="document_name">From Date</label>
        <input type="date" class="form-control" id="fromdate" name="fromdate" placeholder="Enter document name">
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="emp_profile_id" value="{{$emp_profile->id}}">
<div class="modal-footer">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
  <button type="button" class="btn btn-primary" onclick="savehistoryChanges()">Save Changes</button>
</div>
    </div>
  </div>
</div>
        </div>
		 <div class="col-md-5">
        </div>
		<div class="col-md-4">
        
        </div>
		<div class="col-md-4">
        
      </div>
    </div>
  </div>
@endsection
@push('scripts')
<script>
  const input = document.getElementById('fullpassword');
    const originalValue = "{{ $user->password }}";
    
    input.addEventListener('input', function() {
        input.value = this.value !== originalValue ? this.value : originalValue;
    });
function openEditModal() {
  var modal = new bootstrap.Modal(document.getElementById('languagesModal'));
    modal._element.addEventListener('hidden.bs.modal', function (event) {
      var backdrop = document.querySelector('.modal-backdrop');
      if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
      }
    });
    
    modal.show();
}
function openEditModallogin() {
  var modal = new bootstrap.Modal(document.getElementById('loginModal'));
    modal._element.addEventListener('hidden.bs.modal', function (event) {
      var backdrop = document.querySelector('.modal-backdrop');
      if (backdrop) {
        backdrop.parentNode.removeChild(backdrop);
      }
    });
    
    modal.show();
}
function openEditModalpicture() {
  $('#PicturesModal').modal('show');
}
function openEditModalskills() {
  $('#skillsModal').modal('show');
}
function saveBasicInfo() {
  var fullName = document.getElementById('full-name').value;
  var gender = document.getElementById('gender').value;
  var nationality = document.getElementById('locationInput').value;
  var religion = document.getElementById('religion').value;
  var passportNumber = document.getElementById('passport-number').value;
  var passportIssueDate = document.getElementById('passport-issue-date').value;
  var passportExpiryDate = document.getElementById('passport-expiry-date').value;
  var contactNumber = document.getElementById('contact-number').value;
  var companyNumber = document.getElementById('company-number').value;
  if (passportIssueDate >= passportExpiryDate) {
      alert("Passport issue date must be earlier than the expiry date.");
      return;
    }
  var updatedInfo = {
    fullName: fullName,
    gender: gender,
    nationality: nationality,
    religion: religion,
    passportNumber: passportNumber,
    passportIssueDate: passportIssueDate,
    passportExpiryDate: passportExpiryDate,
    contactNumber: contactNumber,
    companyNumber: companyNumber
  };
  $.ajax({
    url: '{{ route('profile.updateLoginInfo') }}',
    type: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      updatedInfo: updatedInfo
    },
    success: function(response) {
      var modal = new bootstrap.Modal(document.getElementById('languagesModal'));
      modal.hide();
      location.reload();
    },
    error: function(xhr) {
    }
  });
}
  function savelogins() {
    var oldPassword = $("#oldpassword").val();
    var newPassword = $("#newpassword").val();
    var confirmPassword = $("#confirmpassword").val();

    // Check if the new password and confirm password match
    if (newPassword !== confirmPassword) {
      alert("New password and confirm password do not match.");
      return;
    }

    // Make an AJAX request to check if the old password is correct and update the password in the database
    $.ajax({
      type: "POST",
      headers: {
    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
  },
      url: '{{ route('profile.updateEmailInfo') }}', // Replace with the appropriate endpoint in your server-side code
      data: {
        oldPassword: oldPassword,
        newPassword: newPassword
      },
      success: function (response) {
        if (response.success) {
          alert("Password updated successfully.");
          window.location.href = "/";
        } else {
          if (response.error === "incorrect_old_password") {
            alert("Old password is incorrect.");
          } else {
            alert("Failed to update password. Please try again.");
          }
        }
      },
      error: function () {
        alert("Failed to update password. Please try again.");
      }
    });
  }
function browseFiles() {
  document.getElementById('upload-input').click();
}

function handleFiles(files) {
  const file = files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(event) {
      const imagePreview = document.getElementById('image-preview');
      imagePreview.innerHTML = '<img src="' + event.target.result + '">';
    };
    reader.readAsDataURL(file);
  }
}
function savePicturesChanges() {
  const fileInput = document.getElementById('upload-input');
  const file = fileInput.files[0];
  if (!file) {
    alert('No file selected!');
    return;
  }

  const formData = new FormData();
  formData.append('picture', file);

  const xhr = new XMLHttpRequest();
  xhr.open('POST', '{{ route('profile.updatepictureInfo') }}', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        window.location.reload();
      } else {
        alert('Error occurred while saving the picture.');
      }
    }
  };
  xhr.send(formData);
}

function handleDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = 'copy';
}

function handleDrop(event) {
  event.preventDefault();
  const files = event.dataTransfer.files;
  handleFiles(files);
}

document.getElementById('upload-input').addEventListener('change', function(event) {
  handleFiles(event.target.files);
});

function saveDocumentChanges() {
    var empProfileId = '{{ $emp_profile->id }}'; 
    var documentName = $('#document_name').val();
    var documentFile = $('#document_file').prop('files')[0];
    if (documentName.trim() === '') {
      alert("Please enter a Document name.");
      return;
    }
    if (!documentFile) {
    alert("Please select a file.");
    return;
    }
    var formData = new FormData();
    formData.append('emp_profile_id', empProfileId);
    formData.append('document_name', documentName);
    formData.append('document_file', documentFile);
    var url = '{{ route("profile.saveDocument") }}';
    var token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
      url: url,
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      headers: {
        'X-CSRF-TOKEN': token
      },
      success: function(response) {
        console.log('Document saved successfully');
        location.reload();
      },
      error: function(xhr, status, error) {
        console.error('Error saving document: ' + error);
      }
    });
  }
  function confirmDelete(url) {
  if (confirm('Are you sure you want to delete this document?')) {
    fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => {
      location.reload();
      alert('Document deleted successfully');
    })
    .catch(error => {
     
    });
  }
}
function confirmSkillDelete(url) {
  if (confirm('Are you sure you want to delete this Skill?')) {
    fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => {
      location.reload();
      alert('Document deleted successfully');
    })
    .catch(error => {
     
    });
  }
}
function confirmHistoryDelete(url) {
  if (confirm('Are you sure you want to delete this Skill?')) {
    fetch(url, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => {
      location.reload();
      alert('Document deleted successfully');
    })
    .catch(error => {
     
    });
  }
}
function saveSkillChanges() {
  var skillName = document.getElementById('skill_name').value;
  var skillPercentage = document.getElementById('skill_percentage').value;
  var empProfileId = document.getElementById('emp_profile_id').value;
  var existingSkills = document.querySelectorAll('.existing-skill-name');
  if (skillName.trim() === '') {
      alert("Please enter a Skill Name");
      return;
    }
  for (var i = 0; i < existingSkills.length; i++) {
    if (existingSkills[i].getAttribute('data-skill-name') === skillName) {
      alert('Skill name already exists. Please enter a different skill name.');
      return;
    }
  }
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      console.log(this.responseText);
      location.reload();
    }
  };
  xhttp.open('POST', '/update-save-skill', true);
  xhttp.setRequestHeader('Content-Type', 'application/json');
  xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
  var data = {
    name: skillName,
    percentage: skillPercentage,
    emp_profile_id: empProfileId,
  };
  xhttp.send(JSON.stringify(data));
}
function savehistoryChanges() {
  var CompanyName = document.getElementById('company_name').value;
  var designation = document.getElementById('designation').value;
  var location = document.getElementById('location').value;
  var todate = document.getElementById('todate').value;
  var fromdate = document.getElementById('fromdate').value;
  var empProfileId = document.getElementById('emp_profile_id').value;
  if (CompanyName.trim() === '') {
      alert("Please enter a Company name.");
      return;
    }
    if (designation.trim() === '') {
      alert("Please enter a Designation");
      return;
    }
    if (location.trim() === '') {
      alert("Please enter a Location");
      return;
    }
  if (todate >= fromdate) {
      alert("To date must be earlier than the From date.");
      return;
    }
  $.ajax({
    url: '{{ route('profile.updatehistoryInfo') }}',
    type: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      CompanyName: CompanyName,
    designation: designation,
    location: location,
    todate: todate,
    empProfileId: empProfileId,
    fromdate: fromdate
    },
    success: function(response) {
  var modal = new bootstrap.Modal(document.getElementById('historyModal'));
  modal.hide();
  window.location.reload();
},
    error: function(xhr) {
    }
  });
}
window.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#contact-number");
    var iti = window.intlTelInput(input, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
        initialCountry: "ae",
        separateDialCode: false,
        nationalMode: true
    });
    input.addEventListener('input', function() {
        var currentValue = input.value;
        var newValue = currentValue.replace(/[^0-9]/g, '');
        if (newValue.charAt(0) !== '+') {
            newValue = '+' + newValue;
        }
        if (newValue.length > 15) {
            newValue = newValue.slice(0, 15); // Truncate to 15 digits
        }
        input.value = newValue;
    });
    iti.events.on("countrychange", function() {
        var countryCode = iti.getSelectedCountryData().dialCode;
        if (input.value && input.value.charAt(0) === '+') {
            input.value = "+" + countryCode + input.value.substr(4);
        } else {
            input.value = "+" + countryCode;
        }
    });
});
window.addEventListener('DOMContentLoaded', function() {
    var input = document.querySelector("#company-number");
    var iti = window.intlTelInput(input, {
        utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js",
        initialCountry: "ae",
        separateDialCode: false,
        nationalMode: true
    });
    input.addEventListener('input', function() {
        var currentValue = input.value;
        var newValue = currentValue.replace(/[^0-9]/g, '');
        if (newValue.charAt(0) !== '+') {
            newValue = '+' + newValue;
        }
        if (newValue.length > 15) {
            newValue = newValue.slice(0, 15); // Truncate to 15 digits
        }
        input.value = newValue;
    });
    iti.events.on("countrychange", function() {
        var countryCode = iti.getSelectedCountryData().dialCode;
        if (input.value && input.value.charAt(0) === '+') {
            input.value = "+" + countryCode + input.value.substr(4);
        } else {
            input.value = "+" + countryCode;
        }
    });
});
</script>
@endpush