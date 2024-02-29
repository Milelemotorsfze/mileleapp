<!DOCTYPE html>
<html>
<head>
    <style>

        .imgdiv{
        width: 100px;
        height:100px;
        }

        img{
            width: 100%;
            /* height: 100%; */
            object-fit: contain;
            }


        html { margin: 0px;}
        /* @page { margin:1cm;background: #f5fbff;  } */
        @page {
            size: A4; margin: 0px;
        }
        body {
            font-style:Serif;
            background: #f5fbff; 
            margin: 0px; padding-left: 3cm; padding-right: 3cm; padding-top: 2cm; padding-bottom: 2cm; font: 12pt "Gill Sans";
        }
        * {
            box-sizing: border-box; -moz-box-sizing: border-box;
        }
        /* .page {
            width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto; border: 1px #f5fbff solid; border-radius: 5px; background: #f5fbff; 
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); 
        }
        .subpage {
            padding: 1cm; border: 5px #f5fbff solid; height: 256mm; outline: 2cm #f5fbff solid;
        }
        @page {
            size: A4; margin: 0;
        }
        @media print {
            .page {
                margin: 0; border: initial; border-radius: initial; width: initial; min-height: initial; box-shadow: initial; background: initial;
                page-break-after: always; 
            }
        } */
        p {
            font-family: "Gill Sans", sans-serif !important; font-size: 13px !important;  width: fit-content!important;
        }
        .bold {
            font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 163px !important;
        }
        .normal {
            font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: max-content!important;
        }
        .bold1 {
            font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 300px !important;
        }
        .normal1 {
            font-family: "Gill Sans", sans-serif !important; font-size: 13px !important; width: 50% !important;
        }
        table {
            border-collapse: separate; border-spacing: 0 7px;
        }
        h1 {
            margin-bottom: 75px!important;
            font-family: "Gill Sans", sans-serif !important;
        }
        .fit-content {
            justify-content: space-between;
        }
        .justify {
            width: 100%;
        }
        .justify p {
            text-align: justify;
        }
        .normal {
                text-align: justify;
            }
    </style>
