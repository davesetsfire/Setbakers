@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('guest-submenu')
@include('layouts.submenu.index')
@endsection

@section('content')


<section class="product-filter category-landing cat_menu_wrap pt-0">
    <div class="container">
        {{-- @include('partials.category-menu-bar') --}}
        @include('partials.search-bar', ['categoryLevel' => 1])

        @if(request()->has('searched_category_id'))
        @if(count($products) > 0)
        <div class="products-container
             {{-- @if(!(Auth::check() && Auth::user()->account_type == 'complete' && Auth::user()->projectDetail['subscription_end_date'] > date('Y-m-d H:i:s')))
             shadow_bottom
             @endif --}}
             item-hover">

            @foreach($products as $product)
            <div class="item-box">
                <div class="product-img">
                    <a href="#" class="open-product-detail-popup" 
                       data-product="{{ json_encode($product->only(['code','name','slug','location', 'postal_code', 'year', 'quantity', 'replacement_value', 'custom_price_available'])) }}"
                       data-color="{{ $product->color['option_value'] ?? '' }}" 
                       data-color-name="{{ $product->color['option_display'] ?? '' }}" 
                       data-style="{{ $product->style['option_display'] ?? '' }}" 
                       data-epoche="{{ $product->epocheText['option_display'] ?? '' }}"
                       data-graphic_form="{{ $product->graphicForm['option_display'] ?? '' }}"
                       data-manufacturer_id="{{ $product->manufacture['option_display'] ?? '' }}"
                       data-manufacture_country="{{ $product->manufactureCountry['option_display'] ?? '' }}"
                       data-file_format="{{ $product->fileFormat['option_display'] ?? '' }}"
                       data-copy_right="{{ $product->copyright['option_display'] ?? '' }}"
                       data-price="{{ $product->prices ?? [] }}"
                       data-dimensions="{{ $product->dimensions }}"
                       data-description="{{ $product->description }}" 
                       data-category="{{ $product->category_name }}"
                       data-parent-category-slug="{{ $product->top_category_slug }}"
                       data-image="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" 
                       data-media="{{ isset($product->productMedia) ? $product->productMedia->pluck('file_name') : [] }}"
                       @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                       data-fundus-name="{{ $product->fundus_name }}" 
                       data-fundus-email="{{ $product->fundus_email }}" 
                       data-fundus-phone="{{ $product->fundus_phone }}" 
                       data-fundus-location="{{ $product->fundus_location }}"
                       data-fundus-store="{{route('store.index',[$product->fundus_name]) }}"
                       data-fundus-logo="{{ !empty($product->logo_image_path) ? config('app.website_media_base_url') . $product->logo_image_path : asset('assets/images/lm-logo.png') }}"
                       @endif
                       >
                        <img src="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" height="250">
                    </a>
                    @if((Auth::check() && Auth::user()->account_type == 'complete') || !Auth::check())
                    <button type="button" class="wishlist {{ !empty($product->bookmark) ? 'active' : '' }}" data-slug="{{ $product->slug }}"><i class="zmdi zmdi-star"></i></button>
                    @endif
                </div>
                <div class="product-detail">
                    <div class="title-year">
                        <span class="year">{{ !empty($product->year) ? $product->year : ($product->epocheText['option_display'] ?? '') }}</span>
                        <h6 class="title">{{ $product->name ?? ''}}</h6>
                    </div>
                    <div class="status-count" style="{{ !in_array('quantity', $categoryWiseFields[$product->top_category_slug]) ? 'display:none;'  : ''}}">
                        <span class="status"></span>
                        <span class="count">{{ $product->quantity ?? '0'}}</span>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
        @else
        <div class="no-article-search-result">Deine Suche ergab keine Treffer</div>
        @endif
            {{ $products->onEachSide(1)->appends(collect(request()->all())->map(function($item, $key) {
            return is_null($item) && $key == 'searched_category_id' ? 0 : $item;
        })->toArray())->links() }}
        @endif
    </div>
</section>

