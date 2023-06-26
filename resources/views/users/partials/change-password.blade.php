<!-- The Modal change-password  -->
<div class="modal fade global-modal" id="change-password">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#change-password"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="" method="POST" id="change_password_form">
                    @csrf
                    <div class="popup-body" id="enter-password">
                        <h3>Passwort ändern</h3>
                        <div class="col-md-12 p-0">
                            <div class="form-group">
                                <input type="password" name="current_password" class="form-control" placeholder="Aktuelles Passwort">
                            </div>
                            <div class="form-group">
                                <input type="password" name="new_password" class="form-control" placeholder="Neues Passwort">
                            </div>
                            <div class="form-group">
                                <input type="password" id="change_new_password" name="confirm_password" class="form-control" placeholder="Neues Passwort wiederholen">
                            </div>
                        </div>
                        <div class="btns text-center">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn global-btn mb-2 style2 btn-block" data-toggle="modal" data-target="#change-password">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn global-btn mb-2 btn-block" id="change-password-button"><div class="spinner-border button_spinner"></div> Passwort ändern</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="popup-body" id="password-changed">
                    <h3>Passwort ändern</h3>
                    <p class="text-center">Ihr Passwort wurde geändert</p>
                    <div class="btns text-center">
                        <button type="button" class="btn global-btn" data-toggle="modal" data-target="#change-password">OK</button>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>