</head>
<body id="justify">
        <!-- <div class="book"> -->
            <!-- <div class="page"> -->
                <!-- <div class="subpage justify" style="font-style:Serif;" id="justify"> -->
                    <h1 style="color:#034c84;">MILELE</h1>
                    <p>MM/OL/00214/@if($data->offer_letter_send_at != NULL){{Carbon\Carbon::parse($data->offer_letter_send_at)->format('Y')}}@else{{now()->format('Y')}}@endif</br>
                    Date: @if($data->offer_letter_send_at != NULL){{Carbon\Carbon::parse($data->offer_letter_send_at)->format('F d,Y')}}@else{{now()->format('F d, Y')}}@endif</br></br>
                    <strong>@if($data->gender == 1) Mr. @elseif($data->gender == 2) Ms. @endif {{$data->candidate_name ?? ''}}</strong></br> 
                    Passport No: {{$data->candidateDetails->passport_number ?? ''}}</br>   
                    Mob Phone: {{$data->candidateDetails->contact_number ?? ''}}</br></br>   
                    Sub: <strong>Employment Offer</strong></br></br> 
                    <strong>Dear {{$data->candidate_name ?? ''}},</strong></br></br> 
                    We refer to your application submitted, and the subsequent Interview you had with us, we are pleased to offer you employment with our 
                    Company, in the capacity of " <strong>{{$data->candidateDetails->designation->name}}</strong> ".</br></br> 
                    This employment offer is subject to the condition that we are able to obtain from the Ministry of Labor and Social Affairs and the 
                    Naturalization and Immigration administration at the Ministry of Interior an entry permit for your employment under our Sponsorship.</br></br> 
                    Your employment with us shall be pursuant to the following terms and conditions.</br></br> 
                    <table>
                        <tbody>
                            <tr>
                                <td class="bold"><strong>Basic Salary</strong></td>
                                <td class="normal">AED {{$data->candidateDetails->basic_salary}}/- p.m. ( {{$inwords['basic_salary'] ?? }} Only )</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Other Allowance</strong></td>
                                <td class="normal">AED {{$data->candidateDetails->other_allowances}}/- p.m. ( {{$inwords['other_allowances']}} Only )</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Total Salary</strong></td>
                                <td class="normal">AED {{$data->candidateDetails->total_salary}}/- p.m. ( {{$inwords['total_salary']}} Only )</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Place of Work</strong></td>
                                <td class="normal">You will be required to perform your duties in the Emirates of Dubai, U.A.E., or in any other Emirates of the
                                    United Arab Emirates, as we may deem fit at our sole discretion.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Leave</strong></td>
                                <td class="normal">30 days paid leave upon completion of each year of service with us, subject to the Employer having the sole 
                                    right to determine the date of such vacation in accordance with work needs at the discretion of the Employer.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Medical</strong></td>
                                <td class="normal">Medical/Health Insurance will be provided for the candidate itself, not for the family and children and this
                                    will be done while the employment visa will be processed.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Probation Period</strong></td>
                                <td class="normal">You will be on probation period for {{$data->candidateDetails->probation_duration_in_months}} months during which your services may be terminated with 14 days’ 
                                    notice period by employer. During the probationary period, employee has the right to terminate the contract with 30 days’
                                    notice. During the probation period, employees will not be entitled to leave and other permanent employee benefits.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>End of Service Benefits</strong></td>
                                <td class="normal">You will be entitled to the end of service benefits in accordance with the UAE Labor Law. The candidate 
                                    should inform written notice period to the HR department before 30 days from the leaving date.</td>
                            </tr>
                        </tbody>
                    </table>
                <!-- </div> -->
            <!-- </div> -->
            <!-- <div class="page"> -->
                <!-- <div class="subpage justify" id="justify1"> -->
                    <table>
                        <tbody>
                            <tr>
                                <td class="bold"><strong>Secrecy</strong></td>
                                <td class="normal">You should not divulge any information which the company considers trade secrets to any individual or 
                                    organization. Breach of this regulation will result in immediate termination of your employment and forfeit of the service 
                                    benefit and may lead to appropriate legal action as per UAE Law in force.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>General</strong></td>
                                <td class="normal">Notwithstanding any of the above terms and conditions under which you will be required to work shall conform
                                    in all respects with the UAE Labor Laws.</td>
                            </tr>
                            <tr>
                                <td class="bold"><strong>Acknowledgement</strong></td>
                                <td class="normal">Should you agree with the terms and conditions stated above, please sign, and return a copy of this letter in
                                    token of our acceptance for further necessary action from our end. This offer letter is valid only for 3 (three) days from 
                                    the date of this letter unless the same is not accepted, signed, and returned to the undersigned.</td>
                            </tr>
                        </tbody>
                    </table>
                    <p>
                        <strong>
                            Employees are prohibited from discussing their own salary or terms and conditions of employment with other employees inside and 
                            outside the company. In case you violate this instruction, this letter will be cancelled, and company may terminate the employee 
                            with immediate effect.
                        </strong>
                    </p>
                    </br>
                    </br>
                    <p><strong>Thanking you,</strong></p></br>
                    <table>
                        <tbody>
                            <tr>
                                <td class="bold1">
                                    <strong>{{$data->candidateDetails->offerLetterHr->name ?? $hr->handover_to_name}}</strong></br></br>
                                    HR Manager</br>
                                </td>
                                <td class="normal1">
                                    <strong>Read and accepted:</strong></br></br>
                                    Name: <strong> @if($data->gender == 1) Mr. @elseif($data->gender == 2) Ms. @endif {{$data->candidate_name ?? ''}}</strong></br>
                                </td>
                            </tr> 
                            <tr>
                                <td class="bold1">
                                    
                                </td>
                                <td class="normal1">
                                    Date: {{Carbon\Carbon::parse($data->candidateDetails->offer_signed_at)->format('F d,Y')}}</br></br>
                                    <!-- <img src="{{$data->candidateDetails->offer_sign}}" style="width: 100%; height: 100%;" alt="Red dot" /> -->
                                </td>
                            </tr>  
                            <tr>
                                <td class="bold1">
                                    
                                </td>
                                <td>Signature: <div class="imgdiv"><img src="{{$data->candidateDetails->offer_sign}}" style="" alt="Red dot" /></div></td>
                            </tr>              
                        </tbody>
                    </table>
                <!-- </div> -->
            <!-- </div> -->
        <!-- </div> -->
</body>
</html>