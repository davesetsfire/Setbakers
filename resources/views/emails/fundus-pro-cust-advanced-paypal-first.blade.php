@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>
            Wir haben Deine Bestellung erhalten. Dein Funduskonto wird zum Ende Deines bestehenden Abos, am {{ $emailData['subscription_end_date'] ?? ''}}, als Funduskonto Pro fortgeführt und auf {{ config('app.max_articles_fundus_pro') }} Artikel begrenzt. Überzählige Artikel bleiben gespeichert, aber werden dann deaktiviert.
        </strong>
        <br>
        <br>
        Beim Wechsel zu Pro am Ende Deines aktuellen Abos, erhältst Du eine Rechnung. Der Betrag für dein Funduskonto Pro wird dann über Dein PayPal-Konto abgebucht.
    </td>
</tr>
<tr>
    <td height="50">&nbsp;</td>
</tr>
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