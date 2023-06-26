<div class="col-md-6">
    <div class="data-wrap edit-wrap" id="edit-store-screen">
        <form action="{{ route('data.update', ['fundus']) }}" method="POST" enctype="multipart/form-data">
            <input name="_method" type="hidden" value="PUT">
            <input name="is_company" type="hidden" value="{{ $fundusDetail->is_company ?? 1 }}">
            @csrf
            <h4 class="gray">MEIN FUNDUS-KONTO
                {{ isset($fundusDetail->package_type) && $fundusDetail->package_type == 'pro' ? 'PRO' : '' }}
                {{ isset($fundusDetail->package_type) && $fundusDetail->package_type == 'infinite' ? 'INFINITE' : '' }}
            </h4>
            <div class="row min-height-800 data_user">
                <div class="col-md-8">
                    <div class="data">
                        @if($errors->has('fundus_name'))
                        <span class="error">{{$errors->first('fundus_name')}}</span>
                        @endif
                        <input type="text" name="fundus_name" class="form-control" placeholder="Fundusname" value="{{ old('fundus_name', app('request')->input('fundus_name', $fundusDetail->fundus_name ?? '')) }}">
                        <span>Fundusname</span>
                    </div>
                    <div class="data">
                        @if($errors->has('fundus_email'))
                        <span class="error">{{$errors->first('fundus_email')}}</span>
                        @endif
                        <input type="email" name="fundus_email" class="form-control" placeholder="E-Mail-Adresse:" value="{{ old('fundus_email', app('request')->input('fundus_email', $fundusDetail->fundus_email ?? '')) }}">
                        <span>E-Mail-Adresse</span>
                    </div>
                    <div class="data">
                        @if($errors->has('fundus_phone'))
                        <span class="error">{{$errors->first('fundus_phone')}}</span>
                        @endif
                        <input type="text" name="fundus_phone" class="form-control" placeholder="Telefonnummer (optional)" value="{{ old('fundus_phone', app('request')->input('fundus_phone', $fundusDetail->fundus_phone ?? '')) }}">
                        <span>Telefonnummer</span>
                    </div>
                    <div class="data">
                        @if($errors->has('owner_first_name'))
                        <span class="error">{{$errors->first('owner_first_name')}}</span>
                        @endif
                        <input type="text" name="owner_first_name" class="form-control" placeholder="Inhaber Vorname" value="{{ old('owner_first_name', app('request')->input('owner_first_name', $fundusDetail->owner_first_name ?? '')) }}">
                        <span>Inhaber Vorname</span>
                    </div>
                    <div class="data">
                        @if($errors->has('owner_last_name'))
                        <span class="error">{{$errors->first('owner_last_name')}}</span>
                        @endif
                        <input type="text" name="owner_last_name" class="form-control" placeholder="Inhaber Nachname" value="{{ old('owner_last_name', app('request')->input('owner_last_name', $fundusDetail->owner_last_name ?? '')) }}">
                        <span>Inhaber Nachname</span>
                    </div>
                    <div class="data">
                        @if($errors->has('website'))
                        <span class="error">{{$errors->first('website')}}</span>
                        @endif
                        <input type="text" name="website" class="form-control" placeholder="Website" value="{{ old('website', app('request')->input('website', $fundusDetail->website ?? '')) }}">
                        <span>Website</span>
                    </div>
                    @if($fundusDetail->is_company ?? 1)
                    <div class="data">
                        @if($errors->has('company_name'))
                        <span class="error">{{$errors->first('company_name')}}</span>
                        @endif
                        <input type="text" name="company_name" class="form-control" placeholder="Firma" value="{{ old('company_name', app('request')->input('company_name', $fundusDetail->company_name ?? '')) }}">
                        <span>Firma</span>
                    </div>
                    <div class="data">
                        @if($errors->has('ust_id'))
                        <span class="error">{{$errors->first('ust_id')}}</span>
                        @endif
                        <input type="text" name="ust_id" class="form-control" placeholder="USt-ID" value="{{ old('ust_id', app('request')->input('ust_id', $fundusDetail->ust_id ?? '')) }}">
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
                                    <input type="text" name="street" class="form-control" placeholder="STrasse" value="{{ old('street', app('request')->input('street', $fundusDetail->street ?? '')) }}">
                                    <span>STrasse</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="data">
                                    @if($errors->has('house_number'))
                                    <span class="error">{{$errors->first('house_number')}}</span>
                                    @endif
                                    <input type="text" name="house_number" class="form-control" placeholder="Hausnummer" value="{{ old('house_number', app('request')->input('house_number', $fundusDetail->house_number ?? '')) }}">
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
                                    <input type="text" id="fundus_postal_code" name="postal_code" class="form-control" placeholder="Postleitzahl" value="{{ old('postal_code', app('request')->input('postal_code', $fundusDetail->postal_code ?? '')) }}">
                                    <span>Postleitzahl</span>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="data">
                                    @if($errors->has('location'))
                                    <span class="error">{{$errors->first('location')}}</span>
                                    @endif
                                    <input type="hidden" id="fundus_geo_location" name="geo_location" value="{{ old('geo_location', app('request')->input('geo_location', $fundusDetail->geo_loc ?? '')) }}" />
                                    <input type="text" id="fundus_location" name="location" class="form-control" placeholder="Ort" value="{{ old('location', app('request')->input('location', $fundusDetail->location ?? '')) }}">
                                    <span>Ort</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="data">
                        @if($errors->has('country'))
                        <span class="error">{{$errors->first('country')}}</span>
                        @endif
                        <input type="text" id="fundus_country" name="country" class="form-control" placeholder="Land" value="{{ old('country', app('request')->input('country', $fundusDetail->country ?? '')) }}">
                        <span>Land</span>
                    </div>
                    <div class="data mb-0">
                        @if($errors->has('description'))
                        <span class="error">{{$errors->first('description')}}</span>
                        @endif
                        <textarea name="description" id="fundus_description_user" class="form-control" placeholder="Über Fundus">{{ old('description', app('request')->input('description', $fundusDetail->description ?? '')) }}</textarea>
                        <div class="word-count">
                            <span>Informationen zu Deinem Fundus</span>
                            <div class="words"><span id="word_left">400</span>/400</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="profile-wrapper">
                        <div class="profile-pic">
                            <img src="{{ !empty($fundusDetail->logo_image_path) ? config('app.website_media_base_url') . $fundusDetail->logo_image_path : asset('assets/images/lm-logo.png') }}" class="cropped">
                            <button type="button" onclick="deleteProfilePic(this)" class="btn delete-img-btn"><i class="zmdi zmdi-close"></i></button>
                        </div>
                        <label class="change-profile-pic">
                            <input type="file" id="file-input" name="fundus_profile_picture_tmp" accept="image/*">
                            Profilbild ändern
                            <input type="file" id="fundus_profile_picture" name="fundus_profile_picture" accept="image/*">
                        </label>
                    </div>
                </div>
            </div>
            <div class="btns">
                <button type="submit" class="btn global-btn">Speichern</button>
            </div>
        </form>
    </div>
</div>