<!-- The Delete Store Request Modal -->
<div class="modal fade global-modal" id="delete-store-request">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#delete-store-request"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <h4>Anfrage löschen</h4>
                <p>Möchtest Du diese Anfrage wirklich entfernen?</p>
                <div class="btns text-right">
                    <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#delete-store-request">Abbrechen</button>
                    <button type="button" class="btn global-btn" id="delete-store-request-button"><div class="spinner-border button_spinner"></div> Löschen</button>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- The Delete Store Request Article Modal -->
<div class="modal fade global-modal" id="delete-store-request-article">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#delete-store-request-article"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <h4>Artikel entfernen</h4>
                <p>Möchtest Du diesen Artikel wirklich aus dem Angebot entfernen?</p>
                <div class="btns text-right">
                    <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#delete-store-request-article">Abbrechen</button>
                    <button type="button" class="btn global-btn" id="delete-store-request-article-button"><div class="spinner-border button_spinner"></div> Löschen</button>
                </div>
            </div>

        </div>
    </div>
</div>