@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')
<section class="product-filter minheight_850 product-category-search cat_menu_wrap">
    <div class="container">
        @if(!empty($fundusDetail))
        <div class="funds-details">
            <div class="row">
                <div class="col-lg-3">
                    <div class="contact-info">
                        <h6>{{ $fundusDetail->location ?? '' }}</h6>
                        @if(Auth::check() && Auth::user()->account_type == 'complete' && !empty(Auth::user()->projectDetail->subscription_end_date) && Auth::user()->projectDetail->subscription_end_date > date('Y-m-d H:i:s'))
                        <div class="email dls"><a href="#">{{ $fundusDetail->fundus_email ?? '' }}</a><button type="button" class="btn" onclick="copyToClipboard('.contact-info .email a')"><i class="zmdi zmdi-copy"></i></button></div>
                        @if(!empty($fundusDetail->fundus_phone))
                        <div class="phone dls"><a href="#">{{ $fundusDetail->fundus_phone ?? '' }}</a><button type="button" class="btn" onclick="copyToClipboard('.contact-info .phone a')"><i class="zmdi zmdi-copy"></i></button></div>
                        @endif
                        <div class="dls">{{ isset($productCounts) ? $productCounts . ' Artikel' :  '' }} </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="funds-info">
                        <div class="row no-gutters">
                            <div class="col-sm-10">
                                <h5>FUNDUS von</h5>
                                <h2>{{ $fundusDetail->fundus_name ?? '' }}</h2>
                                <p>{{ $fundusDetail->description ?? '' }}</p>
                            </div>
                            <div class="col-sm-2">
                                <img src="{{ !empty($fundusDetail->logo_image_path) ? config('app.website_media_base_url') . $fundusDetail->logo_image_path : asset('assets/images/lm-logo.png') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.search-bar', ['categoryLevel' => 1, 'fundusIndex' => 1])
        @else
        {{-- @include('partials.category-menu-bar') --}}
        @include('partials.search-bar', ['categoryLevel' => 1])
        @endif   

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
                       data-product="{{ json_encode($product->only(['code','name','slug','location', 'postal_code', 'year', 'quantity', 'custom_price_available'])) }}"
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

    </div>
</section>
@endsection

@section('more-article-login-restriction')
@auth  

@if(Auth::user()->account_type == 'complete' && Auth::user()->projectDetail['is_subscription_paused'] == 0 && Auth::user()->projectDetail['subscription_end_date'] < date('Y-m-d H:i:s'))
@if($isLastBankPaymentPending == false)
<script type="text/javascript">
    var paymentPopupFlag = true;
</script>
@endif
@endif
@endauth
<script type="text/javascript">
    let categoryWiseFields = {!! json_encode(config('product.fields')) !!};
</script>
@endsection

@section('modal-windows')

@if(Auth::check() && empty(Auth::user()->projectDetail))
@include('partials.upgrade-project')
@endif

@endsection
