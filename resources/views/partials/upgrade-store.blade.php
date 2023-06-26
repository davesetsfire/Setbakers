<div class="modal fade global-modal" id="upgrade-store-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#upgrade-store-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form method="POST" id="upgrade_store_form">
                    @csrf
                    <input type="hidden" name="upgrade" value="store">
                    <ul class="form-progress style2" id="upgrade-store-form-progress">
                        <li class="active" data-id="create-account"><span>Fundus eröffnen</span></li>
                        <li class="active" data-id="store-data"><span>Fundusdaten</span></li>
                        <li data-id="comlplete-store-upgrade"><span>Abschließen</span></li>
                    </ul>

                    <div class="body-inner" id="store-data">
                        <h3>Fundusdaten</h3>
                        <div class="register-checked">
                            <label class="check_container radio">
                                <input type="radio" id="registration_account_fundusdaten_firma" name="fundus_company_account" value="fundusdaten_firma_checked" required checked> 
                                <span class="checkmark"></span>
                                Firma
                            </label>
                            <label class="check_container radio">
                                <input type="radio" id="registration_account_fundusdaten_privatperson" name="fundus_company_account" value="fundusdaten_privatperson_checked" required>
                                <span class="checkmark"></span>
                                Privatperson
                            </label>
                        </div>
                        <div class="row rbox fundusdaten_firma_checked">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_company_name" class="form-control" placeholder="Firma" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_ust_id" class="form-control" placeholder="USt-ID">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_name" class="form-control" placeholder="Fundusname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_email" class="form-control" placeholder="Fundus - Emailadresse" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_owner_first_name" class="form-control" placeholder="Inhaber Vorname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <input type="text" name="fundus_owner_last_name" class="form-control" placeholder="Inhaber Nachname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <input type="text" name="fundus_phone" class="form-control" placeholder="Fundus - Telefonnummer (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_website" class="form-control" placeholder="Website (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_street" class="form-control" placeholder="Straße" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="fundus_house_number" class="form-control" placeholder="Hausnummer" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="fundus_postal_code" name="fundus_postal_code" class="form-control" placeholder="Postleitzahl" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="hidden" id="fundus_geo_location" name="fundus_geo_location" />
                                    <input type="text" id="fundus_location" name="fundus_location" class="form-control" placeholder="Ort" required autocomplete="fundus_loc">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" id="fundus_country" name="fundus_country" class="form-control" placeholder="Land" required>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#upgrade-store-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="upgradeStoreHandler('comlplete-store-upgrade')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="comlplete-store-upgrade">
                        <h3>Upgrade abschließen</h3>
                        <div class="registeration-data">
                            <div class="col-md-12 mx-auto p-0">
                                <div class="row">
                                    <div class="col-md-12 fundus-data-view">
                                        <div class="row">
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_name"></h6>
                                                <p>Fundusname</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_company_name"></h6>
                                                <p>Firma</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_ust_id"></h6>
                                                <p>USt-ID</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_email"></h6>
                                                <p>E-Mail-Adresse:</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_phone"></h6>
                                                <p>Telefonnummer:</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6><span class="cls_fundus_owner_first_name"></span>&nbsp;<span class="cls_fundus_owner_last_name"></span></h6>
                                                <p>Inhaber:</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_website"></h6>
                                                <p>Website:</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6><span class="cls_fundus_street"></span>,&nbsp;<span class="cls_fundus_house_number"></span></h6>
                                                <p>STrasse, Hausnummer </p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6><span class="cls_fundus_postal_code"></span>,&nbsp;<span class="cls_fundus_location"></span></h6>
                                                <p>Postleitzahl, Ort</p>
                                            </div>
                                            <div class="data col-md-6 registration-disp-field">
                                                <h6 class="cls_fundus_country"></h6>
                                                <p>Land</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 check-registeration-popup">
                                        <label class="check_container">Hiermit erkenne ich die <a href="{{ route('privacy') }}" target="_blank">Datenschutzbestimmungen</a> und <a href="{{ route('terms') }}" target="_blank">AGB</a> von SetBakers an
                                            <input type="checkbox" name="tnc_approval" required>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="upgradeStoreHandler('store-data', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="upgrade_store_form_button" class="btn mb-2 global-btn btn-block"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

