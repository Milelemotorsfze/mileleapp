<!-- <h4>{{ $data["name"] }}</h4>
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
<p>Milele Matrix</p> -->





<h4>{{ $data["name"] }}</h4>
</br>
<p>Greetings from Milele!</p>
</br>
@if($data["comment"])
 Please update the personal information form from the below link.
@else
<p>We are excited to move to the next stage of the selection process and we would like to request you please upload the below-mentioned documents by using the following link in order to proceed further.</p>
</br>
<p>Passport Copy</p>
</br>
<p>Visa Copy</p>
</br>
<p>Emirates ID (If available)</p>
</br>
<p>Educational Documents</p>
</br>
<p>Professional Diplomas / Certificates (If any)</p>
</br>
<p>Passport Size Photograph</p>
</br>
<p>Resume</p>
</br>
<p>National ID</p>
</br>
@endif
<p>link is - <a href="{{env('BASE_URL')}}/candidate/documents/{{ $data["id"] }}">Candidate Documents Request Form</a>
</p>
</br>
@if($data["comment"])
<p>{{$data["comment"]}}</p>
@endif
<p>Looking forward to hearing from you.</p>
</br>
<p>Regards,</p>
<p>{{$data['send_by']}}</p>