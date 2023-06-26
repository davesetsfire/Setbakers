<!-- modal pause fundus -->
<div class="modal fade global-modal" id="pause-funds">
    <div class="modal-dialog modal-dialog-centered1">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#pause-funds"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('fundus.pause') }}" method="POST" id="pause_fundus_form">
                    @csrf
                    <h3>Fundus pausieren</h3>
                    <p>Dein Fundus wird anderen Nutzern in dieser Zeit als inaktiv und deine Artikel als nicht verfügbar angezeigt.</p>
                    <div class="col-md-12 mt-4 p-0">
                        <div class="custom-control mb-2 custom-radio">
                            <input type="radio" class="custom-control-input" name="pause_till" id="funds-label1" value="indefinite" required>
                            <label class="custom-control-label" for="funds-label1">Ja, auf unbestimmte Zeit</label>
                        </div>
                        <div class="funds-date">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="pause_till" id="funds-label2" value="definite" required>
                                <label class="custom-control-label" for="funds-label2">Ja, bis zu folgendem Datum</label>
                            </div>
                            <input type="text" name="pause_till_date" class="daterange-single nobackdate pause-date-select">
                        </div>
                    </div>
                    <div class="btns text-center">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#pause-funds">Abbrechen</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn mb-2 global-btn btn-block" id="pause_fundus_button">Pausieren</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- modal activate fundus -->
<div class="modal fade global-modal" id="activate-funds">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#activate-funds"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('fundus.unpause') }}" method="POST" id="unpause_fundus_form">
                    @csrf
                    <h3>Fundus aktivieren</h3>
                    <p>Dein Fundus wird anderen Nutzern wieder als aktiv und deine Artikel als verfügbar angezeigt.</p>
                    <div class="btns">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#activate-funds">Abbrechen</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn mb-2 global-btn btn-block">Aktivieren</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>