@if(!request()->has('searched_category_id'))
<section class="home-first-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="head">
            <h1 class="text-uppercase">Leihe und Verleihe </h1>
            <h6>für Film, Messe und Event</h6>
          </div>
        </div>
      </div>
      <div class="row align-items-center rowheight">
        <div class="col-lg-6">
          <div class="home-section-leftcontent">
              <ul class="list-unstyled m-0 p-0">
                <li>Requisiten und Einrichtung</li>
                <li>Grafiken</li>
                <li>Dienstleistungen</li>
                <li>Fahrzeuge</li>
              </ul>
          </div>
        </div>
        <div class="col-lg-6 align-self-center">
                <div class="leihen-images text-center custom-img-slider">
                    @if(count($featuredProducts) > 0)
                    @foreach($featuredProducts as $index => $featuredProduct)

                    <a href="#" class="open-product-detail-popup {{ ($index == 0) ? 'show' : '' }}" 
                       data-product="{{ json_encode(collect($featuredProduct->product)->only(['code','name','slug','location', 'postal_code', 'year', 'quantity', 'replacement_value', 'custom_price_available'])) }}"
                       data-color="{{ $featuredProduct->product['color']['option_value'] ?? '' }}" 
                       data-color-name="{{ $featuredProduct->product['color']['option_display'] ?? '' }}" 
                       data-style="{{ $featuredProduct->product['style']['option_display'] ?? '' }}" 
                       data-epoche="{{ $featuredProduct->product['epocheText']['option_display'] ?? '' }}"
                       data-graphic_form="{{ $featuredProduct->product['graphicForm']['option_display'] ?? '' }}"
                       data-manufacturer_id="{{ $featuredProduct->product['manufacture']['option_display'] ?? '' }}"
                       data-manufacture_country="{{ $featuredProduct->product['manufactureCountry']['option_display'] ?? '' }}"
                       data-file_format="{{ $featuredProduct->product['fileFormat']['option_display'] ?? '' }}"
                       data-copy_right="{{ $featuredProduct->product['copyright']['option_display'] ?? '' }}"
                       data-price="{{ $featuredProduct->product['prices'] ?? '' }}"
                       data-dimensions="{{ $featuredProduct->product['dimensions'] ?? '' }}"
                       data-description="{{ $featuredProduct->product['description'] ?? '' }}" 
                       data-category="{{ $featuredProduct->product['category_name'] ?? '' }}"
                       data-parent-category-slug="{{ $featuredProduct->product['top_category_slug'] ?? '' }}"
                       data-image="{{ !empty($featuredProduct->product['image']) ? config('app.website_media_base_url') . $featuredProduct->product['image'] : ''}}" 
                       data-media="{{ isset($featuredProduct->product['productMedia']) ? $featuredProduct->product['productMedia']->pluck('file_name') : '' }}"
                       @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                       data-fundus-name="{{ $featuredProduct->product['fundusDetail']['fundus_name'] ?? '' }}" 
                       data-fundus-email="{{ $featuredProduct->product['fundusDetail']['fundus_email'] ?? '' }}" 
                       data-fundus-phone="{{ $featuredProduct->product['fundusDetail']['fundus_phone'] ?? '' }}" 
                       data-fundus-location="{{ $featuredProduct->product['fundusDetail']['fundus_location'] ?? '' }}"
                       data-fundus-store="{{route('store.index',[$featuredProduct->product['fundusDetail']['fundus_name'] ?? '']) }}"
                       data-fundus-logo="{{ !empty($featuredProduct->product['fundusDetail']['logo_image_path']) ? config('app.website_media_base_url') . $featuredProduct->product['fundusDetail']['logo_image_path'] : asset('assets/images/lm-logo.png') }}"
                       @endif
                       >
                        <img src="{{ config('app.website_media_base_url') . $featuredProduct->product['image'] }}">
                    </a>
                    @endforeach
                    @else
                    <img src="{{ asset('assets/images/leihen-img1.jpg') }}" class="show">
                    <img src="{{ asset('assets/images/leihen-img1.jpg') }}">
                    <img src="{{ asset('assets/images/leihen-img1.jpg') }}">
                    @endif
                </div>
            </div>
      </div>
    </div>
</section>
@endif

<section class="home-second-section">
  <div class="container-fluid no-gutters">
    <div class="row no-gutters">
      <div class="col-sm-5">
        <div class="hss-left text-right">
            <div class="hss-left-content">
              <img src="{{ asset('assets/images/logo-zetify-black.png') }}" alt="" class="img-fluid">
              <p>Professionelle Fundi und private Kellerschätze aus ganz Deutschland, gebündelt an einem Ort.</p>
            </div>
        </div>
      </div>
      <div class="col-sm-7">
        <div class="hss-right">
          <div class="hss-right-content">
            <h3 class="text-white">Du stattest ein Filmprojekt, eine Messe oder ein Event aus? <br> Oder Du verleihst genau das, was dafür gebraucht wird?</h3>
            <p>Dann ist SetBakers Deine Plattform. <br> Anstatt mühsamer Recherchen, gibt es hier Möbel, Requisiten, Dienstleister, Grafiken und Fahrzeuge, filterbar und gut sortiert.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="rent-section home-steps" id="leihen">
    <h2 class="heading-global">LEIHEN</h2>
    <div class="container">
      <div class="home-steps-number">
        <ul class="list-unstyled d-flex">
          <li>
            <h4>1</h4>
            <p>Projekt-Account anlegen</p>
          </li>
          <li>
            <h4>2</h4>
            <p>Individuelle Motive mit Drehzeiten erstellen</p>
          </li>
          <li>
            <h4>3</h4>
            <p>Artikel finden und Motiven zuordnen</p>
          </li>
          <li>
            <h4>4</h4>
            <p>Abholung und Rückgabe definieren</p>
          </li>
          <li>
            <h4>5</h4>
            <p>Fundus direkt mit allen 
