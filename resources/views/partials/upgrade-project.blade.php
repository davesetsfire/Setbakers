<div class="modal fade global-modal" id="upgrade-project-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#upgrade-project-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('register') }}" method="POST" id="upgrade_project_form">
                    @csrf
                    <input type="hidden" name="upgrade" value="project">
                    <ul class="form-progress style2" id="upgrade-project-form-progress">
                        <li class="active" data-id="create-account"><span>Konto upgraden</span></li>
                        <li class="active" data-id="project-data"><span>Projektdaten</span></li>
                        <li data-id="comlplete-project-upgrade"><span>Abschließen</span></li>
                    </ul>

                    <div class="body-inner btnright" id="project-data">
                        <div class="new-design-space">
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
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#upgrade-project-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="upgradeProjectHandler('comlplete-project-upgrade')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="body-inner btnright" id="comlplete-project-upgrade">
                        <div class="new-design-space">
                        <h3>Upgrade abschließen</h3>
                        <div class="registeration-data">
                            <div class="col-md-12 mx-auto p-0">
                                <div class="row">
                                    <div class="col-md-12 project-data-view">
                                        <div class="row">
<!--                                            <div class="data  col-md-6 registration-disp-field">
                                                <h6><span class="cls_first_name"></span>&nbsp;<span class="cls_last_name"></span></h6>
                                                <p>Name</p>
                                            </div>-->
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_project_name"></h6>
                                                <p>Projektname</p>
                                            </div>
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_company_name"></h6>
                                                <p>Firma</p>
                                            </div>
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_ust_id"></h6>
                                                <p>USt-ID</p>
                                            </div>
<!--                                            <div class="data  col-md-6 registration-disp-field">
                                                <h6 class="cls_email"></h6>
                                                <p>E-Mail-Adresse</p>
                                            </div>
                                            <div class="data  col-md-6 registration-disp-field">
                                                <h6 class="cls_phone_number"></h6>
                                                <p>TelefonnummER</p>
                                            </div>-->
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6><span class="cls_street"></span>,&nbsp;<span class="cls_house_number"></span></h6>
                                                <p>Straße, Hausnummer</p>
                                            </div>
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6><span class="cls_postal_code"></span>,&nbsp;<span class="cls_location"></span></h6>
                                                <p>Postleitzahl, Ort</p>
                                            </div>
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_country"></h6>
                                                <p>Land</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 check-registeration-popup">
                                        <label class="check_container">Hiermit erkenne ich die <a href="{{ route('terms') }}" target="_blank">AGB</a> und <a href="{{ route('privacy') }}" target="_blank">Datenschutzvereinbarung</a> an
                                            <input type="checkbox" name="tnc_approval" required>
                                            <span class="checkmark"></span>
                                        </label>
                                        {{-- <label class="check_container cancellation_approval">Hiermit stimme ich zu, dass look-alike media e.K bereits vor Ablauf der Widerrufsfrist mit der Vertragserfüllung beginnt. Ich habe Kenntnis davon, dass mein <a href="{{ route('cancellation') }}" target="_blank">Widerrufsrecht</a> nach   § 356 Abs. 5 BGB vor der gesetzlichen Frist, mit Beginn der Vertragserfüllung erlischt. Bei Abos bleibt die Möglichkeit zur monatlichen Kündigung zum Ende jedes Abo-Intervalls (Monat) bestehen.
                                            <input type="checkbox" name="cancellation_approval" required>
                                            <span class="checkmark"></span>
                                        </label> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="upgradeProjectHandler('project-data', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="upgrade_project_form_button" class="btn mb-2 global-btn btn-block"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

