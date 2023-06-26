@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        <strong>Hallo {{ $name }},</strong><br>
        <br/>
        <strong>
            Du kannst Dein Passwort jetzt zurücksetzen.
        </strong>
    </td>
</tr>
<tr>
    <td height="20">&nbsp;</td>
</tr>
<tr>
    <td height="50" align="center"><a href="{{ $reset_url }}" target="_blank" rel="noopener" style="background: #8fb3bc; color: #ffffff; font-size: 12px;text-decoration: none;font-family: arial;padding:8px 20px; border-color: #8fb3bc; border-radius: 6px; display:inline-block;">Passwort zurücksetzen</a></td>
</tr>
<tr>
    <td height="50">{{ $reset_url }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; color: #000; font-family: arial; line-height: 22px;">
        Der Link zum Zurücksetzen deines Passworts, ist für 60 Minuten gültig.
        <br/>
        Falls Du Dein Passwort nicht zurücksetzen möchtest, musst Du nichts weiter tun.
    </td>
</tr>

<tr>
    <td>&nbsp;</td>
</tr>
@endsection