<div class="modal fade global-modal fundus-upgradation-popup" id="proinfinit-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#proinfinit-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('fundus.payment.paypal') }}" method="POST" id="upgrade_fundus_form">
                    @csrf
                    <input type="hidden" name="fundus_current_package" value="{{ $currentPackage ?? '' }}">
                    <ul class="form-progress proinfinit-form-progress style2" id="uprgade-fundus-form-progress">
                        <li class="active" data-id="fundus-choose-package"><span>Paket wählen</span></li>
                        <li data-id="article-count-selection"><span>Artikelanzahl</span></li>
                        <li data-id="fundus-payment-interval"><span>Zahlungsintervall</span></li>
                        <li data-id="fundus-payment-method"><span>Bezahlart</span></li>
                         <!--<li data-id="fundus-confirm-payment"><span>Abschließen</span></li>--> 
                        <li data-id="fundus-upgrade-complete"><span class="progress-bar-pro-complete">Abschließen</span></li>
                    </ul>

                    <div class="body-inner" id="fundus-choose-package">
                        <div class="popup-body show">
                            <h4 class="theme-blue-color">Welches Upgrade möchtest Du durchführen?</h4>
                            <div class="subscribe subscribe_pro">
                                <div class="row">
                                    @if($currentPackage != 'basic')
                                    <div class="col-md-{{ $currentPackage == 'infinite' ? '4' : '6'}}">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_package" value="basic" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div  class="minheight_title">
                                                <h5>Funduskonto <i class="fundus-label">Basic</i></h5>
                                            </div>
                                            <div class="pricing">
                                                <h4>Kostenlos</h4>
                                                <p class="tax"></p>
                                            </div>
                                            <p>Stelle bis zu {{ config('app.max_articles_fundus') }} Artikel kostenlos ein und verleihe zu Deinen Konditionen</p>
                                        </div>

                                    </div>
                                    @endif
                                    @if($currentPackage != 'pro')
                                    <div class="col-md-{{ $currentPackage == 'infinite' ? '4' : '6'}}">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_package" value="pro" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div  class="minheight_title">
                                                <h5>Funduskonto <i class="fundus-label">Pro</i></h5>
                                            </div>
                                            <div class="pricing">
                                                <h4>{{ $fundusSubsPlans['recurring']['1month']->total_amount ?? '' }}€ / Monat</h4>
                                                <p class="tax">inkl. {{ $subsTaxPercentage }}% MwSt</p>
                                            </div>
                                            <p>Erweitere Deinen Fundus auf {{ config('app.max_articles_fundus_pro') }} Artikel</p>
                                        </div>

                                    </div>
                                    @endif
                                    @if($currentPackage != 'infinite')
                                    <div class="col-md-{{ $currentPackage == 'infinite' ? '4' : '6'}}">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_package" value="infinite" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div  class="minheight_title">
                                                <h5>Funduskonto <i class="fundus-label">Infinite</i></h5>
                                            </div>
                                            <div class="pricing">
                                                <h4>Angebot anfordern</h4>
                                                <p class="tax">(unverbindlich)</p>
                                            </div>
                                            <p>Wir machen Dir ein individuelles Angebot für Deinen Fundus</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($currentPackage == 'infinite')
                                    <div class="col-md-{{ $currentPackage == 'infinite' ? '4' : '6'}}">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_package" value="infinite" required>
                                                <span class="checkmark"></span>
                                            </label>
                                            <div  class="minheight_title">
                                                <h5>Funduskonto <i class="fundus-label">Infinite</i></h5>
                                            </div>
                                            <div class="pricing">
                                                <h4>Artikelanzahl erhöhen</h4>
                                                <p class="tax">(unverbindlich)</p>
                                            </div>
                                            <p>Wir machen Dir ein individuelles Angebot für Deinen Fundus</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#proinfinit-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('fundus-payment-interval')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="fundus-payment-interval">
                        <div class="requests-data proinfinit-minheight">
                            <div class="col-md-12 mx-auto p-0">
                                <div class="subscribe">
                                    <div class="row" style="margin-bottom: 25px;">
                                        <div class="col-md-6">
                                            <div class="payment-interval-box">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_subscription_type" value="monthly" checked 
                                                       data-prefix="monatlicher"
                                                       data-name="Monatliche Zahlung"
                                                       data-subscription-charge="{{ $fundusSubsPlans['recurring']['1month']->total_amount ?? '' }}€ / Monat"
                                                       data-tax="{{ $subsTaxPercentage }}"
                                                       data-basic_amount="{{ $fundusSubsPlans['recurring']['1month']->basic_amount ?? '' }}" 
                                                       data-tax_amount="{{ $fundusSubsPlans['recurring']['1month']->tax_amount ?? '' }}" 
                                                       data-total_amount="{{ $fundusSubsPlans['recurring']['1month']->total_amount ?? '' }}" 
                                                       required="">
                                                <span class="checkmark"></span>
                                            </label>
                                            <h3>Monatliche Zahlung</h3>
                                            <p class="small-text">monatlich kündbar <br> nur PayPal-Zahlung möglich</p>
                                            <div class="comlpleterequests_pricetax">
                                                <h4>{{ $fundusSubsPlans['recurring']['1month']->total_amount ?? '' }}€ / Monat</h4>
                                                <p>inkl. {{ $subsTaxPercentage }}% MwSt</p>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="payment-interval-box">
                                            <label class="check_container radio">
                                                <input type="radio" name="fundus_subscription_type" value="yearly" 
                                                       data-prefix="jährlicher"
                                                       data-name="Jährliche Zahlung"
                                                       data-subscription-charge="{{ $fundusSubsPlans['recurring']['1year']->total_amount ?? '' }}€ / Jahr"
                                                       data-tax="{{ $subsTaxPercentage }}"
                                                       data-basic_amount="{{ $fundusSubsPlans['recurring']['1year']->basic_amount ?? '' }}" 
                                                       data-tax_amount="{{ $fundusSubsPlans['recurring']['1year']->tax_amount ?? '' }}" 
                                                       data-total_amount="{{ $fundusSubsPlans['recurring']['1year']->total_amount ?? '' }}" 
                                                       required="">
                                                <span class="checkmark"></span>
                                            </label>
                                            <h3>Jährliche Zahlung</h3>
                                            <p class="small-text">Vertrag verlängert sich automatisch bis<br> zur Kündigung </p>
                                            <div class="comlpleterequests_pricetax">
                                                <h4>{{ $fundusSubsPlans['recurring']['1year']->total_amount ?? '' }}€ / Jahr</h4>
                                                <p>inkl. {{ $subsTaxPercentage }}% MwSt</p>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tax-cls-payment border-top-0 pt-0">
                                    <p><span class="subsBasicTitle"></span> <span class="subsBasicAmount price-width"></span></p>
                                    <p><span class="subsTaxTitle"></span> <span class="subsTaxAmount price-width"></span></p>
                                    <p class="text-bold border-top pt-2"><span class="subsTotalTitle"></span> <span class="subsTotalAmount price-width"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="btns mt-3">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('fundus-choose-package', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('fundus-payment-method')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="fundus-payment-method">
                        <div class="method proinfinit-minheight payment-spacer">
                            <h3>Bezahlart wählen</h3>
                            <div class="payment-method">
                                <label class="check_container radio">
                                    <input type="radio" id="fundus_payment_method_paypal" name="fundus_payment_method" value="paypal" data-name="Paypal" required>
                                    <span class="checkmark"></span> <img src="{{ asset('assets/images/paypal.png') }}" width="100">
                                </label>
                                <label class="check_container radio border-0 bankuber">
                                    <div class="bankuber-radio">
                                        <input type="radio" id="fundus_payment_method_bank" name="fundus_payment_method" value="bank_account" data-name="Banküberweisung" required>
                                        <span class="checkmark"></span> </div><div class="bankuber-desc"><h3> Banküberweisung </h3></div>
                                </label>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('fundus-payment-interval', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('fundus-confirm-payment')"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="fundus-confirm-payment">
                        <div class="complete-order-wrap italic-text-style">
                            <h3>Bestellung abschließen</h3>
                            @if($currentPackage == 'infinite')
                            <!-- paypal -->
                            <p id="fundus-downgrade-pro-paypal-payment-message" style="display:none">Hiermit kündigst Du Dein Funduskonto Infinite zum {{ $fundusSubsEndDate }} und wechselst dann auf das Funduskonto Pro. Zum Ende Deines aktuellen Vertrags, wird der angepasste Betrag von Deinem PayPal-Konto abgebucht.<br/></p>
                            <!-- bank transfer -->
                            <p id="fundus-downgrade-pro-banktransfer-payment-message" style="display:none">Hiermit kündigst Du Dein Funduskonto Infinite zum {{ $fundusSubsEndDate }} und wechselst dann auf das Funduskonto Pro. Zum Ende Deines aktuellen Abos, schicken wir Dir eine Rechnung mit dem neuen Betrag. Bitte begleiche diese dann zeitnah.<br/></p>
                            @endif
                            <div class="dls-row"><span>Upgrade auf:</span> Funduskonto Pro</div>
                            <div class="dls-row"><span>Artikelanzahl:</span> {{ config('app.max_articles_fundus_pro') }}</div>
                            <div class="dls-row"><span>Zahlungsintervall:</span> <i id="l_fundus_subscription_type"></i></div>
                            <div class="dls-row"><span>Zahlungsmethode:</span> <i id="l_fundus_payment_method"></i> <i style="font-size: 12px;">(Abbuchung erst zum Ende des aktuellen Vertrags)</i></div>
                            <div class="dls-row"><span>Betrag:</span> <i id="l_fundus_subscription_charge"></i></div>
                            <div class="checkboxes-wrapper">
                                <label class="check_container">Hiermit erkenne ich die <a href="{{ route('privacy') }}" target="_blank">Datenschutzbedingungen</a> und <a href="{{ route('terms') }}" target="_blank">AGB</a> von SetBakers an
                                    <input type="checkbox" name="fundus_subs_tnc_approval" required>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="btns">
                                <div class="row">
                                    <div class="col-sm-5">
                                        <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('fundus-payment-method', true)">Zurück</button>
                                    </div>
                                    <div class="col-sm-7">
                                        <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('fundus-upgrade-complete')"><div class="spinner-border button_spinner"></div> Kostenpflichtig bestellen</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner complete-proinfinit-body-inner" id="fundus-upgrade-complete">
                        <div class="requests-data proinfinit-minheight">
                            <div class="col-md-12 mx-auto payment-bank-transfer">
                                <h3>Zahlung durch Überweisung</h3>
                                <p>Vielen Dank für das Upgrade auf Dein Funduskonto Pro. Bitte überweise den Betrag von</p>
                                <h5 class="amount-color">{{ $fundusSubsPlans['recurring']['1year']->total_amount ?? '' }}€</h5>
                                <p>auf das untenstehende Konto. <br>
                                    Du kannst Deinen Fundus jetzt auf {{ config('app.max_articles_fundus_pro') }} Artikel erweitern.</p>
                                <div class="bank_details_box">
                                    Empfänger: look-alike media e.K <br>
                                    IBAN: DE17 1101 0101 5002 7983 70<br>
                                    BIC: SOBKDEB2XXX <br>
                                    Bankinstitut: SOLARIS Bank <br>
                                    Referenz: <span class="order_number"></span>
                                </div>
                            </div>
                        </div>
                        <div class="btns btns-option2">
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="button" id="upgrade_fundus_bank_button" class="btn mb-2 global-btn fundus-upgrade-complete-refresh">Schließen</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="infinite-article-count-selection">
                        <h3>Wie viele Artikel möchtest Du buchen?</h3>
                        <div class="row">
                            <div class="col-sm-8 proinfinit-minheight">
                                <div class="form-group pt-5">
                                    <input type="number" name="infinite_required_article_count" class="form-control" placeholder="Artikelanzahl eingeben" required="" min="{{ config('app.max_articles_fundus_pro') + 1 }}">
                                </div>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('fundus-choose-package', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('infinite-fundus-payment-interval')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner" id="infinite-fundus-payment-interval">
                        <div class="col-sm-10  mx-auto proinfinit-selection proinfinit-minheight">
                            <div class="subscribe">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="infinite_subscription_type" value="monthly" required="" class="valid" data-name="Monatliche Zahlung">
                                                <span class="checkmark"></span>
                                            </label>
                                            <h5>Monatliche Zahlung</h5>
                                            <p>monatlich kündbar <br> nur PayPal-Zahlung möglich</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="box-wrapper">
                                            <label class="check_container radio">
                                                <input type="radio" name="infinite_subscription_type" value="yearly" required="" class="valid" data-name="Jährliche Zahlung">
                                                <span class="checkmark"></span>
                                            </label>
                                            <h5>Jährliche Zahlung</h5>
                                            <p>Vertrag verlängert sich automatisch bis <br> zur Kündigung</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('infinite-article-count-selection', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('infinite-fundus-payment-method')">Weiter</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="body-inner" id="infinite-fundus-payment-method">
                        <div class="method proinfinit-minheight payment-spacer">
                            <h3>Bezahlart wählen</h3>
                            <div class="payment-method">
                                <label class="check_container radio">
                                    <input type="radio" id="infinite_payment_method_paypal" name="infinite_payment_method" value="paypal" required="" data-name="Paypal">
                                    <span class="checkmark"></span> <img src="{{ asset('assets/images/paypal.png') }}" width="100">
                                </label>
                                <label class="check_container radio border-0 bankuber">
                                    <div class="bankuber-radio">
                                        <input type="radio" id="infinite_payment_method_bank" name="infinite_payment_method" value="bank_account" required="" data-name="Banküberweisung">
                                        <span class="checkmark"></span> </div><div class="bankuber-desc"><h3> Banküberweisung </h3></div>
                                </label>
                            </div>
                        </div>

                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('infinite-fundus-payment-interval', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="ugradeFundusHandler('infinite-fundus-upgrade-complete')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="body-inner proinfinit-payment-method" id="infinite-fundus-upgrade-complete">
                        <div class="new-design-space">
                        <h3>Deine unverbindliche Anfrage</h3>
                        <div class="row">
                            <div class="col-sm-12 mx-auto proinfinit-minheight infinite-fundus-upgrade-complete">
                                <div class="row pt-3 pb-3">
                                    <div class="col-sm-3 color-green">
                                        Upgrade auf:
                                    </div>
                                    <div class="col-sm-6 text-left">
                                        Funduskonto Infinite
                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-3 color-green">
                                        Artikelanzahl:
                                    </div>
                                    <div class="col-sm-6 text-left infinite_items_count">

                                    </div>
                                </div>
                                <div class="row pb-3">
                                    <div class="col-sm-3 color-green">
                                        Zahlungsintervall:
                                    </div>
                                    <div class="col-sm-6 text-left infinite_payment_interval">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3 color-green">
                                        Zahlungsmethode:
                                    </div>
                                    <div class="col-sm-6 text-left infinite_payment_method">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="ugradeFundusHandler('infinite-fundus-payment-method', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="changeFundusPlan()"><div class="spinner-border button_spinner"></div> Anfragen</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<!-- modal plan change confirmation infinite to basic OR pro to basic plan-->
<div class="modal fade global-modal" id="basic-plan-change-confirmation-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#basic-plan-change-confirmation-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('fundus.plans.downgrade') }}" method="POST" id="plan_change_form">
                    @csrf
                    <input type="hidden" name="new_fundus_package" value="">
                    <h3>Zum FUNDUSKONTO BASIC wechseln</h3>
                    <p>
                        Bist Du sicher, dass Du zum Funduskonto Basic wechseln möchtest? Du kannst weiterhin bis zu {{ config('app.max_articles_fundus') }} Artikel kostenlos verleihen. Deine restlichen Artikel bleiben gespeichert, werden jedoch ab dem 
                        {{ $fundusSubsEndDate }}
                        deaktiviert.
                    </p>

                    <div class="btns">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#basic-plan-change-confirmation-popup">Abbrechen</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn mb-2 global-btn btn-block">Bestätigen</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal plan change confirmation -->
<div class="modal fade global-modal" id="pro-plan-change-confirmation-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#pro-plan-change-confirmation-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <h3>Zum FUNDUSKONTO PRO wechseln</h3>
                <p>
                    Bist Du sicher, dass Du zum Funduskonto Pro wechseln möchtest? Du kannst dann bis zu {{ config('app.max_articles_fundus_pro') }} Artikel verleihen. Deine restlichen Artikel bleiben gespeichert, werden jedoch ab dem 
                    {{ $fundusSubsEndDate }}
                    deaktiviert.
                </p>

                <div class="btns">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#pro-plan-change-confirmation-popup">Abbrechen</button>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn mb-2 global-btn btn-block pro-plan-change-confirmation-button">Weiter</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal infinite plan change confirmation -->
<div class="modal fade global-modal thanku-modal" id="infinite-plan-change-confirmation-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h3>Anfrage erhalten</h3>
                <p>Vielen Dank. Wir melden uns schnellstmöglich mit einem individuellen Angebot zurück.</p>
            </div>
            <div class="modal-footer border-0 mt-3 p-0 pt-2">
                <button type="button" class="btn global-btn mx-0 mt-2" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>