<!-- The Modal delete-account  -->
<div class="modal fade global-modal" id="delete-account">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#delete-account"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="" method="POST" id="delete_account_form">
                    @csrf
                    <div class="popup-body" id="delete-popup">
                        <h3>Konto löschen</h3>
                        <p>Möchtest Du Dein Konto wirklich löschen? Alle deine persönlichen Daten gehen damit verloren. </p>
                        <div class="btns text-right">
                            <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#delete-account">Abbrechen</button>
                            <button type="button" class="btn global-btn" onclick="popupDelete('account-password-verification');">Konto löschen</button>
                        </div>
                    </div>

                    <div class="popup-body" id="account-password-verification">
                        <h3>Bitte gib Dein Passwort ein</h3>
                        <div class="form-group">
                            <input type="password" name="account_password" class="form-control" placeholder="Passwort" required>
                            <span class="error error-account_password" style="text-align: left"></span>
                        </div>
                        <div class="btns text-right">
                            <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#delete-account">Abbrechen</button>
                            <button type="button" class="btn global-btn" onclick="popupDelete('account-deleted');"><div class="spinner-border button_spinner"></div> Löschen</button>
                        </div>
                    </div>

                    <div class="popup-body" id="account-deleted">
                        <h3>Konto gelöscht</h3>
                        <p>Dein Konto wurde erfolgreich gelöscht.</p>
                        <div class="btns text-right">
                            <button type="button" class="btn global-btn style2" onclick="popupDelete('account-deleted-conclude');">Schließen</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>