@extends('layouts.app')

@section('content')
<section class="reset-pass">
    <div class="card reset_password_page">
        <div class="card-header">Bitte bestätige Deine E-Mail Adresse</div>

        <div class="card-body">
            @if (session('resent'))
            <div class="alert alert-success" role="alert">
                Wir haben Dir eine E-Mail geschickt. Bevor Du fortfährst, bestätige Deine E-Mail Adresse über den darin enthaltenen Link.
            </div>
            @endif

            Bevor Du fortfährst, bestätige diese über den darin enthaltenen Verifizierungslink.
            Du hast keine Nachricht erhalten?
            </br></br>
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">Klicke hier, um einen neuen Link anzufordern</button>.
            </form>
        </div>
    </div>
</section>
@endsection
