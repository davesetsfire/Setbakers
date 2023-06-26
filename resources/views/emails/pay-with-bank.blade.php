@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>Im Anhang findest Du die Rechnung zu Deinem Projektkonto.</strong><br> 
        <strong>Du hast Dich für die Zahlung per Banküberweisung entschieden. Wenn nicht schon geschehen, bitte überweise den Betrag von</strong><br>
    </td>
</tr>
<tr>
    <td height="60" align="center" valign="middle" style="color: #8fb3bb; font-size: 16px; font-weight: bold; font-family: arial; line-height: 22px;">{{ !empty($emailData['amount']) ? number_format($emailData['amount'], 2, ',', '.') :  '' }} EUR</td>
</tr>
<tr>
    <td align="left" valign="top" style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;"><strong>auf folgendes Konto: </strong>
        <br>Empfänger: look-alike media.e.K <br>
        IBAN: DE17 1101 0101 5002 7983 70<br>
        BIC: SOBKDEB2XXX<br>
        Bankinstitut: SOLARIS BANK<br>
        Referenz: {{ $emailData['order_number'] }}<br>
        <br>
        <br>Falls Du Fragen oder Anregungen zu SetBakers hast, zögere bitte nicht, Dich bei uns zu melden. Wir freuen uns darauf von Dir zu hören!</td>
</tr>
<tr>
    <td height="90" align="center" valign="middle"><a href="mailto:all@setbakers.de" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">Feedback geben</a></td>
</tr>
@endsection