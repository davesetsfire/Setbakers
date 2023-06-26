@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.profile')
@endsection

@section('content')
<section class="my-data-section">
    <div class="container">
        <div class="data-inner">
            <div class="row">
                @include('users.partials.project-show')
                @include('users.partials.fundus-show')
            </div>
        </div>
        @include('users.partials.footer')
    </div>
</section>

<!-- The Modal -->
<div class="modal fade global-modal" id="new-project">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-body text-center">
                <h3>Hast Du ein neues Projekt, Name?</h3>
                <p>Wie es scheint hattest Du eine Pause eingeldegt</p>
                <p><b>Dein letztes Projekt war : Projektname</b></p>
                <p>Möchtest Du den Projekttitel<br>
                    und die Rechnungsadresse ändern?</p>
                <div class="btns">
                    <button type="button" class="btn global-btn btn-block">Ja, Daten anpassen</button>
                    <button type="button" class="btn global-btn style2 btn-block">Nein, Daten behalten</button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('modal-windows')
@include('users.partials.fundus-pause')
@include('users.partials.project-pause')
@include('users.partials.delete-account')
@include('partials.pro-infinit')
@include('partials.article-bulk-upload')

@if(empty(Auth::user()->projectDetail))
@include('partials.upgrade-project')
@else
@include('partials.new-project')
@endif

@if(empty(Auth::user()->fundusDetail))
@include('partials.upgrade-store')
@endif

@endsection
