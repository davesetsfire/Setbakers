@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: 'Rubik', sans-serif; line-height: 22px;">
        <span style="font-weight: 500;">Hallo <span>{{ $name }}</span>,</span><br>
        <br>
        <span style="font-weight: 500;">Vielen Dank, Du hast Dich für die Zahlung per Banküberweisung entschieden.
            Wenn nicht schon geschehen, bitte überweise den Betrag von
        </span>
    </td>
</tr>
<tr>
    <td height="80" align="center" style="color: #8FB3BB; font-size: 16px; font-family: 'Rubik', sans-serif; line-height: 22px; font-weight:500;">{{ !empty($emailData['amount']) ? number_format($emailData['amount'], 2, ',', '.') :  '' }} EUR</td>
</tr>
<tr>
    <td height="20" valign="top" style="color: #000000; font-size: 14px; color: #000; font-family: 'Rubik', sans-serif;">auf folgendes Konto:</td>
</tr>
<tr>
  <td height="14"></td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: 'Rubik', sans-serif; line-height: 22px;">
        Empfänger: look-alike media.e.K<br>
        IBAN: DE17 1101 0101 5002 7983 70<br>
        BIC: SOBKDEB2XXX<br>
        Bankinstitut: SOLARIS BANK<br>
        Referenz: {{ $emailData['order_number'] }}
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
           display: inline-block;">Feedback geben
        </a>
    </td>
</tr>
<tr>
    <td height="40">&nbsp;</td>
</tr>
@endsection