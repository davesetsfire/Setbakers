@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: 'Rubik', sans-serif; line-height: 22px;">
        <span style="font-weight: 500;">Hallo <span>{{ $name }}</span>,</span><br>
        <br>
        <span style="font-weight: 500;">
            Vielen Dank, wir haben Deine Zahlung per PayPal erhalten. Im Anhang findest Du die Rechnung zu Deinem SetBakers-Fundus Pro
        </span>
    </td>
</tr>
<tr>
    <td height="80">&nbsp;</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: 'Rubik', sans-serif; line-height: 22px;">
        Falls Du Fragen oder Anregungen zu SetBakers hast, zögere bitte nicht, Dich bei uns zu melden. Wir freuen uns darauf von Dir zu hören!
    </td>
</tr>
<tr>
    <td height="50" align="center">
        <a href="mailto:all@setbakers.de" target="_blank" rel="noopener" style="background: #8fb3bc;
           color: #ffffff;
           font-size: 12px;
           text-decoration: none;
           font-family: arial;
           padding: 8px 20px;
           border-color: #8fb3bc;
           border-radius: 6px;
           display: inline-block;">Feedback geben</a>
    </td>
</tr>
<tr>
    <td height="40">&nbsp;</td>
</tr>
@endsection