Details anfragen</p>
          </li>
        </ul>
      </div>
    </div>
</section>
<section class="track-section">
    <div class="container container-width-extend">
        <div class="row no-gutters b1_block">
            <div class="col-md-4">
                <div class="content">
                    <div class="box">
                      <h3>Behalte den Überblick</h3>
                        <p>mit Merklisten, unterteilt in Deine Motive. Sortiere Favoriten übersichtlich und frage diese direkt mit zugehörigen Leihzeiten an. Schluss also mit der Angebotszettelwirtschaft, die zum fünften Mal mit Rotstift korrigiert wurde.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8 track-paddingtop">
                <div class="arrow-pointer">
                    <p>Deine Merkliste sortiert nach Fundus...</p>
                </div>
                <div class="img-box">
                    <div class="arrow_1"><img src="{{ asset('assets/images/arrow1.png') }}"></div>
                    <img src="{{ asset('assets/images/block3.jpg') }}" class="w-100">
                </div>
            </div>
        </div>
        <div class="col-md-10 ml-auto p-0 b2_block">
            <div class="arrow-pointer order">
                <p>oder sortiert nach Motiv und Zeitraum.</p>
            </div>
            <div class="img-box mt-4">
                <div class="arrow_2"><img src="{{ asset('assets/images/arrow2.png') }}"></div>
                <img src="{{ asset('assets/images/block4.jpg') }}">
            </div>
        </div>
    </div>
</section>
<section class="services-section">
    <div class="container container-width-extend">
        <div class="row">
            <div class="col-md-3">
                <h5 class="twoline-text">REQUISITEN / EINRICHTUNG</h5>
                <p>Von der Couch bis zum Spezial-Laborgerät: Es gibt nichts, was es hier nicht gibt. Beim
 Film gibt es schließlich auch nichts, was nicht irgendwann mal gebraucht wird. </p>
            </div>
            <div class="col-md-3">
                <h5>FAHRZEUGE</h5>
                <p>Regie: „Ach ja und für die Rolle Klara hätte ich gerne einen 1962 Ferrari 250 GTO
blau-grau, ins Grünliche gehend. Aber bitte mit der verchromten Stoßstange und
ohne diese hässlichen Felgen”.  </p>
            </div>
            <div class="col-md-3">
                <h5>DIENSTLEISTUNGEN</h5>
                <p>Du benötigst eine Person mit medizinischen Kenntnissen, beim nächsten „Medicus“?  Die Speisekarte im Set von „The Grand Budapest Hotel“ sollte auch noch jemand ins Englische  übersetzen?</p>
            </div>
            <div class="col-md-3">
                <h5>GRAFIK</h5>
                <p>Grafiker:innen bieten hier alles an, was Photoshop und Illustrator hergeben. Mit
 Nutzungslizenz für Dein Projekt. </p>
            </div>
        </div>
    </div>
