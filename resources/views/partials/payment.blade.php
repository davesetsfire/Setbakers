<!-- payment -->
<div class="modal fade global-modal" id="payment-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#payment-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('payment.paypal') }}" method="POST" id="payment_form">
                    @csrf
                    <ul class="form-progress style2">
                        <li class="active" data-id="subscription"><span>Zahlungsintervall wählen</span></li>
                        <li data-id="payment-type"><span>Bezahlart</span></li>
                        <li data-id="project-confirm-payment"><span>Abschließen</span></li>
                    </ul>

                    <div class="popup-body show" id="subscription">
                        <div class="subscribe">
                            <div class="row" style="margin-bottom: 25px;">
                                <div class="col-md-6">
                                    <div class="payment-interval-box">
                                    <label class="check_container radio">
                                        <input type="radio" name="subscription_type" value="recurring" checked="true"
                                               data-name="Abonnement"
                                               data-subscription-charge="{{ isset($showFreeTrial) && $showFreeTrial ? 'kostenloser Probemonat, danach ' : '' }}{{ $projectSubsPlans['recurring']['1month']->total_amount ?? '' }}€ pro Monat"
                                               data-tax="{{ $subsTaxPercentage }}"
                                               data-basic_amount="{{ $projectSubsPlans['recurring']['1month']->basic_amount ?? '' }}" 
                                               data-tax_amount="{{ $projectSubsPlans['recurring']['1month']->tax_amount ?? '' }}" 
                                               data-total_amount="{{ $projectSubsPlans['recurring']['1month']->total_amount ?? '' }}" required>
                                        <span class="checkmark"></span>
                                    </label>
                                    <h3>Abo abschließen</h3>
                                    <ul class="list-bullet">
                                        <li>monatlich kündbar</li>
                                        <li>Nur PayPal-Zahlung möglich</li>
                                        @if(isset($showFreeTrial) && $showFreeTrial)
                                        <li class="text-blue">Im ersten Monat kostenlos testen!</li>
                                        @endif
                                    </ul>
                                </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="payment-interval-box">
                                    <label class="check_container radio">
                                        <input type="radio"  name="subscription_type" value="onetime" required data-name="Einmalzahlung">
                                        <span class="checkmark"></span>
                                    </label>
                                    <h3>Einmalzahlung</h3>
                                    <ul class="list-bullet">
                                        <li>Vertrag verlängert sich nicht automatisch</li>
                                        <li>Paypal oder Banküberweisung möglich</li>
                                    </ul>
                                    <select name="duration" data-width="200">
                                        @if(!empty($projectSubsPlans['onetime']))
                                        @foreach($projectSubsPlans['onetime'] as $key => $plan)
                                        <option value="{{ $plan->id }}" {{ $key == '3month' ? 'selected' : '' }} 
                                                data-name="Einmalzahlung für {{ $plan->duration }} {{ $plan->duration == 1 ? 'Monat' : 'Monate' }}"
                                                data-subscription-charge="{{ $plan->total_amount }}€"
                                                data-tax="{{ $subsTaxPercentage }}"
                                                data-basic_amount="{{ $plan->basic_amount }}" 
                                                data-tax_amount="{{ $plan->tax_amount }}" 
                                                data-total_amount="{{ $plan->total_amount }}">{{ $plan->name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                </div>
                            </div>
                        </div>
                        <!-- tax section -->
                        <div class="tax-cls-payment border-top-0 pt-0">
                            <p><span class="subsBasicTitle"></span> <span class="subsBasicAmount price-width"></span></p>
                            <p><span class="subsTaxTitle"></span> <span class="subsTaxAmount price-width"></span></p>
                            <p class="text-bold border-top pt-2"><span class="subsTotalTitle"></span> <span class="subsTotalAmount price-width"></span></p>
                        </div>
                        <!-- tax section end -->
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#payment-popup">Abbrechen</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="paymentModalHandler('payment-type')">Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="popup-body" id="payment-type">
                        <div class="method">
                            <h3>Bezahlart wählen</h3>
                            <div class="payment-method">
                                <label class="check_container radio">
                                    <input type="radio" id="payment_method_paypal" name="payment_method" value="paypal" data-name="Paypal" required>
                                    <span class="checkmark"></span> <img src="{{ asset('assets/images/paypal.png') }}" width="100">
                                </label>
                                <label class="check_container radio border-0 bankuber">
                                    <div class="bankuber-radio">
                                        <input type="radio" id="payment_method_bank" name="payment_method" value="bank_account" data-name="Banküberweisung" required>
                                        <span class="checkmark"></span> 
                                    </div>
                                    <div class="bankuber-desc">
                                        <h3> Banküberweisung </h3>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="btns">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="paymentModalHandler('subscription', true)">Zurück</button>
                                </div>
                                <div class="col-sm-6">
                                    <button type="button" class="btn mb-2 global-btn btn-block" onclick="paymentModalHandler('project-confirm-payment')"><div class="spinner-border button_spinner"></div> Weiter</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="popup-body" id="project-confirm-payment">
                        <div class="complete-order-wrap italic-text-style">
                            <h3>Bestellung abschließen</h3>

                            <div class="dls-row"><span>Kontoart:</span> Projektkonto</div>
                            <div class="dls-row"><span>Zahlungsintervall:</span> <i id="l_project_subscription_type"></i></div>
                            <div class="dls-row"><span>Zahlungsmethode:</span> <i id="l_project_payment_method"></i></div>
                            <div class="dls-row"><span>Betrag:</span> <i id="l_project_subscription_charge"></i></div>
                            <div class="checkboxes-wrapper">
                                <label class="check_container">Hiermit erkenne ich die <a href="{{ route('privacy') }}" target="_blank">Datenschutzbedingungen</a> und <a href="{{ route('terms') }}" target="_blank">AGB</a> von SetBakers an
                                    <input type="checkbox" name="project_subs_tnc_approval" required>
                                    <span class="checkmark"></span>
                                </label>
                                {{-- <label class="check_container">Hiermit stimme ich zu, dass look-alike media e.K bereits vor Ablauf der Widerrufsfrist mit der Vertragserfüllung beginnt. Ich habe Kenntnis davon, dass mein <a href="{{ route('cancellation') }}" target="_blank">Widerrufsrecht</a> nach  § 356 Abs. 5 BGB vor der gesetzlichen Frist, mit Beginn der Vertragserfüllung erlischt. Bei Abos bleibt die Möglichkeit zur monatlichen Kündigung zum Ende jedes Abo-Intervalls (Monat) bestehen.
                                    <input type="checkbox" name="project_payment_tnc_approval" required>
                                    <span class="checkmark"></span>
                                </label> --}}
                            </div>
                            <div class="btns">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" class="btn mb-2 global-btn style2 btn-block" onclick="paymentModalHandler('payment-type', true)">Zurück</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="button" class="btn mb-2 global-btn btn-block" onclick="paymentModalHandler('bank-detail')"><div class="spinner-border button_spinner"></div> Kostenpflichtig bestellen</button>
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



<!-- payment-confirmation-banktransfer -->
<div class="modal fade global-modal" id="payment-bank-transfer-popup">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <button class="close btn project-upgrade-complete-refresh"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <div class="popup-body payment-bank-transfer-body" id="bank-detail">
                    <div class="bank-detail">
                        <h3>Zahlung durch Überweisung</h3>
                        <p>Vielen Dank für Deine Registrierung {{ Auth::user()->first_name ?? '' }}, bitte überweise den Betrag von</p>
                        <p class="amount-color"><b><span class="subsTotalAmount"></span></b></p>
                        <p>auf untenstehendes Konto.<br></p>
                        <div class="account-info">
                            <p class="mb-0">Empfänger: look-alike media.e.K</p>
                            <p class="mb-0">IBAN: DE17 1101 0101 5002 7983 70</p>
                            <p class="mb-0">BIC: SOBKDEB2XXX</p>
                            <p class="mb-0">Bankinstitut: SOLARIS Bank</p>
                            <p class="mb-0">Referenz: <span class="order_number"></span></p>
                        </div>
                    </div>
                    <div class="btns text-right btns-option2">
                        <div class="row">
                            <div class="col-sm-12">
                                <button type="button" class="btn mb-2 global-btn project-upgrade-complete-refresh">Schließen</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>