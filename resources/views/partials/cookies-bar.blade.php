<div class="footer-cookie">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-8 col-md-7 col-lg-7">
                <p>Schön, dass Du bei uns bist.<br>
                    Wir möchten SetBakers möglichst einfach und benutzerfreundlich gestalten. Deshalb nutzen wir Cookies und andere Technologien, verbessern die Leistung und analysieren die Nutzung. Klicke auf akzeptieren um Dein Einverständnis abzugeben. In unseren <a href="{{ route('privacy') }}">Datenschutzbestimmungen</a> erfährst du mehr.</p>
            </div>
            <div class="col-sm-4 col-md-5 col-lg-5">
                <div class="btn-grp">
                    <button type="button" class="btn global-btn style2 btn-block" data-toggle="modal" data-target="#cookie-model">Cookie-Einstellungen</button>
                    <button type="button" class="btn global-btn style2 btn-block reject-cookies"><div class="spinner-border button_spinner"></div> Alle ablehnen</button>
                    <button type="button" class="btn global-btn btn-block btn-last accept-cookies"><div class="spinner-border button_spinner"></div> Alle akzeptieren</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- cookies popup -->
<div class="modal fade global-modal cookie-model-style" id="cookie-model">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn mt-0 p-0" data-toggle="modal" data-target="#cookie-model"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <h4 class="theme-blue-color">Deine Datenschutz Präferenzen</h4>
                <div class="cookies-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-8"><b>Funktionale Cookies</b></div>
                        <div class="col-sm-4">Immer aktiv</div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-8"><b>Analyse-Cookies</b></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-toggle btn-toggle-cookies analyse-cookie-button"
                                    data-toggle="button" aria-pressed="true" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-8"><b>Marketing-Cookies</b></div>
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-toggle btn-toggle-cookies marketing-cookie-button"
                                    data-toggle="button" aria-pressed="true" autocomplete="off">
                                <div class="handle"></div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-sm-2"><div class="details-cookie"><img src="{{ asset('assets/images/icons/cookie-arrow.png') }}">Details</div></div>
                    <div class="col-sm-10">
                        <div class="btn-grp">
                            <button type="button" class="btn global-btn style2 btn-block custom-cookies"><div class="spinner-border button_spinner"></div> Einstellungen übernehmen</button>
                            <button type="button" class="btn global-btn style2 btn-block reject-cookies"><div class="spinner-border button_spinner"></div> Alle ablehnen</button>
                            <button type="button" class="btn global-btn btn-block btn-last accept-cookies"><div class="spinner-border button_spinner"></div> Alle akzeptieren</button>
                        </div>
                    </div>
                </div>

                <div class="details-cookie-content">
                    <p><b>Funktionale Cookies</b>
                        Diese Cookies sind für einige der grundlegenden Funktionen notwendig und werden daher beim Aufruf unserer Websites automatisch gespeichert. Diese Cookies speichern Deine Präferenzen bei der Nutzung unserer Website. Sie werden auch dazu verwendet, um die Auslastung unserer Server zu verteilen, um unsere Website verfügbar zu halten, sowie zu Sicherheitszwecken.</p>
                    <p>Funktionale Cookies erfüllen folgende Zwecke:</p>
                    <ul>
                        <li>Verbinden mehrerer Anfragen während einer Sitzung</li>
                        <li>Erkennen Deiner Einstellungen für zukünftige Seitenaufrufe</li>
                        <li>Speichern Deiner Einstellungen, z. B. Produktfilter, Vergleichseinstellungen, Sprache, Standort und Anzahl der anzuzeigenden Suchergebnisse</li>
                        <li>Speicherung von Artikeln, die Du während der Suche zu Deiner Favoriten-Liste hinzufügst</li>
                        <li>Websites und Apps werden synchron gehalten, damit sie funktionsfähig und zugänglich bleiben</li>
                        <li>Zur Identifizierung von Missbrauch und potenziellen Problemen mit unseren Websites, Apps und Services, z. B. durch Registrierung von aufeinanderfolgenden Anmeldefehlversuchen</li>
                    </ul>
                    <p>Für die Verwendung von funktionalen Cookies, die die grundlegenden Funktionen der Website ermöglichen und keine oder sehr begrenzte Auswirkungen auf Ihre Privatsphäre haben, besteht keine Zustimmungserfordernis.</p>
                    <p><b>Analyse-Cookies</b>
                        Diese Cookies werden verwendet, um Daten über die Art und Weise zu sammeln, wie Besucher die SetBakers-Websites nutzen. Dazu gehören Angaben zu meistbesuchten Seiten und die Anzahl angezeigter Fehlermeldungen. Mit Hilfe von Analyse-Cookies erstellen wir Nutzungsstatistiken zu unseren Websites. Diese Cookies helfen uns bei der Verbesserung der Websites.</p>
                    <p>Wir benutzen Google Analytics, um zusammengefasste Statistiken zu den Aufrufen unserer Websites zu erstellen. Zu diesem Zweck haben wir einen Datenverarbeitungsvertrag mit Google abgeschlossen. Die durch Google Analytics erhobenen Daten werden mit anderen Google-Diensten geteilt.</p>
                    <p>Folgende Daten werden über Cookies in den Analysesystemen gespeichert:</p>
                    <ul>
                        <li>Ihre IP-Adresse</li>
                        <li>Technische Merkmale wie z. B. verwendeter Browser (Chrome, Internet Explorer, Firefox etc.) und Bildschirmauflösung</li>
                        <li>Von welcher Seite aus Du SetBakers aufgerufen hast</li>
                        <li>Wann und wie lange Du die SetBakers-Websites besucht und benutzt hast</li>
                        <li>Wie Du die Funktionen der Websites nutzt. Dazu gehören z. B. das Erstellen einer Favoritenliste oder das Stellen von Anfragen an Fundi</li>
                        <li>Welche Seiten unserer Website Du aufrufst</li>
                        <li>Wie Du innerhalb Deines Accounts interagierst</li>
                        <li>Deine Suchanfragen, einschließlich der Ergebnisse und der Schritte, die Du bei den Anfragen unternimmst</li>
                        <li>Eindeutige Identifikatoren, z. B. die Besucher-ID, die Ihrem Gerät beim Aufruf unserer Websites zugewiesen wird, die Hash-Transaktions-ID und Hash-Member-ID</li>
                    </ul>
                    <p>Wir benutzen diese Daten zu folgen Zwecken:</p>
                    <ul>
                        <li>Um die Anzahl der Seitenaufrufe zu verfolgen</li>
                        <li>Um die Dauer zu messen, die jeder Besucher auf unseren Seiten verbringt</li>
                        <li>Um zu wissen, in welcher Reihenfolge ein Besucher die Seiten unserer Website besucht</li>
                        <li>Zur Beurteilung, welche Teile unserer Websites und Apps angepasst werden müssen</li>
                    </ul>
                    <p><p><b>Marketing-Cookies</b><br>
                        Diese Cookies werden üblicherweise von Marketingpartnern, Werbenetzwerken und Sozialen Medien auf den Websites von SetBakers platziert. Diese Drittanbieter fungieren als Vermittler, um Dir unsere Inhalte, News, Angebote, Veröffentlichungen in Sozialen Medien sowie Werbung zu präsentieren. Diese Drittanbieter sammeln mit Deinen Cookies ebenfalls über unsere Websites Daten. Die Verarbeitung dieser Daten unterliegt den Datenschutzrichtlinien dieser Drittanbieter.</p></p>
                    <p>Marketing-Cookies erfüllen folgende Zwecke:</p>
                    <ul>
                        <li>Verbindung mit Sozialen Medien, damit Du Inhalte unserer Websites über Soziale Medien teilen kannst</li>
                        <li>Sammeln von Daten, um Werbung innerhalb und außerhalb unserer Websites zu individualisieren und an Deine Interessen anzupassen</li>
                        <li>Begrenzung der Menge angezeigter Werbung</li>
                        <li>Um unsere Besucher auf der Grundlage ihrer Aktionen durch Bereitstellung relevanter, personalisierter Inhalte über E-Mail, Soziale Medien, Bannerwerbung und andere Kanäle besser zu erreichen</li>
                        <li>Zur Messung der Effektivität von Werbekampagnen</li>
                    </ul>
                    <p class="cookie-assignlink"><a href="javascript:void(0)" data-toggle="modal" data-target="#cookie-list-model">Cookies anzeigen</a></p>
                    <p>Um Ihre Einwilligung zu widerrufen, können Sie Ihren Browser so einstellen, dass unsere Cookies oder die von Drittanbietern gelöscht werden. Sie können Ihren Browser auch so einstellen, dass Websites keine Cookies setzen dürfen und Cookies von Drittanbietern abgelehnt werden. Wenn Sie den Einsatz bestimmter Cookies verhindern, kann dies dazu führen, dass einige Funktionen nicht verfügbar sind oder bestimmte Teile der Website nicht geladen werden.</p>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- cookies end -->