</section>
<section class="cat-login cat-home common-spacer-topbot">
    <div class="false-container">
        @foreach($topLevelCategories as $category)
        <div class="cat-products">
            <div class="pr-listing owl-carousel">
                @foreach($productList[$category->id] as $product)
                <a href="#" class="open-product-detail-popup" 
                   data-product="{{ json_encode($product->only(['code','name','slug','location', 'postal_code', 'year', 'quantity', 'replacement_value', 'custom_price_available'])) }}"
                   data-color="{{ $product->color['option_value'] ?? '' }}" 
                   data-color-name="{{ $product->color['option_display'] ?? '' }}" 
                   data-style="{{ $product->style['option_display'] ?? '' }}" 
                   data-epoche="{{ $product->epocheText['option_display'] ?? '' }}"
                   data-graphic_form="{{ $product->graphicForm['option_display'] ?? '' }}"
                   data-manufacturer_id="{{ $product->manufacture['option_display'] ?? '' }}"
                   data-manufacture_country="{{ $product->manufactureCountry['option_display'] ?? '' }}"
                   data-file_format="{{ $product->fileFormat['option_display'] ?? '' }}"
                   data-copy_right="{{ $product->copyright['option_display'] ?? '' }}"
                   data-price="{{ $product->prices ?? [] }}"
                   data-dimensions="{{ $product->dimensions }}"
                   data-description="{{ $product->description }}" 
                   data-category="{{ $product->category_name }}"
                   data-parent-category-slug="{{ $product->top_category_slug }}"
                   data-image="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" 
                   data-media="{{ isset($product->productMedia) ? $product->productMedia->pluck('file_name') : [] }}"
                   @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                   data-fundus-name="{{ $product->fundus_name }}" 
                   data-fundus-email="{{ $product->fundus_email }}" 
                   data-fundus-phone="{{ $product->fundus_phone }}" 
                   data-fundus-location="{{ $product->fundus_location }}"
                   data-fundus-store="{{route('store.index',[$product->fundus_name]) }}"
                   data-fundus-logo="{{ !empty($product->logo_image_path) ? config('app.website_media_base_url') . $product->logo_image_path : asset('assets/images/lm-logo.png') }}"
                   @endif
                   >
                    <img src="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" height="250">
                </a>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</section>
