@extends('layouts.app')

@section('content')
<section class="contact-page minheight_850">
    <div class="container">
        <h2><center>Kontakt</center></h2>
        <div class="contact-from mt-5">
            @if(session('error_message'))
            <div class="error-msg">
                <i class="zmdi zmdi-close-circle"></i>
                {{ session('error_message') }}
            </div>
            @endif
            @if(session('success_message'))
            <div class="success-msg">
                <i class="zmdi zmdi-check"></i>
                {{ session('success_message') }}
            </div>
            @endif
            <form action="{{ route('contact.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="first_name" class="form-control" placeholder="Vorname" value="{{ old('first_name', app('request')->input('first_name', '')) }}">
                            @if($errors->has('first_name'))
                            <span class="error">{{$errors->first('first_name')}}</span>
                            @endif
                        </div>

                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="last_name" class="form-control" placeholder="Nachname" value="{{ old('last_name', app('request')->input('last_name', '')) }}" >
                            @if($errors->has('last_name'))
                            <span class="error">{{$errors->first('last_name')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control" placeholder="E-Mail-Adresse" value="{{ old('email', app('request')->input('email', '')) }}" >
                            @if($errors->has('email'))
                            <span class="error">{{$errors->first('email')}}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <input type="text" name="phone_number" class="form-control" placeholder="Telefonnummer (Optional)" value="{{ old('phone_number', app('request')->input('phone_number', '')) }}">
                            @if($errors->has('phone_number'))
                            <span class="error">{{$errors->first('phone_number')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <textarea name="contactus_message" placeholder="Nachricht" class="form-control" rows="6" >{{ old('contactus_message', app('request')->input('contactus_message', '')) }}</textarea>
                            @if($errors->has('contactus_message'))
                            <span class="error">{{$errors->first('contactus_message')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p>Informationen zur Verarbeitung Deiner personenbezogenen Daten im Zusammenhang mit dem Verfassen eines Kommentares findest Du in unserer <a href="{{ route('privacy') }}">Datenschutzerklärung.</a></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <button type="submit" class="btn mb-2 global-btn btn-block">Einreichen</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection