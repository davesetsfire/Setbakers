@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br>
        <strong>Herzlichen Glückwunsch! Du hast es fast geschafft und bist stolzer Besitzer eines SetBakers-Accounts. Dem Zugang zu tausenden Artikeln, steht dann nichts mehr im Wege.  Außerdem kannst Du Deine Favoriten übersichtlich sortieren und direkt beim Fundus anfragen.</strong><br>
        <br>
        <strong>Bestätige nur noch Deine E-Mail Adresse, unter folgender Schaltfläche. Danach gelangst Du automatisch zur Zahlungsabwicklung.</strong><br>
    <br></td>
</tr>
<tr>
    <td height="100" align="center"><a href="{{ $verificationUrl }}" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">E-Mail Adresse bestätigen</a></td>
</tr>
<tr>
    <td height="30" style="line-height:0;"></td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        Alternativ, bestätige Deine E-Mail Adresse über diesen <a href="{{ $verificationUrl }}" target="_blank" rel="noopener" style="color:#6db9f9;text-decoration:none;">Link</a> oder kopiere diese<br>
    </td>
</tr>
<tr>
    <td height="3" style="line-height:0;"></td>
</tr>
<tr>
    <td style="color: #707070; font-size: 14px; font-family: arial; line-height: 22px;">
        URL in Deinen Browser
    </td>
</tr>
<tr>
    <td height="3" style="line-height:0;"></td>
</tr>
<tr>
    <td style="color: #707070; font-size: 14px; font-family: arial; line-height: 22px;">
        <a href="{{ $verificationUrl }}" target="_blank" rel="noopener" style="color:#707070;text-decoration:none;white-space: pre-wrap;word-break: break-all;">{{ $verificationUrl }}</a>
    </td>
</tr>
{{-- <tr>
    <td height="30" style="line-height:0;"></td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        Hiermit bestätigen wir Deine Zustimmung, dass wir bereits vor Ablauf der Widerrufsfrist mit der Ausübung des Vertrags beginnen.<br>
        Mit der Zustimmung entfällt Dein Anspruch auf Widerruf. Im Falle eines Abos berührt dies jedoch nicht Dein monatliches Kündigunsrecht zum Ende des aktuellen Abo-Intervalls (Monat).
    </td>
</tr> --}}
<tr>
    <td height="50" align="center">&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
@endsection