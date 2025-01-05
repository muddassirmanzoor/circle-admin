<table>
    <tbody>
    <tr>
        <td>IFH</td>
        <td>IFILE</td>
        <td>CSV</td>
        <td>Connect ID</td>
        <td>Customer ID</td>
        <td>File Reference</td>
        <td>File Creation Date</td>
        <td>File Creation Time</td>
        <td>P</td>
        <td>1.0</td>
        <td>15</td>
        <td colspan="17"></td>
    </tr>
    @foreach ($records as $record)
    <tr>
        <td>BATHDR</td>
        <td>FTR</td>
        <td>Total number of payments</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>@1st@</td>
        <td>Value Date</td>
        <td>Debit Account Number</td>
        <td>Payment Currency Code</td>
        <td>Payment amount</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>First Party Account Currency</td>
        <td>Payment amount</td>
        <td>(First Party / On behlaf ) Ordering Party Name </td>
        <td>(First Party /On behalf ) Ordering Party Address1</td>
        <td>(First Party /On behalf ) Ordering Party Address2</td>
        <td>(First Party /On behalf ) Ordering Party Address3</td>
        <td></td>
        <td></td>
        <td>Payment Reference</td>
        <td></td>
        <td>Ordering Party Account/Id</td>

    </tr>
    {{-- Seconed Party --}}
    <tr>
        <td>SECPTY</td>
        <td>Account Number </td>
        <td>{{$record['first_name'].$record['last_name']}}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{$record['primary_location']['location']['city']}}</td>
        <td>{{$record['primary_location']['location']['city']}}</td>
        <td>Beneficiary Address3</td>
        <td>Value Date</td>
        <td>{{$record['bank_detail']['account_number']}}</td>
        <td>{{$record['default_currency']}}</td>
        <td>{{$record['freelancer_earnings']->sum('earned_amount')}}</td>
        <td></td>
        <td>N</td>
        <td>N</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>@HVP@</td>
        <td></td>
        <td>{{$record['default_currency']}}</td>
        <td></td>
        <td></td>
        <td>SWF</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>SWIFT code of Intermediary bank</td>
        <td>Intermdiary bank accout number</td>
        <td>SWF or BCD </td>
        <td>SWIFT Code / BIC / Local Bank Code </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Beneficiary Bank Country Code </td>
        <td></td>
        <td>Payment Information</td>
        <td>Payment Information</td>
        <td>Payment Information</td>
        <td></td>
        <td>SHA</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>Regulatory Reporting1</td>
        <td></td>

    </tr>
    @endforeach
   
    </tbody>
</table>