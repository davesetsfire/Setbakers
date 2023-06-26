<div class="col-md-6">
    <span class="error success errors-email" style="text-align: left">{{ session('fundus_success', '') }}</span>
    <span class="error errors-email" style="text-align: left; position: absolute;top: -40px;font-size: 16px !important;">{{ session('fundus_error', '') }}</span>
    <div class="data-wrap">
        <h4 class="gray">MEIN FUNDUS-KONTO 
            {{ isset($fundusDetail->package_type) && $fundusDetail->package_type == 'pro' ? 'PRO' : '' }}
            {{ isset($fundusDetail->package_type) && $fundusDetail->package_type == 'infinite' ? 'INFINITE' : '' }}
            <i class="title_right_text">{{ (isset($fundusDetail->is_paused) && $fundusDetail->is_paused) ? '(' . __('lang.paused') .')' : '' }}</i> 
            @if(isset($fundusDetail->id) && $fundusDetail->package_type != 'free')
            <i class="title_right_text upgraden_text open-upgrade-fundus-popup">Upgrades</i>
            @endif
        </h4>
        @if(!empty(Auth::user()->fundusDetail))
        <div class="row min-height-800 data_user">
            <div class="col-md-8">
                <div class="data">
                    <p>{{ $fundusDetail->fundus_name ?? '' }}</p>
                    <span>Fundusname</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->fundus_email ?? ''}}</p>
                    <span>E-Mail-Adresse</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->fundus_phone ?? '' }}</p>
                    <span>Telefonnummer</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->owner_first_name ?? ''}} {{ $fundusDetail->owner_last_name ?? ''}}</p>
                    <span>Inhaber</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->website ?? ''}}</p>
                    <span>Website</span>
                </div>
                @if($fundusDetail->is_company)
                <div class="data">
                    <p>{{ $fundusDetail->company_name ?? ''}}</p>
                    <span>Firma</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->ust_id ?? ''}}</p>
                    <span>USt-ID</span>
                </div>
                @endif
                <div class="data">
                    <p>{{ $fundusDetail->street ?? '' }}, {{ $fundusDetail->house_number ?? '' }}</p>
                    <span>STrasse, Hausnummer</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->postal_code ?? '' }}, {{ $fundusDetail->location ?? '' }}</p>
                    <span>Postleitzahl, Ort</span>
                </div>
                <div class="data">
                    <p>{{ $fundusDetail->country ?? '' }}</p>
                    <span>Land</span>
                </div>
                <div class="data">
                    @if(Auth::check() && !isset($userImpressions['hintbox-allesklar']))
                     <div class="hint-box-global border-bottom-left-radius user-impression-block">
                        <p>{{ __('hintbox.FUNDUS_ACCOUNT_HINT') }}</p>
                        <div class="hint-check">
                            <div class="option">
                                <label>
                                    Alles klar!
                                    <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-allesklar" data-impression-value="yes">
                                    <span class="checkmarks"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @endif
                    <p>{{ $fundusDetail->description ?? '' }}</p>
                    <span>Informationen zu Deinem Fundus</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="profile-wrapper">
                    <div class="profile-pic">
                        <img src="{{ !empty($fundusDetail->logo_image_path) ? config('app.website_media_base_url') . $fundusDetail->logo_image_path : asset('assets/images/lm-logo.png') }}">
                    </div>
                </div>
            </div>
        </div>
        <div class="btns csv-btns">
            <div class="upload-doc">
                <a href="#" class="open-bulk-upload-popup"><label><span class="icon"><img src="{{ asset('assets/images/icons/upload-icon.png') }}"></span> CSV Upload</label></a>
                <span class="name"></span>
            </div>
            <div class="btn-wrap">
                @if(!$fundusDetail->is_paused)
                <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#pause-funds">Fundus pausieren</button>
                @else
                <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#activate-funds">Fundus aktivieren</button>
                @endif
                <a href="{{ route('data.edit', ['fundus']) }}" class="global-btn">Bearbeiten</a>
            </div>
        </div>
        @else
        <div class="row min-height-800 data_user">
            <div class="col-md-12">Derzeit hast Du keinen Fundus</div>
        </div>
        <div class="btns">
            <a href="#" class="global-btn open-store-upgrade-popup">Fundus eröffnen</a>
        </div>
        @endif
    </div>
</div>