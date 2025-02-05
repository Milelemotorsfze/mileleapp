<!DOCTYPE html>
<html>
<head>
    <title>Invoice no : {{ $pfi_number}} | Milele Motors</title>
    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid white;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

    <p>Dear Team,</p>

    <p>
        Greetings from Milele Motors FZE, Dubai!
    </p>

    <p>
        Please find attached Transfer copy of the payment made against the invoice with following details
    </p>

    <table>
        <tr>
            <td>Invoice no</td>
            <td>{{ $pfi_number }}</td>
        </tr>
        <tr>
            <td>Amount</td>
            <td>{{ $total_amount}}</td>
        </tr>
    </table>

    <p>
        You will receive the SWIFT copy as soon as we receive the acknowledgement from the bank. 
        Please reach out to vehicleprocurement@milele.com for any concerns.
    </p></br>

    <p style="margin-bottom:2px;">Regards,</p>
    <p style="margin-top:2px;">Milele Motors</p>

</body>
</html>
