@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')  
<section class="product-filter hide-shadow minheight_850 funduslist">
    <div class="container">
        <div class="funds-details">
            <div class="row">
                <div class="col-lg-3">
                    <div class="contact-info">
                        <h6>{{ $fundusDetail->location ?? '' }}</h6>
                        <div class="email dls"><a href="#">{{ $fundusDetail->fundus_email ?? '' }}</a></div>
                        <div class="phone dls"><a href="#">{{ $fundusDetail->fundus_phone ?? '' }}</a></div>
                        <div class="dls artikel">{{ isset($productCounts) ? $productCounts . ' Artikel' :  '' }} </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="funds-info">
                        <div class="row no-gutters">
                            <div class="col-sm-10">
                                <h5>FUNDUS von</h5>
                                <h2>{{ $fundusDetail->fundus_name ?? '' }}</h2>
                                <div class="desciption">
                                    <div class="short-description">
                                        <div class="content">
                                            <p>{{ Str::limit($fundusDetail->description ?? '', 400) }}</p>
                                        </div>
                                        <button type="button"><i class="zmdi zmdi-chevron-down"></i></button>
                                    </div>
                                    <div class="full-description">
                                        <div class="content">
                                            <p>{{ $fundusDetail->description ?? '' }}</p>
                                            <button type="button"><i class="zmdi zmdi-chevron-up"></i></button>
                                        </div>
                                    </div>
                                </div>
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
        <div class="products-container">
            <div class="item-box" width="300">
                <div class="img-upload">
                    <a href="{{ route('fundus.create') }}"><label>
                            <span><i class="zmdi zmdi-plus"></i></span>
                            <img id="preview" src="" width="405" />
                        </label></a>
                </div>
            </div>
            @foreach($products as $product)

            <div class="item-box">
                <a href="{{ route('fundus.show', [$product->slug]) }}">
                    <div class="product-img">
                        <img src="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" height="200">
                    </div>
                </a>
                <div class="product-detail">
                    <div class="title-year">
                        <span class="year">{{ !empty($product->year) ? $product->year : ($product->epocheText['option_display'] ?? '') }}</span>
                        <h6 class="title">{{ $product->name ?? ''}}</h6>
                    </div>
                    <!--
                    <div class="product-count">
                        <div class="counter fundus-index-counter" data-slug="{{ $product->slug }}">
                            <button type="button" class="btn decrement">-</button>
                            <input type="text" class="form-control" value="{{ $product->quantity ?? ''}}">
                            <button type="button" class="btn increment">+</button>
                        </div>
                    </div>
                    -->
                </div>
            </div>

            @endforeach

        </div>

        {{ $products->onEachSide(1)->appends(collect(request()->all())->map(function($item, $key) {
            return is_null($item) && $key == 'searched_category_id' ? 0 : $item;
        })->toArray())->links() }}

    </div>
</section>
@endsection