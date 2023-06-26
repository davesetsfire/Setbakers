<div class="modal fade project-popup" id="project-detail">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-0">
            <div class="model_disabled_container_class"></div>
            <button class="close btn" data-toggle="modal" data-target="#project-detail"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <section class="product-detail-section">
                    <div class="container">
                        <div class="popup-content-minheight">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="product-img">
                                        <div class="main-img">
                                            <a id="product-detail-fancy-image" href="{{ asset('assets/images/image-loader.gif') }}" data-fancybox="images">
                                                <img id="product-detail-image" src="{{ asset('assets/images/image-loader.gif') }}">
                                            </a>
                                        </div>
                                        <div class="product-thumns">                                      
                                            <div class="row product_image_view_row">

                                            </div>
                                        </div>
                                        @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                                        <div class="media-look media-look-desktop">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <a class="product-detail-fundus-store" href="" target="_blank"><img class="w-100 product-detail-fundus-logo" src="{{ asset('assets/images/lm-logo.png') }}"></a>
                                                </div>
                                                <div class="col-sm-8">
                                                    <a class="product-detail-fundus-store" href="" target="_blank"><h6 class="product-detail-fundus-name"></h6></a>
                                                    <p class="product-detail-fundus-location"></p>     
                                                    <div class="email dls"><a class="product-detail-fundus-email" href="mailto:"></a></div>
                                                    <div class="phone dls product-detail-fundus-phone"></div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="product-detail product-detail-new">
                                        @if(Auth::check() && !isset($userImpressions['hintbox-motive']))
                                            <div class="hint-box-global hint-check-top-right border-top-right-radius user-impression-block">
                                                <div class="hint-check">
                                                    <div class="option">
                                                        <label>
                                                            Now we're talking
                                                            <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-motive" data-impression-value="yes">
                                                            <span class="checkmarks"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <p>{{ __('hintbox.PRODUCT_DETAILS_HINT') }}</p>
                                            </div>
                                        @endif
                                        <div class="popup-content-minheight">
                                            <form action="" method="POST" id="product_bookmark_form">
                                                @if(
                                                ((Auth::check() && Auth::user()->account_type == 'complete') || !Auth::check()) 
                                                && ($showBookmarkSection ?? true)
                                                )
                                                <div class="quantity-btns">

                                                    @csrf
                                                    <input type="hidden" id="product-detail-slug" name="bookmark_product_slug" value="">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <div class="product-count">
                                                                <div class="counter">
                                                                    <button type="button" class="btn decrement">-</button>
                                                                    <input type="number" class="form-control" name="requested_count" value="1">
                                                                    <button type="button" class="btn increment" data-available="1" id="product-detail-requested_count">+</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <select class="select2-multiple-pr form-control" id="project-detail-favourite" name="favourite[]" data-width="100%" multiple="multiple">
                                                                @if(isset($favourites) && count($favourites) > 0)
                                                                @foreach($favourites as $favourite)
                                                                <option value="{{ $favourite->id }}">{{ $favourite->name }}</option>
                                                                @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <button class="btn global-btn add-bookmark-button" 
                                                                    @guest
                                                                    data-toggle="modal" data-target="#fav-popup"
                                                                    @endguest
                                                                    ><div class="spinner-border button_spinner"></div> HINZUFÜGEN</button>
                                                        </div>
                                                        <label id="requested_count-error" class="error" for="requested_count"></label>
                                                        <label id="requested_count-success" class="success"></label>
                                                    </div>

                                                </div>
                                                @endif
                                            </form>
                                            <h4 id="product-detail-name"></h4>
                                            <div class="description">
                                                <p id="product-detail-description"></p>
                                            </div>
                                            <div class="dls">
                                                <div class="dls-lft">
                                                    <h6>Kategorie</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-category"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Verfügbarkeit</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-graphic_form"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Marke</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-manufacturer_id"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Menge</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-quantity"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Herstellerland</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-manufacture_country"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field preis_dls">

                                                <h6 id="product-detail-price">Preis</h6>
                                                <div class="price-row-parent" id="price-row-parent">
                                                    
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Abmessungen (LxBxH)</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-dimensions"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Format</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-file_format"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Rechte</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-copy_right"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Epoche</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-epoche"></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Farbe</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p style="display: flex;"><span class="color" id="product-detail-color"></span><span id="product-detail-color-name" style="margin-left: 10px"></span></p>
                                                </div>
                                            </div>
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Stil</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-style"></p>
                                                </div>
                                            </div>
                                            @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                                            <div class="dls article-field">
                                                <div class="dls-lft">
                                                    <h6>Ort</h6>
                                                </div>
                                                <div class="dls-rgt">
                                                    <p id="product-detail-location_at"></p>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                                        <div class="product-img media-look-mobile">
                                            <div class="media-look">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <a class="product-detail-fundus-store" href="" target="_blank"><img class="w-100 product-detail-fundus-logo" src="{{ asset('assets/images/lm-logo.png') }}"></a>
                                                    </div>
                                                    <div class="col-sm-8">
                                                        <a class="product-detail-fundus-store" href="" target="_blank"><h6 class="product-detail-fundus-name"></h6></a>
                                                        <p class="product-detail-fundus-location"></p>
                                                        <div class="email dls"><a class="product-detail-fundus-email" href="mailto:"></a></div>
                                                        <div class="phone dls product-detail-fundus-phone"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                    @if(Auth::check())
                                    <div class="article-dls artikelnummer-bottomfix">
                                        {{-- <p class="article-field">verfügbar <span id="product-detail-quantity"></p> --}}
                                        <p>Artikelnummer: <span id="product-detail-code"></span></p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @guest
                        <div class="register-login product-details-bottom">
                            <h5><span>Nach der Anmeldung stehen Dir alle Kontaktinformationen zur Verfügung</span></h5>
                            <div class="btns">
                                <a href="#" class="global-btn style2 open-registration">{{ __('lang.register') }}</a>
                                <a href="#" class="global-btn open-login">{{ __('lang.sign_in') }}</a>
                            </div>
                        </div>
                        @endguest
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal fade global-modal" id="fav-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#fav-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body btnright">
                <h4>Favoritenliste</h4>
                <p>Bitte melde Dich mit einem Projektkonto an, um die 
                    Favoriten-Funktion nutzen zu können.</p>
                <div class="btns text-right">
                    <button type="button" class="btn global-btn style2 open-registration">{{ __('lang.register') }}</button>
                    <button type="button" class="btn global-btn open-login">{{ __('lang.sign_in') }}</button>
                </div>
            </div>

        </div>
    </div>
</div>