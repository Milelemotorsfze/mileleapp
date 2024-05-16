<h4>{{ $data["name"] }}</h4></br>
<p>Good day!</p></br>
@if($data["comment"])
 Please update the personal information form from the below link.
@else
<p>This is with the reference to your applied position and being a part of our interview process. We would like to inform you that you have 
    completed our selection process successfully, and we are excited to offer you to join our team as a {{ $data['job_position'] }} to add value to our 
    esteemed organization’s growth with your best knowledge and skills, and abilities.</p></br>
<p>Your gross compensation package will be AED {{$data['basic_salary']}} ( {{$data['basic_salary_inwords']}} Only) per month which will be divided into basic salary and other 
    allowances as well, as per our payroll structure. </p></br>
<p>The attached offer letter link is for your acceptance and signature that you need to submit back to us as soon as possible. You will receive a signed copy of your job offer letter to your email. </p></br>
<p>Please also complete, sign, and submit the attached "Personal Information Form" by using the following link. </p></br>
<p>Wish you good luck.</p></br>
@endif
@if($data['canSendOfferLetterLink'] == 'yes')
<p>Candidate Job Offer Letter link is - <a href="{{env('BASE_URL')}}/candidate-offer-letter/sign/{{ $data["id"] }}">Candidate Job Offer Letter</a></p></br>
@endif
@if($data['canSendPersonalInfoLink'] == 'yes')
<p>Candidate Personal Information Form link is - <a href="{{env('BASE_URL')}}/candidate/personal_info/{{ $data["id"] }}">Candidate Personal Information Form</a></p></br>
@endif
@if($data["comment"])
<p>{{$data["comment"]}}</p>
@endif
<p>Looking forward to hearing from you.</p></br>
<p>Regards,</p>
<p>{{$data['send_by']}}</p>