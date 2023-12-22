<h4>{{ $data["name"] }}</h4>
@if($data["comment"])
 Please update the personal information form from the below link.
@else
<p>We are very glad to inform you that, you are shortlisted for this job position. Kindly fill the attached form for further procedures. </p>
@endif
<p>link is - <a href="{{env('BASE_URL')}}/candidate/personal_info/{{ $data["id"] }}">Candidate Personal Information Form</a>
</p>

@if($data["comment"])
<p>{{$data["comment"]}}</p>
@endif
<p>Regards,</p>
<p>Milele Matrix</p>