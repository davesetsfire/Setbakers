<div class="modal fade global-modal welcome" id="message-to-fundus">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn mt-0 p-0 cancel_send_message_to_fundus"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body shooting-period-date">
                <h4 class="mb-1">Nachricht an den Fundus</h4>
                <p class="mb-4">Möchtest Du zu Deiner Anfrage eine Nachricht hinzufügen?</p> 
                <div>
                    <textarea id="fundus_message_text" name="fundus_message_text" class="fundus-message-text" placeholder="Deine Nachricht" maxlength="500"></textarea>
                </div>
                <div class="message-fundus-btn mt-3">
                    <div class="option checkbox-option-style">                                
                        <input type="checkbox" id="fundus_message_save_draft" name="fundus_message_save_draft" value="">
                        <span class="checkmarks"></span>
                        <label class="add-edit-custom_price_available-q">
                            Entwurf speichern
                        </label>
                    </div>
                    <div class="btngroup2">
                        <button type="button" class="btn global-btn style2 cancel_send_message_to_fundus">Abbrechen</button>
                        <button type="button" class="btn global-btn send_request_to_store"><div class="spinner-border button_spinner"></div>Anfrage senden</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
