<div class="modal fade global-modal welcome send-message-favoriten-fundus" id="send-message-favoriten-fundus">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn mt-0 p-0" data-toggle="modal" data-target="#send-message-favoriten-fundus"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body shooting-period-date">
                <h4>Leihzeiten überprüfen</h4>
                <p class="mb-3">Bitte wirf nochmal einen Blick auf folgende Leihzeiten. Falls Du keine Abhol- oder Rückgabezeiten definierst, wird hierfür der Drehzeitraum übernommen.</p> 
                <div class="send-message-favoriten-fundus-body">

                </div>
                <div class="text-right">
                    <button type="button" class="btn global-btn style2 cancel_send_request_to_store_popup">Abbrechen</button>
                    <button type="button" class="btn global-btn send_message_to_fundus"><div class="spinner-border button_spinner"></div>Anfragen</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="send-message-favoriten-fundus-body-clone" style="display:none">
    <div class="dategroup">
        <h5 class="set_name"> </h5>
        <div class="fundus-dates-wrap">
            <div class="fundus-dates justify-content-between">
                <input type="hidden" name="favourite_date_id" value="">
                <div class="spinner-border" style="display:none;"></div>
                <input type="hidden" name="favourite_store_id" value="">
                <div class="start_date">
                    <input type="text" data-drop="top" class="form-control daterange-single3 pickup_date" placeholder="TT.MM.JJJJ" value="">
                </div>
                <div class="period-date">
                    SHOOTING_DATE 
                </div>
                <div class="end_date">
                    <input type="text" class="form-control daterange-single3 return_date" placeholder="TT.MM.JJJJ" value="">
                </div>
            </div>
            <div class="fundus-dates-desc d-flex justify-content-between" style="opacity: 1">
                <div class="start-date-txt">Abholung</div>
                <div class="period-date-txt">Drehzeitraum</div>
                <div class="end-date-txt">Rückgabe</div>
            </div>
            <div class="fundus-dates-errormsg error"></div>
        </div>
    </div>
</div>
