<div class="modal fade global-modal" id="registeration-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#registeration-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('register') }}" method="POST" id="register_form">
                    @csrf
                    <!-- Account Type Selection Start -->

                    <div class="popup-body show body-inner" id="account-type-selection">
                        <h4>Wie möchtest du SetBakers nutzen?</h4>
                        <div class="subscribe">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="box-shadow">
                                        <label class="check_container radio">
                                            <input type="radio" id="registration_account_type" name="account_type" value="complete" required>
                                            <span class="checkmark"></span>
                                        </label>
                                        <h5>Projektkonto</h5>
                                        <p>(leihen und optional verleihen)</p>
                                        <div class="pricing">
                                            <h4>{{ $projectSubsPlans['recurring']['1month']->total_amount ?? '' }}€ / Monat</h4>
                                            <p class="tax">inkl. {{ config('app.tax_percentage') }}% MwSt</p>
                                        </div>
                                        <ul class="list-bullet">
                                            <li>Nutze die umfassende Suchfunktion</li>
                                            <li>Erstelle Deine eigene Favoritenliste</li>
                                            <li>Sehe die Kontaktdaten zu Deinen ausgewählten Produkten ein</li>
                                            <li>Erstelle optional Deinen eigenen Fundus mit bis zu {{ config('app.max_articles_complete') }} Artikeln.</li>
                                            <li>Monatlich kündbar</li>
                                            <li class="text-blue">Jetzt einen Monat kostenlos testen!</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="box-shadow">
                                        <label class="check_container radio">
                                            <input type="radio" id="registration_account_type_fundus" name="account_type" value="fundus" required>
                                            <span class="checkmark"></span>
                                        </label>
                                        <h5>Funduskonto</h5>
                                        <p>(verleihen)</p>
                                        <div class="pricing">
                                            <h4>Kostenlos</h4>
                                        </div>
                                        <ul class="list-bullet">
                                            <li>Erstelle Deinen eigenen Onlinefundus</li>
                                            <li>Stelle bis zu {{ config('app.max_articles_fundus') }} Artikel kostenos ein und verleihe zu Deinen Konditionen</li>
                                            <li>Deaktiviere Deinen Fundus temporär, wenn Du mal keine Zeit hast</li>
                                            <li>Erweitere Deinen Fundus, auf so viele Artikel wie Du möchtest.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="btns row">
                                <div class="col-sm-6 ml-auto">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="registerationHandler('create-account')">Weiter</button>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                    <!-- Account Type Selection End -->

                    <ul class="form-progress style2" id="register-form-progress" style="display:none">
                        <li class="active" data-id="create-account"><span>Zugang</span></li>
                        <li data-id="project-data"><span>Projektdaten</span></li>
                        <li data-id="fundus-data"><span>Fundusdaten</span></li>
                        <li data-id="comlplete-registeration"><span>Abschließen</span></li>
                    </ul>
                    <div class="body-inner" id="create-account">
                        <h3>Konto anlegen</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="first_name" class="form-control" placeholder="Vorname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="text" name="last_name" class="form-control" placeholder="Nachname" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="E-Mail-Adresse" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <input type="text" name="phone_number" class="form-control" placeholder="Telefonnummer (optional)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" id="registration_password" name="password" class="form-control" placeholder="Passwort" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Passwort wiederholen" required>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#registeration-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="registerationHandler('project-data')">Weiter</button>
                                </div>
                            </div>
                        </div>
                        <div class="already-registered">
                            <p>Du hast bereits ein Konto?</p>
                            <a href="#" class="open-login">Anmelden</a>
                        </div>
                    </div>

                    <div class="body-inner" id="project-data">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="check_container style2">Ich möchte zusätzlich einen Fundus anlegen <span>(Du kannst auch zu einem späteren Zeitpunkt noch einen Fundus anlegen)</span>
                                        <input type="checkbox" name="add_fundus_store" value="yes">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="registerationHandler('create-account', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="registerationHandler('fundus-data')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="fundus-data">
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
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="registerationHandler('project-data', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <!--<button type="button" class="btn mb-2 global-btn btn-block" onclick="registerationHandler('comlplete-registeration')">Überspringen</button>-->
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="registerationHandler('comlplete-registeration')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="body-inner btnright" id="comlplete-registeration">
                        <h3>Registrierung abschließen</h3>
                        <div class="registeration-data">
                            <div class="col-md-12 mx-auto p-0">
                                <div class="row">
                                    <div class="col-md-6 project-data-view">
                                        <div class="row">
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6><span class="cls_first_name"></span>&nbsp;<span class="cls_last_name"></span></h6>
                                                <p>Name</p>
                                            </div>
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
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_email"></h6>
                                                <p>E-Mail-Adresse</p>
                                            </div>
                                            <div class="data  col-md-12 registration-disp-field">
                                                <h6 class="cls_phone_number"></h6>
                                                <p>TelefonnummER</p>
                                            </div>
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
                                    <div class="col-md-6 fundus-data-view">
                                        <div class="row">
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_name"></h6>
                                                <p>Fundusname</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_company_name"></h6>
                                                <p>Firma</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_ust_id"></h6>
                                                <p>USt-ID</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_email"></h6>
                                                <p>E-Mail-Adresse:</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_phone"></h6>
                                                <p>Telefonnummer:</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6><span class="cls_fundus_owner_first_name"></span>&nbsp;<span class="cls_fundus_owner_last_name"></span></h6>
                                                <p>Inhaber:</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_website"></h6>
                                                <p>Website:</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6><span class="cls_fundus_street"></span>,&nbsp;<span class="cls_fundus_house_number"></span></h6>
                                                <p>STrasse, Hausnummer </p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6><span class="cls_fundus_postal_code"></span>,&nbsp;<span class="cls_fundus_location"></span></h6>
                                                <p>Postleitzahl, Ort</p>
                                            </div>
                                            <div class="data col-md-12 registration-disp-field">
                                                <h6 class="cls_fundus_country"></h6>
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
                                            <input type="checkbox" name="cancellation_approval">
                                            <span class="checkmark"></span>
                                        </label> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="registerationHandler('fundus-data', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" id="sign_up_button" class="btn mb-2 global-btn btn-block"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- register success popup -->
<div class="modal fade global-modal" id="register-success">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#register-success"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body btnright">
                <h3>Registrierung abgeschlossen</h3>
                <div id="fundus-success-message" style="display: none">
                    <p>Vielen Dank für deine Registrierung <span class="register-success-message-name"></span>, wir haben eine Bestätigung an die E-Mail Adresse <span class="register-success-message-email"></span> geschickt. </p>
                    <p>Bitte verifiziere Deine E-Mail Adresse, mit dem darin enthaltenen Link.</p>
                </div>

                {{-- <div id="complete-success-message" style="display: none">
                    <p>Herzlichen Glückwunsch <span class="register-success-message-name"></span>, du bist nur noch einen Schritt
                        von Deinem neuen Workflow entfernt!</p>

                    <p>Wir haben eine Bestätigung an die E-Mail Adresse
                        <span class="register-success-message-email"></span> geschickt.</p>

                    <p>Bitte bestätige Deine Registrierung, mit dem darin enthaltenen Link. Danach wirst Du zur Zahlungsabwicklung weitergeleitet.</p>
                </div> --}}

                <div class="btns text-right">
                        <button type="button" class="btn mb-2 global-btn btn-block" data-toggle="modal" data-target="#register-success">Schließen</button>
                </div>
            </div>

        </div>
    </div>
</div>
