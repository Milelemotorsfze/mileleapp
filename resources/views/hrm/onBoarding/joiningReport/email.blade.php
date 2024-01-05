<h4>{{ $data["name"] }}</h4></br>
<p>Good day!</p></br>
<p>Kindly check the employee joining report attached below and verify by click on "Click Here To Verify" Button</p></br>
<p>Employee Joining Report link is - <a href="{{env('BASE_URL')}}/joining_report_employee_verification/{{ $data["id"] }}">Employee Joining Report Verification</a></p></br>
<p>Regards,</p>
<p>{{$data['send_by']}}</p>