@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        <strong>Hallo Team,</strong><br>
        <br/>
        <strong>
            Infinite Package User has been deleted . Kindly disable all his offline billings<br><br>
        </strong>
    </td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        Customer Name: {{ $emailData['name'] }}
    </td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        Customer Email: {{ $emailData['email'] }}
    </td>
</tr>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
@endsection