@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        <strong>Hallo Team,</strong><br>
        <br/>
        <strong>
            Contact Us Request.<br><br>
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
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        Phone Number: {{ $emailData['phone_number'] ?? '' }}
    </td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;" class="white-space: pre-wrap; word-break: break-all;">
        Message: {{ $emailData['message'] }}
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