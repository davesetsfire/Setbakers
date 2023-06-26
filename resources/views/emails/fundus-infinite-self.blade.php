@extends('emails.layout')

@section('content')
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">
        <strong>Hallo Team,</strong><br>
        <br/>
        <strong>
            New Fundus Infinite account upgrade request.<br><br>
        </strong>
    </td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Customer Name: {{ $name }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Customer Email: {{ $emailData['email'] }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Upgrade auf: {{ $emailData['fundus_package'] }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Artikelanzahl: {{ $emailData['article_count'] }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Zahlungsintervall: {{ $emailData['subscription_type'] }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">Zahlungsmethode: {{ $emailData['payment_method'] }}</td>
</tr>
<tr>
    <td style="color: #000000; font-size: 14px; font-family: arial; line-height: 22px;">&nbsp;</td>
</tr>

<tr>
    <td>&nbsp;</td>
</tr>
@endsection