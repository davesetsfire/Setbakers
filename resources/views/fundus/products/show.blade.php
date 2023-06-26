@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')
<section class="product-detail-section">
    <div class="container">
        <form action="" method="POST" id="product_store_request_form">
            @csrf
            <div class="top-btns category-counter @if(Auth::check() && !isset($userImpressions['hintbox-fundus-show'])) position-relative @endif ">
                @if(Auth::check() && !isset($userImpressions['hintbox-fundus-show']))
                    <div class="hint-box-global hint-check-top-right border-top-right-radius user-impression-block">
                        <div class="hint-check">
                            <div class="option">
                                <label>
                                    Check
                                    <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-fundus-show" data-impression-value="yes">
                                    <span class="checkmarks"></span>
                                </label>
                            </div>
                        </div>
                        <p>{{ __('hintbox.FUNDUS_SHOW_HINT') }}</p>
                    </div>
                @endif
                <div class="product-count">
                    <div class="counter">
                        <button type="button" class="btn decrement">-</button>
                        <input type="number" class="form-control" name="requested_count" value="1">
                        <button type="button" class="btn increment" data-available="{{ $product->quantity }}">+</button>
                    </div>
                </div>
                <div class="custom-dropdown">
                    <div class="label">Angebot wählen</div>
                    <input type="hidden" value="" name="store_order_item">
                    <input type="hidden" value="{{ $product->slug }}" name="order_product_slug">
                    <div class="dropdown-wrap">
                        <div class="category-tab">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="category-tab1-btn">
                                        <ul>
                                            @foreach($storeOrders as $storeRequest)
                                            <li><a href="#request{{ $storeRequest->order_number }}">{{ $storeRequest->order_number }}</a></li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="category-tab2-btn">
                                        @foreach($storeOrders as $storeRequest)
                                        <div class="content" id="request{{ $storeRequest->order_number }}">
                                            <ul>
                                                @foreach($storeRequest->orderItems as $storeRequestSet)
                                                <li><a href="#" class="set-option" data-option="{{ $storeRequestSet['favourite_name'] ?? '' }}" data-value="{{ $storeRequestSet['id'] ?? '' }}">{{ $storeRequestSet['favourite_name'] ?? '' }}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn global-btn add-product-store-request"><div class="spinner-border button_spinner"></div>HINZUFÜGEN</button>

                <a href="{{ route('fundus.edit',[$product->slug]) }}" class="global-btn"><i class="zmdi zmdi-edit"></i></a>
                <a href="#" class="global-btn style2" data-toggle="modal" data-target="#delete-item"><i class="zmdi zmdi-delete"></i></a>
            </div>
            <div class="message-box">
                <label id="requested_count-error" class="error" for="requested_count" style="display:none;"></label>
                <label id="requested_count-success" class="success" style="display:none;"></label>
            </div>
        </form>
        <div class="row fundus-detail-new">
            <div class="col-md-6">
                <div class="product-img">
                    <div class="main-img">
                        <a href="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" data-fancybox="product-image">
                            <img src="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" height="300">
                        </a>
                    </div>
                    <div class="product-thumns">
                        <div class="row">
                            @foreach($product->productMedia as $productMedia)
                            @if($productMedia['is_primary'] == 0)
                            <div class="col-sm-6">
                                <a href="{{ !empty($productMedia['file_name']) ? config('app.website_media_base_url') . $productMedia['file_name'] : ''}}" data-fancybox="product-image">
                                    <img src="{{ !empty($productMedia['file_name']) ? config('app.website_media_base_url') . $productMedia['file_name'] : ''}}">
                                </a>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="keywords">
                        <h4>Keywords</h4>
                        <p>{{ $product->keywords ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="product-detail">
                    <h4>{{ $product->name ?? '' }}</h4>
                    <div class="description">
                        <p>{{$product->description ?? '' }}</p>
                    </div>
                    <div class="dls">
                        <div class="dls-lft">
                            <h6>Kategorie</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->category_name }}</p>
                        </div>
                    </div>
                    @if(!empty($product->graphicForm['option_display']))
                    <div class="dls" style="{{ !in_array('graphic_form', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Verfügbarkeit</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->graphicForm['option_display'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->manufacture['option_display']))
                    <div class="dls" style="{{ !in_array('manufacturer_id', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Marke</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->manufacture['option_display'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->manufactureCountry['option_display']))
                    <div class="dls" style="{{ !in_array('manufacture_country', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Herstellerland</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->manufactureCountry['option_display'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->prices) && count($product->prices) > 0 || $product->custom_price_available == 1)
                    <div class="dls" style="{{ !in_array('price', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Preis</h6>
                        </div>
                        <div class="dls-rgt preis">
                            @foreach($product->prices as $key => $priceItem)
                            <p>@money_format($priceItem['price']) / {{ $priceItem['duration_text'] }}</p>
                            @endforeach
                            @if($product->custom_price_available)
                            <p class="text-grey">{{ $parentCategory == "grafik" ? "Bei Stückpreis, Mengenrabatt möglich" : "Pauschale möglich" }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->replacement_value) && $product->replacement_value > 0)
                    <div class="dls" style="{{ !in_array('replacement_value', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>WBW</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>@money_format($product->replacement_value)</p>
                        </div>
                    </div>
                    @endif

                    @if($product->length > 0 || $product->width > 0 || $product->height > 0)
                    <div class="dls" style="{{ !in_array('dimensions', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Abmessungen (LxBxH)</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->dimensions ?? '' }}</p>
                        </div>
                    </div>
                    @endif

                    @if(!empty($product->fileFormat['option_display']))
                    <div class="dls" style="{{ !in_array('file_format', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Format</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->fileFormat['option_display'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->copyright['option_display']))
                    <div class="dls" style="{{ !in_array('copy_right', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Rechte</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->copyright['option_display'] ?? '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->epocheText['option_display']))
                    <div class="dls" style="{{ !in_array('epoche', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                         <div class="dls-lft">
                            <h6>Epoche</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->epocheText['option_display'] ?? ''}}  {{ $product->year != 0 ? '('.$product->year.')' : '' }}</p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->color['option_value']) && $product->color['option_value'] > 0)
                    <div class="dls" style="{{ !in_array('color', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Farbe</h6>
                        </div>
                        <div class="dls-rgt">
                            <p style="display: flex;"><span class="color check-mark" style="background: #{{ $product->color['option_value'] ?? '' }};border: 1px solid #000000;"></span><span style="margin-left: 10px">{{ $product->color['option_display'] ?? '' }}</span></p>
                        </div>
                    </div>
                    @endif
                    @if(!empty($product->style['option_display']))
                    <div class="dls" style="{{ !in_array('style', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Stil</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{$product->style['option_display'] ?? ''}}</p>
                        </div>
                    </div>
                    @endif
                    <div class="dls" style="{{ !in_array('location_at', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                           <h6>Ort</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->location ?? '' }}, {{ $product->postal_code ?? '' }}</p>
                        </div>
                    </div>
                    <div class="dls" style="{{ !in_array('quantity', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                        <div class="dls-lft">
                            <h6>Menge</h6>
                        </div>
                        <div class="dls-rgt">
                            <p>{{ $product->quantity ?? ''}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('modal-windows')
@include('fundus.products.partials.delete')
@endsection