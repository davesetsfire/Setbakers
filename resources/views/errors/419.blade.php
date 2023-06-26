@extends('layouts.app')

@section('content')
<section class="content-404-page">
    <div class="container text-center">
        <div class="content-404">
            <!--<img src="{{ asset('assets/images/404-icon.png') }}" class="thumb-404">-->
            <h2><center>User Session Expired</center></h2>
            <div class="row">
                <div class="col-sm-12"><p>Please go back to the homepage</p>
                    <a href="{{ route('index') }}" class="btn mb-2 global-btn">GO TO HOME PAGE</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection