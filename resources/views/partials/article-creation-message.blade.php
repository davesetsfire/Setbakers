{{-- Notice Popup --}}
<div class="modal fade global-modal welcome fundus-notice-model user-impression-block" id="fundus-notice-model">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <!--<button class="close btn mt-0 p-0" data-toggle="modal" data-target="#fundus-notice-model"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>-->
            <div class="modal-body">
                <h4 class="text-blue">Bitte beachte,</h4>
                <p>Bilder, Artikelbezeichnung und Beschreibung dürfen weder Deinen Fundusnamen oder Dein Logo, noch Deine Website oder Kontaktdaten enthalten. Nur so können wir kostenlose Fundusaccounts anbieten.
Außerdem muss für jeden Artikel einer Art ein extra Eintrag erstellt werden. Dadurch können diese leicht gefunden und angefragt werden. Bitte fasse nur identische Artikel über die Mengenangabe zusammen.</p> 
                <p>Einträge, die nicht den Nutzungsbedingungen entsprechen, müssen wir leider entfernen. Danke für Dein Verständnis.</p>
                <div class="row">
                    <div class="col-sm-8">
                        <div class="checkboxes-wrapper">
                            <label class="check_container">Ich habe die Nutzungsbedingungen verstanden
                                <input type="checkbox" name="fundus_notice_approval" id="fundus_notice_approval" required>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                         <div class="text-right">
                            <button type="button" class="btn global-btn fundus-notice-btn user-impression" 
                                    data-impression-key="article-creation-message" data-impression-value="yes"
                                    data-toggle="modal" data-target="#fundus-notice-model" disabled>Weiter</button>
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>