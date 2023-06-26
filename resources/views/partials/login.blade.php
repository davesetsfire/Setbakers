<div class="modal fade" id="login-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#login-modal"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="{{ route('login') }}" method="POST" id="login_form">
                    @csrf
                    <div class="login-body" id="login">
                        <h4>ANMELDEN</h4>
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="E-Mail-Adresse" name="email" id="l_email" required>
                            <span class="error errors-email" style="text-align: left"></span>
                        </div>
                        <div class="form-group mb-1">
                            <input type="password" class="form-control" placeholder="Passwort" name="password" id="l_password" required>
                            <span class="error errors-password" style="text-align: left"></span>
                        </div>
                        <div class="text-left"><a href="#" class="open-forgot-password">Passwort vergessen</a></div>
                        <div class="text-left">
                            <label class="check_container d-inline-block">angemeldet bleiben
                                <input type="checkbox" name="remember" value="true">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="submit-btn">
                            <button type="submit" class="btn" id="sign_in_button"><div class="spinner-border button_spinner"></div> ANMELDEN</button>
                        </div>
                        <div class="login-bottom">
                            <h5>Noch kein Konto bei uns?</h5>
                            <a href="#" class="open-registration">Hier registrieren</a>
                        </div>
                    </div>
                </form>
                <!-- Subscription expired / Account deactivated -->
                <div class="login-body" id="thanku">
                    <h4>Willkommen zurück, Name!</h4>
                    <p>Wie es scheint, ist dein letztes Abo abgelaufen
                        oder du hast eine Pause eingelegt.
                        Möchtest Du dein Konto wieder aktivieren?</p>
                    <button type="button" class="btn" onclick="">KONTO AKTIVIEREN</button>
                </div>

                <!-- Change project name and billing address -->
                <div class="login-body" id="quiz">
                    <h4>Hast du ein neues Projekt, Name?</h4>
                    <p>Möchtest Du gleich den Projektnamen<br> und die Rechnungsadresse ändern?</p>
                    <div class="btns">
                        <button type="button" class="btn" onclick="showProjectForm()">Ja, Daten anpassen</button>
                        <button type="button" class="btn style2" onclick="showProjectForm()">Nein, Daten behalten</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>