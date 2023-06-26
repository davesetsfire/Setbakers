<div class="modal fade global-modal" id="new-project-option-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h3>Hast Du ein neues Projekt, {{ Auth::user()->name ?? '' }}?</h3>
                <p>Wie es scheint, hattest Du eine Pause eingelegt . 
                    Dein letztes Projekt war : {{ $projectDetail->project_name ?? '' }}
                    Möchtest Du ein neues Projekt anlegen?</p>
            </div>
            <div class="modal-footer mt-3 p-0 pt-2">
                <button type="button" class="btn global-btn mt-2 style2 close-open-payment-popup">Daten behalten</button>
                <button type="button" class="btn global-btn mx-0 mt-2 open-new-project-popup">Daten anpassen</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade global-modal" id="new-project-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#new-project-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="#" method="POST" id="new_project_form">
                    @csrf
                    <input type="hidden" name="upgrade" value="new-project">
                    <div class="body-inner1" id="new-project-data">
                        <h3>Projektdaten</h3>
                        <div class="register-checked">
                            <label class="check_container radio">
                                <input type="radio" id="registration_account_projektdaten_firma" name="project_company_account" value="firma_checked" required checked> 
                                <span class="checkmark"></span>
                                Firma
                            </label>
                            <label class="check_container radio">
                                <input type="radio" id="registration_account_projektdaten_privatperson" name="project_company_account" value="privatperson_checked" required>
                                <span class="checkmark"></span>
                                Privatperson
                            </label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="project_name" class="form-control" placeholder="Projektname" required>
                                </div>
                            </div>
                        </div>
                        <div class="row rbox firma_checked">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="company_name" class="form-control" placeholder="Firma" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="ust_id" class="form-control" placeholder="USt-ID">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="street" class="form-control" placeholder="Straße" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <input type="text" name="house_number" class="form-control" placeholder="Hausnummer" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="postal_code" class="form-control" placeholder="Postleitzahl" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="location" class="form-control" placeholder="Ort" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="country" class="form-control" placeholder="Land" required>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#new-project-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="new_project_form_button" class="btn mb-2 global-btn btn-block"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

