<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate</title>


    @php
    $branchMapping = [
    'milele_fze' => ['name' => 'Milele FZE', 'bgImage' => 'milele_fze_header.jpg', 'stampImage' => 'milele_fze_stamp.png'],
    'milele_motors_fze' => ['name' => 'Milele Motors FZE', 'bgImage' => 'milele_motors_fze_header.jpg', 'stampImage' => 'milele_motors_fze_stamp.png'],
    'miele_auto_fze' => ['name' => 'Milele Auto FZE', 'bgImage' => 'milele_auto_fze_header.jpg', 'stampImage' => 'milele_auto_fze_stamp.png'],
    'milele_cars_trading_llc' => ['name' => 'Milele Cars Trading LLC', 'bgImage' => 'milele_cars_trading_llc_header.jpg', 'stampImage' => 'milele_cars_trading_llc_stamp.png'],
    'milele_car_rental_llc' => ['name' => 'Milele Car Rental LLC', 'bgImage' => 'milele_car_rental_llc_header.jpg', 'stampImage' => 'milele_car_rental_llc_stamp.png'],
    'trans_car_fze' => ['name' => 'Trans Car FZE', 'bgImage' => 'trans_car_fze_header.jpg', 'stampImage' => 'trans_car_fze_stamp.png'],
    ];

    $branch = $branchMapping[$certificate->company_branch] ?? ['name' => '_________', 'bgImage' => 'default_header.jpg', 'stampImage' => 'default_stamp.png'];
    $bgPath = public_path('images/Salary-Certificates/Sal_Cer_Bg_Images/' . $branch['bgImage']);
    $bgData = file_get_contents($bgPath);
    $bgType = pathinfo($bgPath, PATHINFO_EXTENSION);
    $bgBase64 = 'data:image/' . $bgType . ';base64,' . base64_encode($bgData);

    $stampPath = public_path('images/Salary-Certificates/Sal_Cer_Company_Stamps/' . $branch['stampImage']);
    $stampData = file_get_contents($stampPath);
    $stampType = pathinfo($stampPath, PATHINFO_EXTENSION);
    $stampBase64 = 'data:image/' . $stampType . ';base64,' . base64_encode($stampData);

    @endphp

    <style>
        @page {
            size: A4;
        }

        .stamp-image {
            width: 150px;
        }

        .certificate-bg {
            background-image: url('{{ $bgBase64 }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            position: relative;
            width: 100%;
            height: 1000px;
            /* margin-left: auto;
            margin-right: auto; */
        }

        .overlay-content {
            position: relative;
            top: 20%;
            padding: 0px 1px;
            font-family: "Calibri", sans-serif;
            font-size: 15px;
            word-spacing: 4px;
        }

        .stamp-container {
            text-align: end;
        }

        .salary-certificate-heading {
            text-align: center;
            text-decoration: underline;
        }

        .salary-certificate-body {
            text-align: justify;
        }

        .system-generated-line {
            margin-top: 120px;
            text-align: center;

        }
    </style>
</head>


<body>

    <div class="certificate-bg">
        <div class="overlay-content">

            @php
            $branchMapping = [
            'milele_fze' => 'Milele FZE',
            'milele_motors_fze' => 'Milele Motors FZE',
            'miele_auto_fze' => 'Milele Auto FZE',
            'milele_cars_trading_llc' => 'Milele Cars Trading LLC',
            'milele_car_rental_llc' => 'Milele Car Rental LLC',
            'trans_car_fze' => 'Trans Car FZE',
            ];

            $branchName = $branchMapping[$certificate->company_branch] ?? '_________';
            @endphp

            <div class="overlay-text">
                <div><strong>Date: </strong>
                    <span id="preview-request-date">
                        {{ isset($certificate->creation_date) ? \Carbon\Carbon::parse($certificate->creation_date)->format('F d, Y') : '_________' }}
                    </span>
                </div>
                <br />
                <p>To,</p>
                <div>
                    <span>{{ $certificate->bank_name ?? '_________' }} <br />
                        {{ $certificate->branch_name ?? '_________' }} <br />
                        {{ $certificate->country_name ?? '_________' }}
                    </span>
                </div>
                <br />
                <div class="salary-certificate-heading">
                    <h3>Salary Certificate</h3>
                </div>

                @php
                function numberToWords($num) {
                $a = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten', 'eleven',
                'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
                $b = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
                $g = ['', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'];

                if ($num == 0) return 'zero';

                $word = '';
                $group = 0;

                while ($num > 0) {
                $groupOfThree = $num % 1000;
                $num = floor($num / 1000);

                if ($groupOfThree > 0) {
                $groupWord = '';

                if ($groupOfThree > 99) {
                $groupWord .= $a[floor($groupOfThree / 100)] . ' hundred ';
                $groupOfThree %= 100;
                }

                if ($groupOfThree > 19) {
                $groupWord .= $b[floor($groupOfThree / 10)] . ' ';
                $groupOfThree %= 10;
                }

                $groupWord .= $a[$groupOfThree] . ' ';
                $word = $groupWord . $g[$group] . ' ' . $word;
                }

                $group++;
                }

                return trim($word);
                }
                @endphp

                <div class="salary-certificate-body">
                    <p>This is to certify that <span id="preview-employee-name">{{ $certificate->requestedFor ? $certificate->requestedFor->name : 'N/A' }}</span>,
                        holding Passport Number <span id="preview-passport-number">{{ $certificate->passport_number ?? '_________' }}</span>,
                        issued by the <span id="preview-issued-by">{{ $certificate->issued_by ?? '_________' }}</span>,
                        is a permanent employee of our esteemed <span id="preview-company-branch">{{ $branchName }}</span>.
                        He is serving as a “<span id="preview-job-title">{{ $certificate->jobTitle ? $certificate->jobTitle->name : '_________' }}</span>” since
                        <span id="preview-joining-date">{{ $certificate->joining_date ? \Carbon\Carbon::parse($certificate->joining_date)->format('F d, Y') : '_________' }}</span>.
                        He is currently withdrawing a monthly salary of AED
                        <span id="preview-salary">{{ $certificate->salary_in_aed > 0 ? number_format($certificate->salary_in_aed, 2) : '_________' }}</span>
                        (<span id="salary-in-words">{{ $certificate->salary_in_aed > 0 ? numberToWords($certificate->salary_in_aed) : '_________' }}</span> UAE dirhams only).
                    </p>

                    <p>This certificate is issued upon the request of the employee, and <span id="preview-company-branch-2">{{ $branchName }}</span> does not bear any legal or financial responsibility for any issues that may arise with this certificate.</p>
                    <br />
                </div>
            </div>

            <div class="stamp-container">
                <img src="{{ $stampBase64 }}" alt="Stamp" class="stamp-image" />
            </div>

            <div class="system-generated-line">
                <p><i>This is system generated certificate.</i></p>
            </div>
        </div>
    </div>


</body>

</html>