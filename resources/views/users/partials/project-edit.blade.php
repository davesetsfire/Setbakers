<div class="col-md-6">
    <div class="data-wrap edit-wrap">
        <form action="{{ route('data.update', ['project']) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PUT">
            <input name="is_company" type="hidden" value="{{ $projectDetail->is_company }}">
            @csrf
            <h4 class="gray">MEIN LEIH-KONTO</h4>
            <div class="row min-height-800 data-fundus">
                <div class="col-md-8">
                    <div class="data">
                        <p>{{ Auth::user()->email ?? '' }}</p>
                        <span>E-Mail-Adresse</span>
                    </div>
                    <div class="data">
                        @if($errors->has('project_name'))
                        <span class="error">{{$errors->first('project_name')}}</span>
                        @endif
                        <input type="text" name="project_name" class="form-control" placeholder="Projektname" value="{{ old('project_name', app('request')->input('project_name', $projectDetail->project_name ?? '')) }}">
                        <span>Projektname</span>
                    </div>
                    <div class="data">
                        @if($errors->has('phone_number'))
                        <span class="error">{{$errors->first('phone_number')}}</span>
                        @endif
                        <input type="text" name="phone_number" class="form-control" placeholder="Telefonnummer" value="{{ old('phone_number', app('request')->input('phone_number', Auth::user()->phone_number ?? '')) }}">
                        <span>Telefonnummer</span>
                    </div>
                    <div class="data">
                        @if($errors->has('first_name'))
                        <span class="error">{{$errors->first('first_name')}}</span>
                        @endif
                        <input type="text" name="first_name" class="form-control" placeholder="Vorname" value="{{ old('first_name', app('request')->input('first_name', Auth::user()->first_name ?? '')) }}">
                        <span>Vorname</span>
                    </div>
                    <div class="data">
                        @if($errors->has('last_name'))
                        <span class="error">{{$errors->first('last_name')}}</span>
                        @endif
                        <input type="text" name="last_name" class="form-control" placeholder="Nachname" value="{{ old('last_name', app('request')->input('last_name', Auth::user()->last_name ?? '')) }}">
                        <span>Nachname</span>
                    </div>
                    @if($projectDetail->is_company)
                    <div class="data">
                        @if($errors->has('company_name'))
                        <span class="error">{{$errors->first('company_name')}}</span>
                        @endif
                        <input type="text" name="company_name" class="form-control" placeholder="Firma" value="{{ old('company_name', app('request')->input('company_name', $projectDetail->company_name ?? '')) }}">
                        <span>Firma</span>
                    </div>
                    <div class="data">
                        @if($errors->has('ust_id'))
                        <span class="error">{{$errors->first('ust_id')}}</span>
                        @endif
                        <input type="text" name="ust_id" class="form-control" placeholder="USt-ID" value="{{ old('ust_id', app('request')->input('ust_id', $projectDetail->ust_id ?? '')) }}">
                        <span>USt-ID</span>
                    </div>
                    @endif
                    <div class="address">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="data">
                                    @if($errors->has('street'))
                                    <span class="error">{{$errors->first('street')}}</span>
                                    @endif
                                    <input type="text" name="street" class="form-control" placeholder="STrasse" value="{{ old('street', app('request')->input('street', $projectDetail->street ?? '')) }}">
                                    <span>STrasse</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="data">
                                    @if($errors->has('house_number'))
                                    <span class="error">{{$errors->first('house_number')}}</span>
                                    @endif
                                    <input type="text" name="house_number" class="form-control" placeholder="Hausnummer" value="{{ old('house_number', app('request')->input('house_number', $projectDetail->house_number ?? '')) }}">
                                    <span>Hausnummer</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="data">
                                    @if($errors->has('postal_code'))
                                    <span class="error">{{$errors->first('postal_code')}}</span>
                                    @endif
                                    <input type="text" name="postal_code" class="form-control" placeholder="Postleitzahl" value="{{ old('postal_code', app('request')->input('postal_code', $projectDetail->postal_code ?? '')) }}">
                                    <span>Postleitzahl</span>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="data">
                                    @if($errors->has('location'))
                                    <span class="error">{{$errors->first('location')}}</span>
                                    @endif
                                    <input type="text" name="location" class="form-control" placeholder="Ort" value="{{ old('location', app('request')->input('location', $projectDetail->location ?? '')) }}">
                                    <span>Ort</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="data mb-0">
                        @if($errors->has('country'))
                        <span class="error">{{$errors->first('country')}}</span>
                        @endif
                        <input type="text" name="country" class="form-control" placeholder="Land" value="{{ old('country', app('request')->input('country', $projectDetail->country ?? '')) }}">
                        <span>Land</span>
                    </div>
                </div>
                <!--<div class="col-md-4">
                    <div class="profile-wrapper">
                        <div class="profile-pic">
                            <img src="{{ asset('assets/images/lm-logo.png') }}">
                        </div>
                        <label class="change-profile-pic">
                            <input type="file" name="profile_picture" onchange="changeProfile(this)">
                            Profilbild ändern
                        </label>
                    </div>
                </div>-->
            </div>
            <div class="btns">
                <button type="submit" class="btn global-btn">Speichern</button>
            </div>
        </form>
    </div>
</div>