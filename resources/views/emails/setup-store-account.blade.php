@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>Herzlichen Glückwunsch! Du bist jetzt stolze:r Besitzer:in eines SetBakers-Fundus. Endlich kannst Du anderen Deine Schätze zugänglich machen und wirst von Filmemacher:innen gefunden.</strong><br>
    <br></td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        Beim <strong>Fundus-Konto Basic</strong>, sind bis zu {{ config('app.max_articles_fundus') }} Artikel kostenlos. Du möchtest mehr Artikel hochladen? Dann verpasse Deinem Fundus ein Upgrade, unter “Meine Daten” in Deinem SetBakers-Konto.
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
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