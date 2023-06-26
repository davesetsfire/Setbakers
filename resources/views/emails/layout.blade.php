<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SetBakers</title>
        <style type="text/css">
            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                background-color: #e5e5e5;
            }
            .btn {
                width: 160px;
                padding: 10px 5px;
                font-size: 18px;
                margin: 0 15px;
                background: #91cbd8;
                color: #ffffff;
                display: inline-block;
                border-radius: 5px;
                -webkit-transition: all 0.5s ease;
                border: 0;
                text-decoration: none;
            }

            @media(max-width: 767px){
                table{width: 100%;}
            }


        </style>
    </head>

    <body>
        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
            <tbody>
                <tr>
                    <td><table width="520" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                  <td height="10" align="right"></td>
                                </tr>
                                <tr>
                                  <td height="50" align="right" valign="middle" bgcolor="#474747"><table width="94%" border="0" align="center" cellpadding="0" cellspacing="0">
                                   
                                      <tr>
                                        <td align="right"><img src="{{ asset('assets/images/mailer-updated-logo.png') }}" width="107" height="34" alt="" style="display: block;border:0;"/></td>
                                      </tr>
                                    
                                  </table></td>
                                </tr>
                                <tr>
                                    <td height="30" align="right"></td>
                                </tr>
                                @yield('content')
                                <tr>
                                    <td height="20"></td>
                                </tr>
                                <tr>
                                    <td align="left" style="color: #000000; font-size: 14px; color: #000; font-family: 'Arial', sans-serif; line-height: 22px;font-weight: 400;"><strong>Beste Grüße,<br> 
                                    Dein SetBakers-Team</strong></td>
                                </tr>
                                <tr>
                                    <td height="20"></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#474747">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td bgcolor="#474747"><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td align="center"><img src="{{ asset('assets/images/mailer-updated-logo.png') }}" width="84" height="28" alt=""/></td>
                                                </tr>
                                                <tr>
                                                    <td height="1" bgcolor="#bfbfc0"></td>
                                                </tr>
                                                <tr>
                                                    <td height="5"></td>
                                                </tr>
                                                <tr>
                                                    <td align="center" style="color: #8fb3bb; font-size: 12px;  font-family: arial; line-height: 22px;">
                                                        look-alike media e.K &nbsp;  | &nbsp; Malmöer Straße 18 &nbsp;  | &nbsp; 10439 Berlin &nbsp; | &nbsp; Deutschland
                                                        Eingetragen beim Amtsgericht Charlottenburg unter HRA 54408 B
                                                        <br>
                                                        Geschäftsführer : David Peichl
                                                        <br>
                                                        <a href="https://www.setbakers.de" target="_blank" style="color: #8fb3bb;">www.setbakers.de</a></td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td ><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                                                            <tbody>
                                                                <tr>
                                                                    <td width="19%" align="center"><a href="{{ route('terms') }}" target="_blank"  style="color: #ffffff;text-decoration: none;display: block;">AGB</a></td>
                                                                    <td width="28%" align="center"><a href="{{ route('contact') }}" target="_blank" style="color: #ffffff; text-decoration: none;display: block;">Kontakt</a></td>
                                                                    <td width="38%" align="center"><a href="{{ route('privacy') }}" target="_blank" style="color: #ffffff;  text-decoration: none;display: block;">Datenschutz</a></td>
                                                                    <td width="15%" align="center"><a href="{{ route('faq') }}" target="_blank" style="color: #ffffff;  text-decoration: none;display: block;">FAQ</a></td>
                                                                </tr>
                                                            </tbody>
                                                        </table></td>
                                                </tr>
                                            </tbody>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td bgcolor="#474747">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                            </tbody>
                        </table></td>
                </tr>
            </tbody>
        </table>
    </body>
</html>
