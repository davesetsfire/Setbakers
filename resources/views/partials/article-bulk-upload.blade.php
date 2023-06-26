<!-- bul upload start -->
<div class="modal fade global-modal" id="bulk-upload">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" id="bulk-upload-form" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <h3>CSV-Massenupload</h3>
                <div class="message-wrap">
                    <label id="bulk-upload-success" class="success" style="display:none"></label>
                    <label id="bulk-upload-error" class="error" style="display:none"></label>
                </div>
                <div class="modal-body p-0">
                    <p>Hier kannst Du eine große Menge an Artikeln, bequem über eine CSV-Datei hochladen.</p>
                    <div class="downloads-wrap">
                        <div class="download-categ">Anleitung</div>
                        <a href="{{ route('fundus.product.bulk.download', ['manual']) }}" class="global-btn"><span class="icon"><img src="{{ asset('assets/images/icons/download-white.png') }}"></span> Download</a>
                    </div>
                    <div class="downloads-wrap">
                        <div class="download-categ">Legende</div>
                        <a href="{{ route('fundus.product.bulk.download', ['masters']) }}" class="global-btn"><span class="icon"><img src="{{ asset('assets/images/icons/download-white.png') }}"></span> Download</a>
                    </div>
                    <div class="downloads-wrap">
                        <div class="download-categ">
                            <select class="select2-single-download" data-width="165" id="bulk-upload-sample-csv">
                                <option value="#">CSV-Vorlage wählen</option>
                                <option value="{{ route('fundus.product.bulk.download', ['Requisiten-und-Einrichtung-CSV']) }}">Requisiten und Einrichtung-CSV</option>
                                <option value="{{ route('fundus.product.bulk.download', ['Grafik-CSV']) }}">Grafik-CSV</option>
                                <option value="{{ route('fundus.product.bulk.download', ['Dienstleistung-CSV']) }}">Dienstleistung-CSV</option>
                                <option value="{{ route('fundus.product.bulk.download', ['Fahrzeuge-CSV']) }}">Fahrzeuge-CSV</option>
                            </select>
                        </div>
                        <a href="#" class="global-btn download-csv-file"><span class="icon"><img src="{{ asset('assets/images/icons/download-white.png') }}"></span> Download</a>
                    </div>
                    <div class="upload-bulk-form csv">
                        <h6>CSV-Datei hochladen <span> (.csv)</span> </h6>
                        <div class="form-group mb-0">
                            <div class="upload-doc">
                                <label>Datei hier ablegen oder <span class="global-btn">Durchsuchen</span> <input type="file" name="products_csv_file" accept=".csv"> <span class="name"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="upload-bulk-form zip">
                        <h6>Bilderverzeichnis hochladen <span>(Zip-Datei)</span> </h6>
                        <div class="form-group mb-0">
                            <div class="upload-doc">
                                <label>Datei hier ablegen oder <span class="global-btn">Durchsuchen</span><input type="file" name="images_zip_file" accept=".zip"> <span class="name"></span></label>                 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer mt-3 p-0 pt-2">
                    <button type="button" class="btn global-btn mt-2 style2" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn global-btn mx-0 mt-2 upload-csv-file"><div class="spinner-border button_spinner"></div> Hochladen</button>
                </div>

            </div>
        </form>
    </div>
</div>
<!-- bulk upload end -->


<!-- thank you popup -->
<div class="modal fade global-modal" id="bulk-upload-thank-you-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h3>CSV-Upload erfolgreich</h3>
                <p>Die Artikel wurden zu Deinem Fundus hinzugefügt.</p>
            </div>
            <div class="modal-footer mt-3 p-0 pt-2">
                <!--<button type="button" class="btn global-btn mt-2 style2" data-dismiss="modal">Abbrechen</button>-->
                <button type="button" class="btn global-btn mx-0 mt-2" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

