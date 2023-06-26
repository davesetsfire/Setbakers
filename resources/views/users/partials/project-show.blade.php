<div class="col-md-6">
    <span class="error success errors-email" style="text-align: left">{{ session('project_success', '') }}</span>
    <span class="error errors-email" style="text-align: left; position: absolute;top: -40px;font-size: 16px !important;">{{ session('project_error', '') }}</span>
    <div class="data-wrap">
        <h4>MEIN PROJEKT-KONTO 
            @if(Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail))
                @if(isset($projectDetail->subscription['type']) && $projectDetail->subscription['type'] == 'recurring')
                    @if(!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                        @if(isset($projectDetail->is_subscription_paused) && $projectDetail->is_subscription_paused == 1)
                            <i class="title_right_text">
                                {{ !empty($projectDetail->subscription_end_date) ? 'Aktiv bis zum '. date('d.m.Y', strtotime($projectDetail->subscription_end_date)) : '' }}
                            </i>
                        @else
                            <i class="title_right_text">
                                {{ !empty($projectDetail->subscription_end_date) ? 'Abo verlängert sich am '. date('d.m.Y', strtotime($projectDetail->subscription_end_date)) : '' }}
                            </i>
                        @endif
                    @else
                        <i class="title_right_text">
                            Konto inaktiv
                        </i>
                    @endif
                @elseif(isset($projectDetail->subscription['type']) && $projectDetail->subscription['type'] == 'onetime')
                    @if(!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                        <i class="title_right_text">
                            {{ !empty($projectDetail->subscription_end_date) ? 'Aktiv bis zum '. date('d.m.Y', strtotime($projectDetail->subscription_end_date)) : '' }}
                        </i>
                    @else
                        <i class="title_right_text">
                            Konto inaktiv
                        </i>
                    @endif
                @else 
                    <i class="title_right_text">
                        Konto inaktiv
                    </i>
                @endif
            @endif
        </h4>
        @if(Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail))
        <div class="row min-height-800 data_fundus">
            <div class="col-md-8">
                <div class="data">
                    <p>{{ Auth::user()->email ?? '' }}</p>
                    <span>E-Mail-Adresse</span>
                </div>
                <div class="data">
                    <p>{{ $projectDetail->project_name ?? '' }}</p>
                    <span>Projektname</span>
                </div>
                <div class="data">
                    <p>{{ Auth::user()->phone_number ?? '' }}</p>
                    <span>Telefonnummer</span>
                </div>
                <div class="data">
                    <p>{{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}</p>
                    <span>Name</span>
                </div>
                @if($projectDetail->is_company ?? 1)
                <div class="data">
                    <p>{{ $projectDetail->company_name ?? '' }}</p>
                    <span>Firma</span>
                </div>
                <div class="data">
                    <p>{{ $projectDetail->ust_id ?? ''}}</p>
                    <span>USt-ID</span>
                </div>
                @endif
                <div class="data">
                    <p>{{ $projectDetail->street ?? '' }} {{ $projectDetail->house_number ?? '' }}</p>
                    <span>Straße, Hausnummer</span>
                </div>
                <div class="data">
                    <p>{{ $projectDetail->postal_code ?? '' }} {{ $projectDetail->location ?? '' }}</p>
                    <span>Postleitzahl, Ort</span>
                </div>
                <div class="data">
                    <p>{{ $projectDetail->country ?? '' }}</p>
                    <span>Land</span>
                </div>
            </div>
            <!--<div class="col-md-4">
                <div class="profile-wrapper">
                    <div class="profile-pic">
                        <img src="{{ asset('assets/images/lm-logo.png') }}">
                    </div>
                </div>
            </div>-->
        </div>
        <div class="btns">
            @if(isset($projectDetail->subscription['type']) && $projectDetail->subscription['type'] == 'recurring')
                @if(!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                    @if(isset($projectDetail->is_subscription_paused) && $projectDetail->is_subscription_paused == 1)
                        <!-- Start subscription without payment dialog box -->
                        <a href="{{ route('project.unpause') }}" class="btn global-btn style2" style="margin-right: 10px;">Abo fortsetzen</a>
                    @else
                        <button type="button" class="btn global-btn style2" data-toggle="modal" data-target="#pause-project">Abo kündigen</button>
                    @endif
                @else
                    <!-- Open project dialog and then payment dialog box -->
                    <button type="button" class="btn global-btn style2 open-new-project-option-popup">Konto aktivieren</button>
                @endif
            @elseif(isset($projectDetail->subscription['type']) && $projectDetail->subscription['type'] == 'onetime')
                @if(!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                    <!-- Open payment dialog box with future subscription date -->
                    <button type="button" class="btn global-btn style2 open-payment-popup">Zeitraum verlängern</button>
                @else
                    <!-- Open project dialog and then payment dialog box -->
                    <button type="button" class="btn global-btn style2 open-new-project-option-popup">Konto aktivieren</button>
                @endif
            @else
                <!-- Open payment dialog box -->
                <button type="button" class="btn global-btn style2 open-payment-popup">Konto aktivieren</button>
            @endif
            <a href="{{ route('data.edit', ['project']) }}" class="global-btn">Bearbeiten</a>
        </div>
        @else
        <div class="row min-height-800">
            <div class="col-md-12">Derzeit hast Du kein Leih-Konto</div>
        </div>
        <div class="btns">
            <a href="#" class="global-btn open-project-upgrade-popup">Leih-Konto hinzufügen</a>
        </div>
        @endif
    </div>
</div>
