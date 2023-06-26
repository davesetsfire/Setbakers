@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
            <br>
            <strong>Vielen Dank, wir haben Deine Zahlung per PayPal erhalten. Dein Account ist  nun freigeschaltet und Du kannst alle Funktionen frei nutzen. Im Anhang findest Du die Rechnung.</strong><br>
        <br>
        Falls Du Fragen oder Anregungen zu SetBakers hast, zögere bitte nicht, Dich bei uns zu melden. Wir freuen uns darauf von Dir zu hören!<br>
        <br></td>
</tr>
<tr>
    <td height="90" align="center" valign="top"><a href="mailto:all@setbakers.de" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">Feedback geben</a></td>
</tr>
@endsection