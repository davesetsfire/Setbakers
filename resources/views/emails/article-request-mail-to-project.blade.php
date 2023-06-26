@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>Du hast soeben eine neue Anfrage an <span>{{ $storeName }}</span> gesendet! Im Anhang findest Du die Artikelliste zur Anfrage <span>{{ $requestNumber }}</span>. 
            Der Fundus wird sich bald mit einem Angebot bei Dir melden.</strong></td>
</tr>
@if(!empty($storeMessage))
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;margin-bottom: 20px;">
       <p style="margin-bottom: 6px;padding:0;font-weight: 500;">Deine Nachricht </p>
        <div style="border:1px solid #979797;padding: 10px;color:#787878;margin-bottom: 20px;min-height: 90px;white-space: pre;border-radius: 3px;">{{ $storeMessage }}
        </div>
    </td>
</tr>
@endif
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">Falls Du Fragen oder Anregungen zu SetBakers hast, zögere bitte nicht, Dich bei uns zu melden. Wir freuen uns darauf von Dir zu hören!</td>
</tr>
<tr>
    <td height="50" align="center"><a href="mailto:all@setbakers.de" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">Feedback geben</a></td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
@endsection