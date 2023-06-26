@extends('layouts.app')

@section('content')
<section class="error-page-section">
   <div class="container">
      <div class="row">
        <div class="col-sm-11 mx-auto">
          <div class="row align-items-center">
            <div class="col-sm-5 text-center">
              <h1>500</h1>
              <h3 class="line-height-increase">Oops, da ist etwas <br> schief gelaufen</h3>
            </div>
            <div class="col-sm-7">
              <div class="error-page-content border-left">
                <p><b>Wir kümmern uns darum</b> </p>
                <p class="mb-0">Über das Menü kommst Du zurück zu dem, was Du eigentlich gesucht hast.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
   </div>
</section>
@endsection