<section class="lend-section" id="verleihen">
    <h2 class="heading-global">VERLEIHEN</h2>
    <div class="container container-width-extend">
       <div class="row no-gutters">
         <div class="col-sm-12 justify-content-end d-flex">
           <div class="lend-box1">
            <div class="lend-box1-arrow"><img src="{{ asset('assets/images/lend-box1-arrow.png') }}" class="img-fluid"></div>
            <div>
             <h3>Erstelle Dir eine eigene Onlinepräsenz auf SetBakers</h3>
             <p class="mb-0">Egal ob Du Sammler:in bist, Grafiker:in, etablierter Fundus oder Dienstleister:in.</p>
           </div>
           </div>
         </div>
       </div>
        <div class="row no-gutters mobile-reverse-row">
            <div class="col-md-9">
                <div class="img-box">
                    <img src="{{ asset('assets/images/block5.jpg') }}">
                </div>
            </div>
            <div class="col-md-3">
              <div class="home-steps-number">
                <ul class="list-unstyled">
                  <li>
                    <h4>1</h4>
                    <p>Fundus-Account anlegen</p>
                  </li>
                  <li>
                    <h4>2</h4>
                    <p>Produkte hochladen</p>
                  </li>
                  <li>
                    <h4>3</h4>
                    <p>Anfragen erhalten</p>
                  </li>
                  <li>
                    <h4>4</h4>
                    <p>Preisliste für Dein Angebot direkt auf SetBakers erstellen</p>
                  </li>
                  
                </ul>
              </div>
                {{-- <div class="lend-right">
                    <div class="box">
                        <p>Jeder, egal ob Sammler, Grafiker, etablierter Fundus oder
                            Film-Dienstleister, kann seine eigene Onlinepräsenz auf SetBakers  aufbauen und wird von Filmschaffenden gefunden.</p>
                    </div>
                    <div class="arrow-pointer mt-4">
                        <img src="{{ asset('assets/images/icons/arrow-bottom-left.png') }}">
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container container-width-extend">
        <div class="row">
            <div class="col-md-4">
                <h4>KOSTENGÜNSTIG</h4>
                <div class="box">
                    <p>SetBakers erspart Dir eine teure und aufwändige Website. Lade einfach Deine Artikel auf SetBakers hoch und mache sie  Filmschaffenden zugänglich. </p>
                </div>
            </div>
            <div class="col-md-4">
                <h4>FLEXIBEL</h4>
                <div class="box">
                    <p>Abonnements können monatlich gekündigt und Fundi pausiert werden. Dein Online-Fundus, Deine Produkte und Deine Art zu verleihen. Es bleibt alles beim Alten, nur wird es viel einfacher. </p>
                </div>
            </div>
            <div class="col-md-4">
                <h4>NACHHALTIG</h4>
                <div class="box">
                    <p>Die Filmindustrie gilt als einer der größten Umweltsünder. Daher ist ”Green-Production” zunehmend wichtig. Mit SetBakers lohnt sich das Bewahren von Dingen wieder. Teile, gib weiter und leihe, anstatt alles neu zu kaufen.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="price-section" id="preise">
    <h2 class="heading-global">PREISE</h2>
    <div class="container container-width-extend">
        <div class="price-head">
            <div class="row justify-content-between mobile-reverse-row">
                <div class="col-md-6">
                    <h6>Wie möchtest Du <b>SetBakers</b> nutzen?</h6>
                </div>
                <div class="col-md-5">
                    <h5>„ICH WERDE IHM EIN ANGEBOT <br>MACHEN, DAS ER NICHT ABLEHNEN KANN“
                        <span> (Marlon Brando, Der Pate)</span></h5>
                </div>
            </div>
        </div>
        <div class="price-wrapper">
            <div class="row">
                <div class="col-md-3 pr-col1 {{ !Auth::check() ? 'cursor-pointer open-registration-project' : '' }}">
                  <div class="price-box">
                    <h6>PROJEKTKONTO <span>(leihen und optional verleihen)</span></h6>
                    <div class="price">
                        <h3>{{ $projectSubsPlans['recurring']['1month']->display_amount ?? '' }}€ / Monat</h3>
                        <p>inkl. {{ $subsTaxPercentage }}% MwSt</p>
                    </div>
                    <ul>
                        <li style="color: #8fb3bb;font-weight: 600;">Jetzt einen Monat kostenlos testen!</li>
                        <li>Nutze die umfassende Suchfunktion</li>
                        <li>Sehe Kontaktdaten zu allen Produkten ein</li>
                        <li>Erstelle Deine eigene Favoritenliste und sende Anfragen direkt an den jeweiligen Fundus</li>
                        <li>Erstelle optional Deinen eigenen Fundus, mit bis zu {{ config('app.max_articles_fundus') }} Artikeln.</li>
                        <li>Monatlich kündbar</li>
                    </ul>
                  </div>
                </div>
                <div class="col-md-3 pr-col2 pr-col-common {{ !Auth::check() ? 'cursor-pointer open-registration-fundus' : '' }}">
                  <div class="price-box">
                    <h6>FUNDUSKONTO <i>BASIC</i>  <span>(verleihen)</span></h6>
                    <div class="price spacer-1">
                        <h3>Kostenlos</h3>
                    </div>
                    <ul>
                      <li>Erstelle Deinen eigenen Onlinefundus</li>
                      <li>Stelle bis zu {{ config('app.max_articles_fundus') }} Artikel kostenlos ein und verleihe zu Deinen Konditionen</li>
                      <li>Deaktiviere Deinen Fundus temporär, wenn Du mal keine Zeit hast</li>
                    </ul>
                  </div>
                </div>
                <div class="col-md-3 pr-col-common">
                  <div class="price-box">
                    <h6>FUNDUSKONTO <i>PRO</i> <span>(verleihen)</span></h6>
                    <div class="price">
                        <h3>{{ $fundusSubsPlans['recurring']['1month']->display_amount ?? '' }}€ / Monat</h3>
                        <p>inkl. {{ $subsTaxPercentage }}% MwSt</p>
                    </div>
                    <ul>
                        <li>Erweitere deinen Fundus auf {{ config('app.max_articles_fundus_pro') }} Artikel</li>
                    </ul>
                  </div>
                </div>
                <div class="col-md-3 pr-col-common">
                  <div class="price-box">
                    <h6>FUNDUSKONTO <i>INFINITE</i> <span>(verleihen)</span></h6>
                    <div class="price spacer-1">
                        <h3>Angebot anfordern</h3>
                    </div>
                    <ul>
                        <li>Wir machen Dir ein individuelles Angebot für Deine gewünschte Artikelanzahl</li>
                    </ul>
                  </div>
                </div>
            </div>
        </div>
    </div>
</section>
@if(isset($displayLoginPopup) && $displayLoginPopup)
<script type="text/javascript">
    let displayLoginPopup = true;
</script>
@endif
@endsection


@section('more-article-login-restriction')
@guest
@else
@if(Auth::user()->account_type == 'complete' && Auth::user()->projectDetail['is_subscription_paused'] == 0 && Auth::user()->projectDetail['subscription_end_date'] < date('Y-m-d H:i:s'))
@if($isLastBankPaymentPending == false)
<script type="text/javascript">
    var paymentPopupFlag = true;
</script>
@endif
@endif
@endguest
<script type="text/javascript">
    let categoryWiseFields = {!! json_encode(config('product.fields')) !!}
    ;
</script>
@endsection


@section('modal-windows')

@if(Auth::check() && empty(Auth::user()->projectDetail))
@include('partials.upgrade-project')
@endif

@endsection