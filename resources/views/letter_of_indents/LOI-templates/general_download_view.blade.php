
<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        /*@page { size: 700pt }*/
        .content{
            font-family: arial, sans-serif;
            font-size:13px;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        table ,td,th{
            /* font-family: arial, sans-serif; */
            border-collapse: collapse;
            /* padding:10px; */
            font-size:12px;
           
            border: 1px solid #1c1b1b;
            /*background-color: #0a58ca;*/
        }
    
        .last{
            text-align: right;
            /*margin-left: 20px;*/
        }
        .page_break { page-break-before: always; }

    </style>

</head>
<body>

    @if($letterOfIndent->LOIDocuments->count() > 0)
        <div class="row">
            @if($isCustomerPassport)
                <img src="{{ public_path('storage/app/public/passports/'.$isCustomerPassport->loi_document_file) }}" class="mt-2"></iframe>
            @endif
            @if($isCustomerTradeLicense)
                <img src="{{ public_path('storage/app/public/tradelicenses/'.$isCustomerTradeLicense->loi_document_file) }}" class="mt-2"></iframe>
            @endif
            @foreach($customerOtherDocAdded as $letterOfIndentDocument)
                <img src="{{ public_path('customer-other-documents/'.$letterOfIndentDocument->loi_document_file) }}" class="mt-2"></iframe>
            @endforeach
        </div>
    @endif
</body>
</html>


