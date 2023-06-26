<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />    
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf_token" content="{{ csrf_token() }}">
        <title>SetBakers</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @env('production')
        <meta name="google-site-verification" content="4GDH0J4GVCySfdLvcpoP0gx22zcrB8Tt_eVKcsXiaIk" />
        @else
        <meta name="robots" content="noindex, nofollow">
        @endenv 
        
        <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">
        <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/material-design-iconic-font.min.css') }}"/>    
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/bootstrap.min.css') }}"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/owl.carousel.min.css') }}"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/select2.min.css') }}"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/jquery.fancybox.min.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/daterangepicker.css') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/cropper.min.css') }}"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/style.css') }}"/>
        <link rel="stylesheet" type="text/css" media="screen" href="{{ asset('assets/css/responsive.css') }}"/>
        <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/row-grid.min.js') }}"></script>
        <script src="{{ asset('assets/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/js/moment.min.js') }}"></script>
        <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.fancybox.min.js') }}"></script>
        <script src="{{ asset('assets/js/validate.min.js') }}"></script>
        <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('assets/js/cropper.min.js') }}"></script>
        <script src="{{ asset('assets/js/custom.js') }}"></script>
        @env('production')
        
        @if($cookieAnalyse)
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-4S1J9ML5L1"></script>
        <script>
window.dataLayer = window.dataLayer || [];
function gtag() {
    dataLayer.push(arguments);
}
gtag('js', new Date());

