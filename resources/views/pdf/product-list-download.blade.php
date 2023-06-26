<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>SetBakers</title>

        <style type="text/css">
            @page {
                margin: 10px;
            }

            @font-face {
                font-family: 'SegoeUI';
                src: url("{{ asset('assets/fonts/SegoeUI.svg#SegoeUI') }}") format('svg'),
                    url("{{ asset('assets/fonts/SegoeUI.ttf') }}") format('truetype'),
                    url("{{ asset('assets/fonts/SegoeUI.woff') }}") format('woff');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'segoeui';
                src: url("{{ asset('assets/fonts/segoeui.eot') }}");
                src: url("{{ asset('assets/fonts/segoeui.eot?#iefix') }}") format('embedded-opentype'),
                    url("{{ asset('assets/fonts/segoeui.woff2') }}") format('woff2');
                font-weight: normal;
                font-style: normal;
            }



            @font-face {
                font-family: 'SegoeUI-Semilight';
                src: url("{{ asset('assets/fonts/SegoeUI-Semilight.svg#SegoeUI-Semilight') }}") format('svg'),
                    url("{{ asset('assets/fonts/SegoeUI-Semilight.ttf') }}") format('truetype'),
                    url("{{ asset('assets/fonts/SegoeUI-Semilight.woff') }}") format('woff');
                font-weight: normal;
                font-style: normal;
            }

            @font-face {
                font-family: 'segoeuisl';
                src: url("{{ asset('assets/fonts/segoeuisl.eot') }}");
                src: url("{{ asset('assets/fonts/segoeuisl.eot?#iefix') }}") format('embedded-opentype'),
                    url("{{ asset('assets/fonts/segoeuisl.woff2') }}") format('woff2');
                font-weight: normal;
                font-style: normal;
            }

            body {
                margin-left: 0px;
                margin-top: 0px;
                margin-right: 0px;
                margin-bottom: 0px;
                font-family: 'SegoeUI-Semilight';
            }

            @media print {
                body {
                    font-family: 'SegoeUI-Semilight';
                }
                .page_break {
                    page-break-before: always;
                }
                
            }
            .page_break {
                page-break-before: always;
            }
            .bg_grey {
                background-color: #e7e8e9;
                -webkit-print-color-adjust: exact;
            }
            .row_2col{
                display: flex;
                justify-content: space-between;
                padding: 10px 20px;
                color: #748284;
                font-size: 16px;
            }

            .row_3col{
                display: flex;
                padding: 10px 20px;
                color: #748284;
                border-top:1px solid #c3c4c5;
                font-size: 14px;
            }
            .bordertop0{
                border-top:none;
            }
            .row_3col .col_1{
                width: 10%;
            }
            .row_3col .col_2{
                width: 40%;
            }
            .row_3col .col_3{
                width: 50%;
                text-align: right;
            }

            .bg_grey1 {
                background-color: #e7e8e9;
                -webkit-print-color-adjust: exact;
            }

            .bg_grey img{
                max-height: 210px;
            }

            .row_2col_img{
                display: flex;
                padding:0;
                color: #748284;
                font-size: 14px;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 14px;
            }

            .row_2col_img .col_1{
                flex: 0 0 49%;
                margin-right:  1%;
                text-align: center;
                height: 220px;
                padding: 10px 0;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
            .row_2col_img .col_1 img{
                max-width: 400px;
                height: auto;
                display: block;
            }
            .row_2col_img .col_2{
                flex: 0 0 49%;
                margin-left:  1%;
                text-align: center;
                height: 220px;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }
            .number_box{
                position: absolute;
                top: 0;
                left: 0;
                border: 2px solid #ffffff;
                font-size: 20px;
                line-height: 19px;
                width: 30px;
                height: 35px;
                text-align: center;
                vertical-align: middle;
                border-left: 0;
                background-color: #e7e8e9;
                -webkit-print-color-adjust: exact;
                font-family: 'SegoeUI';
                border-top:none;
            }
            .footer {
                width: 100%;
                bottom: 0;
                left: 0;
                right: 0;
                position: absolute;
            }

        </style>
    </head>

    <body>

        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
            <tbody>
                <tr>
                    <td>
                        <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td height="30" align="right" style="font-size: 18px; color: #82aeb6;">ARTIKELÜBERSICHT</td>
                                </tr>
                                <tr>
                                    <td height="1px" bgcolor="#d5d5d6" style=" border-bottom: 1px solid #d5d5d6;"></td>
                                </tr>
                                @php $serialNumber = 1; @endphp
                                @php $imageData = []; @endphp
                                @foreach($favouritesByFundus as $storeId => $stores)
                                <tr>
                                    <td height="30" align="right" style="font-size: 18px; color: #000000;">{{ $storeMaster[$storeId]->fundus_name ?? '' }}</td>
                                </tr>
                                @foreach($stores as $favouriteId => $favouriteItems)
                                @if($favouriteMaster[$favouriteId]->user_id != 0)
                                <tr>
                                    <td class="bg_grey" style="border-bottom: 1px solid #c3c4c5;"><table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td height="30" align="left" valign="top" style="color: #748284;font-size: 16px;" width="30%">
                                                        @foreach($favouriteDateMaster[$favouriteId]['date_range'] as $key => $dateRange)
                                                        {{ $dateRange }} </br>
                                                        @endforeach
                                                    </td>
                                                    <td align="left" valign="top" style="color: #748284;font-size: 16px;">Motiv: {{ $favouriteMaster[$favouriteId]->name }}</td>
                                                    <td align="right" valign="top" style="color: #748284;font-size: 16px;">{{ $favouriteDateMaster[$favouriteId]['days'] }} Tage</td>
                                                </tr>
                                            </tbody>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td class="bg_grey">
                                        <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td width="13%" align="left" style="color: #748284;font-size: 16px;">Pos</td>
                                                    <td width="80%" height="24" align="left" style="color: #748284;font-size: 16px;">Artikel</td>
                                                    <td width="7%" align="right" style="color: #748284;font-size: 16px;">Stück</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <table width="96%" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                @foreach($favouriteItems as $favouriteItem)
                                                @php $imageData[$serialNumber]=config('app.website_media_base_url') . $favouriteItem->product['image']; @endphp
                                                <tr>
                                                    <td width="13%" align="left" style="color: #748284;font-size: 14px;border-bottom: 1px solid #c3c4c5;">
                                                        {{ $serialNumber++ }}
                                                    </td>
                                                    <td width="80%" height="20" align="left" style="color: #748284;font-size: 16px;border-bottom: 1px solid #c3c4c5;">
                                                        {{ $favouriteItem->product['name'] ?? '' }}
                                                    </td>
                                                    <td width="7%" align="right" style="color: #748284;font-size: 14px;border-bottom: 1px solid #c3c4c5;">
                                                        {{ $favouriteItem->requested_count ?? 0 }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                @endif
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="page_break">
            <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td height="40" align="right" style="font-size: 18px; color: #82aeb6;">ARTIKELGALERIE</td>
                    </tr>
                    <tr>
                        <td height="1px" bgcolor="#d5d5d6" style=" border-bottom: 1px solid #d5d5d6;"></td>
                    </tr>
                    <tr>
                        <td height="10"></td>
                    </tr>
                    @for($counter = 1 ; $counter <= count($imageData) ; $counter+=2)
                    <tr>
                        <td>
                            <div class="row_2col_img">
                                <table width="100%" align="center">
                                    <tr>
                                        <td width="50%" align="center" valign="middle">
                                            @if(isset($imageData[$counter]))
                                            <div class="col_1 bg_grey"><span class="number_box">{{ $counter }}</span><img src="{{ $imageData[$counter] }}" alt=""></div>
                                            @endif
                                        </td>
                                        <td width="50%" align="center" valign="middle">
                                            @if(isset($imageData[$counter+1]))
                                            <div class="col_1 bg_grey"><span class="number_box">{{ $counter+1 }}</span><img src="{{ $imageData[$counter+1] }}" alt=""></div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                           
                        </td>
                    </tr>
                     @endfor
                    <tr>
                        <td height="20"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <footer class="footer">
            <table width="200" border="0" align="center" cellpadding="0" cellspacing="0">
                <tbody>
                    <tr>
                        <td width="93" align="right" style="font-family: 'SegoeUI-Semilight'; font-size: 12px; color: #231f20;opacity: 0.7;">Erstellt mit </td>
                        <td align="right" width="4" ></td>
                        <td width="103"><img src="{{ asset('assets/images/logo.svg') }}" width="100" height="auto" alt=""/></td>
                    </tr>
                    <tr>
                        <td colspan="3" align="center" style="font-family: 'SegoeUI-Semilight'; font-size: 14px; color: #2e2a2b;"><a href="www.setbakers.de" target="_blank" style="color: #2e2a2b; text-decoration: none;">www.setbakers.de</a></td>
                    </tr>
                </tbody>
            </table>
        </footer>
    </body>
</html>
