<table>
    <tbody>
    <tr>
        <th>Payment Reference</th>
        <th>Beneficary Name</th>
        <th>Beneficary Account No.</th>
        <th>Swift Address</th>
        <th>Local Clearing Address/Code</th>
        <th>Payment Currency</th>
        <th>Payment Amount</th>
        <th>Value Date <br>YYYYMMDD</th>
        <th>Beneficary Bank Country</th>
        <th>Charges</th>
        <th>Payment Information 1</th>
        <th>Payment Information 2</th>
        <th>Payment Information 3</th>
        <th>Dealer Name and Exchange Rate</th>
        <th>Email-Id</th>
        <th>Regular Reporting</th>
        <th>On Behalf Account No / ID</th>
        <th>Own / On Behalf Ordering Party Name</th>
        <th>Own / On Behalf Ordering Party Address 1</th>
        <th>Own / On Behalf Ordering Party Address 2</th>
        <th>Own / On Behalf Ordering Party Address 3</th>
        <th>Beneficiary Address 1</th>
        <th>Beneficiary Address 2</th>
        <th>Beneficiary Address 3</th>
        <th>Status</th>
        <th>Failed Reason</th>
    </tr>
    @foreach ($records['withdraws'] as $record)
    <tr>
        <td>{{$record['fp_payment_reference']}}</td>
        <td>{{$record['account_name']}}</td>
        <td>{{$record['account_number']}}</td>
        <td>{{$record['swift_code']}}</td>
        <td></td>
        <td>{{$record['currency_code']}}</td>
        <td>{{$record['amount']}}</td>
        <td>{{$record['value_date']}}</td>
        <td>{{$record['bank_country']}}</td>
        <td>OUR</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{isset($record['email'])?$record['email']:$record['withdrawal_freelancer']['email']}}</td>
        <td>BRT</td>
        <td>12345678910121</td>
        <td>{{$record['fp_ordering_party_name']}}</td>
        <td>{{$record['fp_ordering_party_address_1']}}</td>
        <td></td>
        <td></td>
        <td>{{@$record['primaryLocation']['location']['address']}}</td>
        <td>{{@$record['secondaryLocation']['location']['address']}}</td>
        <td></td>
        <td>{{$record['transfer_status']}}</td>
        <td></td>
    </tr>
    @endforeach
    
    </tbody>
</table>