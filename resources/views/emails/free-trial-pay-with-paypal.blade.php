@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>Großartige Nachrichten! Dein Projektkonto ist jetzt für alle Funktionen freigeschaltet.</strong>
        <br>
        <br>
        Schau Dir alles in Ruhe an und erkunde, was <strong>SetBakers</strong> zu bieten hat. Wenn Du <strong>SetBakers</strong> nach Ablauf des Testzeitraums weiter nutzen möchtest, brauchst Du nichts weiter zu tun. Der monatliche Betrag wird ab dem nächsten Monat automatisch von Deinem PayPal-Konto abgebucht, bis Du kündigst. Zugehörige Rechnungen bekommst Du monatlich per Mail.
        <br>
        <br>
        Falls Du nicht zufrieden bist, kannst Du das Abo innerhalb des Probemonats unter “Meine Daten” kündigen. Wie Du weißt, ist <strong>SetBakers</strong> gerade erst geschlüpft. Falls noch nicht alles rund läuft oder Du Verbesserungsvorschläge hast, lass es uns gerne wissen. 
        <br>
        Wöchentlich kommen neue Fundi und damit tausende Produkte zu SetBakers hinzu. Außerdem arbeiten wir bereits mit Hochdruck an zusätzlichen Funktionen. Es lohnt sich also, immer mal wieder rein zu schauen.
        <br>
        <br>
        Oder folge uns auf Instagram und Facebook. Hier posten wir regelmäßig Neuerungen.
        <br>
        <br>
        Falls Du Fragen oder Anregungen zu SetBakers hast, zögere bitte nicht, Dich bei uns zu melden. Wir freuen uns darauf von Dir zu hören!<br>
        <br>
    </td>
</tr>
<tr>
    <td height="90" align="center" valign="top"><a href="mailto:all@setbakers.de" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">Feedback geben</a></td>
</tr>
@endsection