gtag('config', 'G-4S1J9ML5L1');
        </script>
        @endif
        
        @endenv 
    </head>
    <body>
        <main class="main-container">
            <input type="hidden" id="website_url" value="{{ config('app.url') }}">
            <input type="hidden" id="img_url" value="{{ config('app.website_media_base_url') }}">
            @guest        
            <header class="top-header">
                <div class="header-inner">
                    <div class="container-fluid">
                        <div class="head-wrap">
                            <div class="header-left">
                                <a href="{{ route('index') }}"><img class="logo" src="{{ asset('assets/images/logo.svg') }}"></a>
                            </div>
                            <div class="head-right">
                                <a href="#" class="open-login">{{ __('lang.sign_in') }}</a>
                                <a href="#" class="open-registration">{{ __('lang.register') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @yield('guest-submenu')
            </header>
            @else
            <header class="top-header header-loggedin">
                <div class="header-inner">
                    <div class="container-fluid">
                        <div class="head-wrap">
                            <div class="header-left">
                                <a href="{{ route('index') }}"><img class="logo" src="{{ asset('assets/images/logo.svg') }}"></a>
                            </div>
                            <button class="btn menu-toggle-btn">
                                <div id="nav-icon2" class="menu-toggler">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </button>
                            <div class="head-right">
                                <a href="#home" 
                                   class="{{ Route::currentRouteNamed( 'product.category' ) ?  'active-icon' : '' }}">
                                    <i class="zmdi zmdi-home"></i>
                                </a>
                                @if(!empty(Auth::user()->fundusDetail))
                                <a href="#fundus" 
                                   class="store-icon {{ Route::currentRouteNamed( 'fundus.inquiries.index' ) || Route::currentRouteNamed( 'fundus.index' ) ?  'active-icon' : '' }}">
                                    {{-- <i class="zmdi zmdi-store"></i> --}}
                                </a>
                                @endif
                                @if( Auth::user()->account_type != 'fundus' )
                                <a href="#favourite" 
                                   class="{{ Route::currentRouteNamed( 'favourites.theme' ) || Route::currentRouteNamed( 'favourites.fundus' ) ?  'active-icon' : '' }}">
                                    <i class="zmdi zmdi-star"></i>
                                </a>
                                @endif
                                <a href="#account" 
                                   class="{{ Route::currentRouteNamed( 'data.show' ) ?  'active-icon' : '' }}">
                                    <i class="zmdi zmdi-account-o"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @yield('submenu') --}}
                <div class="logged-in-menu" id="hover-menu">
                    <div class="container-fluid">
                        <div class="menu-wrapper">
                            <ul class="menu-items" id="home">
                                <li><a href="#" class="{{ Route::currentRouteNamed( 'product.category' ) ?  'active-icon' : '' }}"><i class="zmdi zmdi-home"></i></a></li>
                                <li><a href="{{ route('product.category', ['category' => '']) }}">{{ __('lang.search') }}</a></li>
                            </ul>
                            @if(!empty(Auth::user()->fundusDetail))
                            <ul class="menu-items" id="fundus">
				<li><a href="#" class="store-icon {{ Route::currentRouteNamed( 'fundus.inquiries.index' ) || Route::currentRouteNamed( 'fundus.index' ) ?  'active-icon' : '' }}">{{-- <i class="zmdi zmdi-store"></i> --}}</a></li>
                                <li><a href="{{ route('fundus.index') }}">{{ __('lang.my_fundus') }}</a></li>
                                <li><a href="{{ route('fundus.inquiries.index') }}">{{ __('lang.my_requests') }}</a></li>
                            </ul>
                            @endif
                            @if( Auth::user()->account_type != 'fundus' )
                            <ul class="menu-items" id="favourite">
                                <li><a href="#" class="{{ Route::currentRouteNamed( 'favourites.theme' ) || Route::currentRouteNamed( 'favourites.fundus' ) ?  'active-icon' : '' }}"><i class="zmdi zmdi-star"></i></a></li>
                                <li><a href="{{ route('favourites.theme') }}">{{ __('lang.favourite_by_theme') }}</a></li>
                                <li><a href="{{ route('favourites.fundus') }}">{{ __('lang.favourite_by_fundus') }}</a></li>
                            </ul>
                            @endif
                            <ul class="menu-items" id="account">
                                <li><a href="#" class="{{ Route::currentRouteNamed( 'data.show' ) ?  'active-icon' : '' }}"><i class="zmdi zmdi-account-o"></i></a></li>
                                <li><a href="{{ route('data.show', [0]) }}">{{ __('lang.my_data') }}</a></li>
                                <li>
                                    <a href="#" class="logout-button">{{ __('lang.logout') }}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            @endguest
            <div class="loader-container">
                <div class="loader3">
                    <span></span><span></span>
                </div>
            </div>
            <div class="logo-container">
                <div class="container">
                    <a href="{{ route('index') }}">&nbsp;</a>
                </div>
            </div>
            @yield('content')
            <footer class="footer-section {{ !$cookieConsent ? 'footer-space' : '' }}">
                <div class="container">
                    @yield('more-article-login-restriction')
                    <ul class="footer-links">
                        <li><a href="{{ route('imprint') }}">Impressum</a></li>
                        <li><a href="{{ route('terms') }}">AGB</a></li>
                        <li><a href="{{ route('contact') }}">Kontakt</a></li>
                        <li><a href="{{ route('privacy') }}">Datenschutz</a></li>
                        <li><a href="{{ route('cancellation') }}">Widerrufsbelehrung</a></li>
                        <li><a href="{{ route('faq') }}">FAQ</a></li>
                    </ul>
                </div>
            </footer>
        </main>
        @if(!$cookieConsent)
        @include('partials.cookies-bar')
        @endif
        <!-- crop image popup -->
        <div class="modal fade global-modal" id="cropImage">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <h3>Profilbild zuschneiden</h3>
                    <div class="modal-body p-0">
                        <div class="profile-upload-result"></div>
                    </div>

                    <div class="modal-footer p-0">
                        <button type="button" class="btn global-btn mt-2 style2" id="cancelProfileCrop" data-dismiss="modal" value="Crop">Abbrechen</button>
                        <button type="button" class="btn global-btn mx-0 mt-2" id="cropProfilePic" value="Crop">Übernehmen</button>
                    </div>

                </div>
            </div>
        </div>
        <!-- crop image end -->

        <div class="modal fade global-modal welcome" id="welcome-user">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <button class="close btn mt-0 p-0" data-toggle="modal" data-target="#welcome-user"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
                    <div class="modal-body">
                        <h4>Willkommen auf SetBakers,</h4>
                        <p>Wir sind in den letzten Zügen! Bald schon wird Dir SetBakers mit allen Funktionen zur Verfügung stehen.</p> 

                        <p>Schaue demnächst nochmal vorbei!</p>

                        <div class="text-right">
                            <button type="button" class="btn global-btn" data-toggle="modal" data-target="#welcome-user">Ok, bis später!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.google-places-script')
        @include('partials.product-detail')

        @guest

        @include('partials.login')
        @include('partials.register')
        @include('partials.forgot-password')

        @else

        @include('users.partials.change-password')
        @yield('modal-windows')

        @endguest

        @include('partials.payment')
        @stack('bottom')

        @auth

        @if (Session::has('showPaymentPopup'))
        <script>
            $(function () {
                openPaymentPopup();
            });
        </script>
        @endif

        @endauth
        

        @if (Session::has('showInformationModal'))
    <x-information-modal modalId="informationModal" :modalHeading="Session::get('modalHeading')" :modalMessage="Session::get('modalMessage')"/>
    <script>
        displayInformationModal = "informationModal";
    </script>
    @endif
    <script type="text/javascript">
        $(function () {
            slideShow();
            function slideShow() {

                var current = $('.custom-img-slider .show');
                var next = current.next().length ?
                        current.next() :
                        current.siblings().first();
                current.hide().removeClass('show');
                next.fadeIn("slow").addClass('show');
                setTimeout(slideShow, 3000);
            }


            $('.index-page-child-menu li a').click(function (event) {
                event.preventDefault();
                var ids = $(this).attr('href');
                $('html,body').animate({
                    scrollTop: $(ids).offset().top
                }, 500);
            });

            $('body').on('hidden.bs.modal', function () {
                if($('.modal.show').length > 0)
                {
                    $('body').addClass('modal-open');
                }
            });

        });
    </script>
</body>
</html>