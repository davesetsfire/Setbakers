<!-- The Modal forgot-password  -->
<div class="modal fade global-modal" id="forgot-password">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#forgot-password"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <form action="{{ route('password.email') }}" method="POST" id="forgot_password_form">
                @csrf
                <div class="body-inner show" id="forgot-password-email">
                    <h3>Gib Deine E-Mail Adresse ein</h3>
                    <div class="form-group">
                        <input type="text" class="form-control" name="email" placeholder="E-Mail Adresse">
                        <span class="error error-email" style="text-align: left"></span>
                    </div>
                    <div class="btns text-center">
                        <button type="button" class="btn global-btn" onclick="forgotPassword('forgot-password-confirm');"><div class="spinner-border button_spinner"></div> Einreichen</button>
                    </div>
                </div>

                <div class="body-inner" id="forgot-password-confirm">
                    <h3>Passwort vergessen</h3>
                    <p>{{ __('passwords.sent') }}</p>
                    <div class="btns text-center">
                        <button type="button" class="btn global-btn" data-toggle="modal" data-target="#forgot-password">OK</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>