<!-- cookies list popup -->
<div class="modal fade global-modal cookie-list-model" id="cookie-list-model">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn mt-0 p-0" data-toggle="modal" data-target="#cookie-list-model"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <div class="cookies-list-table">
                    <p><b>Cookies</b></p>
                   {{--  <p></p> --}}
                    <div class="table-responsive tablescroll mt-2">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Domain</th>
                                    <th>Typ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>setbakers_session</td>
                                    <td>www.setbakers.de</td>
                                    <td>Funktionale</td>
                                </tr>
                                <tr>
                                  <td>XSRF-TOKEN</td>
                                  <td>www.setbakers.de</td>
                                  <td>Funktionale</td>
                                </tr>
                                <tr>
                                  <td>remember_web_...</td>
                                  <td>www.setbakers.de</td>
                                  <td>Funktionale</td>
                                </tr>
                                <tr>
                                  <td>CONSENT</td>
                                  <td>www.setbakers.de</td>
                                  <td>Funktionale</td>
                                </tr>
                                <tr>
                                  <td>_ga_4S1J9ML5L1</td>
                                  <td>.setbakers.de</td>
                                  <td>Analyse</td>
                                </tr>
                                <tr>
                                  <td>_ga</td>
                                  <td>.setbakers.de</td>
                                  <td>Analyse</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- cookies list end -->