<style>
.accordion, .accordion1 {
  background-color: #f3f9fc;
  color: #444;
  cursor: pointer;
  padding: 5px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
}

.active, .accordion:hover, .accordion1:hover {
  /* background-color: #ccc; */
}

.panel {
  padding: 0 18px;
  background-color: white;
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}
</style>
<div class="col-xxl-12 col-lg-12 col-md-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Candidate Cound For Each Round</h4>
    </div>
    <div class="card-body">
      <div class="row">
        @if($countSelectedCandidates > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Candidate Selected And Hiring Request Closed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
          <span> {{ $countSelectedCandidates ?? '' }}</span>
        </div>
        @endif
        @if($countApprovedSelectedCandidates > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Candidate Selected And Approved :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countApprovedSelectedCandidates ?? '' }}</span>
        </div>
        @endif
        @if($countRejectedCandidates > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Rejected :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countRejectedCandidates ?? '' }}</span>
        </div>
        @endif
        @if($countHrApprovalAwaitingCandidates > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Candidate Selected And HR Manager's Approval Awaiting :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countHrApprovalAwaitingCandidates ?? '' }}</span>
        </div>
        @endif
        @if($countDivisionHeadApprovalAwaitingCandidates > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Candidate Selected And Division Head's Approval Awaiting :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countDivisionHeadApprovalAwaitingCandidates ?? '' }}</span>
        </div>
        @endif
        @if($countFifthRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Fifth Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countFifthRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countForthRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Forth Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countForthRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countThirdRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Third Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countThirdRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countSecondRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Second Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countSecondRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countFirstRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">First Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countFirstRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countTelephonicRoundCompleted > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Telephonic Round Interview Completed :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countTelephonicRoundCompleted ?? '' }}</span>
        </div>
        @endif
        @if($countSelectedForInterview > 0)
        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
          <label for="choices-single-default" class="form-label">Resume Shortlisted :</label>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-12">
        <span> {{ $countSelectedForInterview ?? '' }}</span>
        </div>
        @endif
      </div>
      </div>
    </div>
    <div class="card">
    <div class="card-header">
        <h4 class="card-title">Candidate Info</h4>
    </div>
        @if(isset($data->allInterview))
          @if(count($data->allInterview) > 0)
          <div hidden>{{$i=0;}}</div>
            @foreach($data->allInterview as $data)
              <button class="accordion" style="border-bottom: 1px solid #e6e6e6; border-right: 1px solid #e6e6e6; border-left: 1px solid #e6e6e6;">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-12">{{ ++$i }}.
                    <label for="choices-single-default" class="form-label"><strong> Candidate Name :</strong></label><span> {{ $data->candidate_name ?? '' }}</span>
                  </div>
                  <div class="col-lg-5 col-md-5 col-sm-5 col-11">
                    <label for="choices-single-default" class="form-label"> <strong>Current Status :</strong></label><span> {{ $data->candidate_current_status ?? '' }}</span>
                  </div>
                  <div class="col-lg-1 col-md-1 col-sm-1 col-1">
                  <i class="fa fa-arrow-down" aria-hidden="true"></i><i class="fa fa-arrow-up" aria-hidden="true"></i>
                  </div>
                </div>
              </button>
              <div class="panel">
                @include('hrm.hiring.interview_summary_report.details')
              </div>
            @endforeach
          @endif
        @endif
    </div>
</div>
<script>
var acc = document.getElementsByClassName("accordion");
console.log(acc);
var i;
for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>