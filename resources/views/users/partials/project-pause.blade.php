<!-- modal pause project -->
<div class="modal fade global-modal" id="pause-project">
    <div class="modal-dialog modal-dialog-centered1">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#pause-project"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('project.pause') }}" method="POST" id="pause_project_form">
                    @csrf
                    <h3>Abo kündigen</h3>
                    <p>Dein aktuelles Abo läuft bis zum {{ !empty($projectDetail->subscription_end_date) ? date('d.m.Y', strtotime($projectDetail->subscription_end_date)) : '' }}, möchtest Du es zu diesem Zeitpunkt kündigen?</p>
                    <div class="btns text-center">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#pause-project">Abbrechen</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn mb-2 global-btn btn-block" id="pause_project_button">Bestätigen</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
