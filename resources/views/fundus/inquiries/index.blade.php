@extends('layouts.app')


@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')
<section class="favourite-funds prop-store minheight_850">
    <div class="container">
        <div class="funds-list">
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
            @if(count($storeOrders) == 0)
            <div class="nodata-available"><h6>Du hast noch keine Anfragen erhalten</h6></div>
            @endif
            @foreach($storeOrders as $storeOrder)
            <div class="funds-wrapper">
                <form action="" method="post" id="fundus_inquiry_form" class="fundus_inquiry_form">
                    <input type="hidden" name="order_id" value="{{ $storeOrder->id }}">
                    @csrf
                    <div class="funds-name meine-anfragen-header">
                        <div class="name">
                            <h6><div class="date-of-request">{{ $storeOrder->created_at->format('d.m.Y') }} </div><div class="projectname">Projekt “{{ $storeOrder->project['project_name'] ?? '' }}”</div></h6>
                            <p class="user-info">
                                @if(!empty($storeOrder->project['user']))
                                <span class="name-user">{{ $storeOrder->project['user']['name'] ?? '' }}</span>
                                <span class="email">{{ $storeOrder->project['user']['email'] ?? '' }}</span>
                                @else
                                <span class="name-user">Dieses Konto wurde gelöscht.</span>
                                @endif
                            </p>
                        </div>
                        <div class="btns">
                            <!--<button type="submit" class="btn global-btn">Anfrageliste downloaden</button>-->
                            <div class="requestnumber"> {{ $storeOrder->order_number ?? '' }}</div>
                            <div class="action-parent">
                                @if(Auth::check() && !isset($userImpressions['hintbox-export-list']))
                                 <div class="hint-box-global action-hintbox hint-check-top-right border-bottom-right-radius user-impression-block">
                                    <p>{{ __('hintbox.EXPORT_LIST_HINT') }}</p>
                                    <div class="hint-check">
                                        <div class="option">
                                            <label>
                                                Now we're talking
                                                <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-export-list" data-impression-value="yes">
                                                <span class="checkmarks"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <div class="action">
                                    <select class="form-control" name="inquiry_action">
                                        <option value="" selected disabled hidden>Aktion</option>
                                        <option value="export" data-action="{{ route('fundus.inquiries.download') }}">Angebot exportieren</option>
                                        <option value="export_gallery" data-action="{{ route('fundus.inquiries.download.gallery') }}">Artikelgalerie</option>
                                        <option value="delete" data-action="{{ route('fundus.inquiries.delete') }}">löschen</option>
                                    </select>
                                    <button type="submit" class="btn arwenden global-btn">Anwenden</button>
                                </div>
                                <label class="check_container">
                                    <input type="checkbox" class="project-request-checkbox pr-checkbox-{{ $storeOrder->id }}" name="project_order_id" value="{{ $storeOrder->id }}">
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="funds-detail-ac fundus-enquiry">
                        <div id="funds-accordion1">
                            @foreach($storeOrder->orderItems as $orderItems)
                            <div class="card fundus-enquiry-item meine-anfragen-card">
                                <div class="card-header">
                                    <a class="card-link" data-toggle="collapse" href="#funds-product-one{{ $orderItems->id }}">
                                        <div class="fundus-dates-container">
                                            <div class="fundus-dates-wrap">
                                                @php $totalDays = 0; @endphp
                                                @foreach($orderItems['dateRanges'] as $dateRange)
                                                <div class="fundus-dates">
                                                    @php $totalDays += Carbon\Carbon::parse($dateRange['end_date'])->diffInDays($dateRange['start_date']) + 1; @endphp
                                                    @if($dateRange['start_date']->format('d.m.Y') == $dateRange['end_date']->format('d.m.Y'))
                                                    {{ $dateRange['start_date']->format('d.m.Y') ?? '' }} </br>
                                                    @else
                                                    {{ $dateRange['start_date']->format('d.m.Y') ?? '' }} - {{ $dateRange['end_date']->format('d.m.Y') ?? '' }} </br>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>

                                            <span class="motiv-after-dates"> Motiv: {{ $orderItems['favourite_name'] ?? '' }} </span> <span>({{ count($orderItems['orderProducts']) }})</span>
                                        </div>
                                    </a>

                                    <div class="checkbox info-message">
                                        <span class="tage tage-inquiry">{{ $totalDays }} Tage</span>
                                        <label class="check_container">
                                            <input type="checkbox" class="project-request-item-checkbox-{{ $storeOrder->id }}" data-checkbox="{{ $storeOrder->id }}" name="order_item_id[]" value="{{ $orderItems['id'] }}" data-id="{{ $orderItems['id'] }}">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                                <div id="funds-product-one{{ $orderItems->id }}" class="collapse" data-parent="#funds-accordion1">
                                    <div class="card-body">
                                        @foreach($orderItems['orderProducts'] as $storeProduct)
                                        @php $productKeyName = !empty($storeProduct['product']) ? 'product' : 'addonProduct';  @endphp
                                        <div class="product-wrap">
                                            <div class="row">
                                                <div class="col-sm-2">
                                                    <div class="product-img">
                                                        <img src="{{ !empty($storeProduct[$productKeyName]['image']) ? config('app.website_media_base_url') . $storeProduct[$productKeyName]['image'] : '' }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-10">
                                                    <div class="product-dls-wrap">
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="product-name">
                                                                    <h5>{{ $storeProduct[$productKeyName]['name'] ?? ''}}</h5>
                                                                   {{--  <p>{{ $storeProduct[$productKeyName]['description'] ?? '' }}</p> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row fundus-inquiries-row">
                                                            <div class="col-sm-12 fundus-enquiry-sm-8">
                                                                <div class="select-count">
                                                                    <div class="product-count">
                                                                        <div class="counter inquiry-product-counter" data-id="{{ $storeProduct->id }}">
                                                                            <button type="button" class="btn decrement">-</button>
                                                                            <input type="number" name="requested_item_count[]" class="form-control" value="{{ $storeProduct['quantity'] ?? 0 }}">
                                                                            <button type="button" class="btn increment" 
                                                                                    @if(isset($storeProduct[$productKeyName]['quantity']))
                                                                                    data-available="{{ $storeProduct[$productKeyName]['quantity'] ?? 0 }}"
                                                                                    @endif
                                                                                    >+</button>
                                                                        </div>
                                                                        @if(isset($storeProduct[$productKeyName]['quantity']))
                                                                        <div class="count">{{ $storeProduct[$productKeyName]['quantity'] ?? 0 }}
                                                                            <span class="circle" style=""></span></div>
                                                                        @endif
                                                                        <a href="{{ route('fundus.inquiries.deleteOrderProduct', [$storeProduct->id])}}" class="btn delete_product_btn delete-store-request-article-confirm"><i class="zmdi zmdi-delete"></i></a>
                                                                        <!--<button type="button" class="btn delete_product_btn"><i class="zmdi zmdi-delete"></i></button>-->
                                                                    </div>
                                                                </div>
                                                                <div class="detail-row">

                                                                    <div class="detail-col">
                                                                        <label>Wiederbeschaffungswert</label>
                                                                        <div class="value">
                                                                            @if(!empty($storeProduct[$productKeyName]['formatted_replacement_value']))
                                                                            {{ $storeProduct[$productKeyName]['formatted_replacement_value'] }} €
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    @if(!empty($storeProduct[$productKeyName]['prices']) && count($storeProduct[$productKeyName]['prices']) > 0)
                                                                    @foreach($storeProduct[$productKeyName]['prices'] as $price)
                                                                    <div class="detail-col">
                                                                        <label>Preis pro {{ $price['duration_text'] }}</label>
                                                                        <div class="value-parent">
                                                                            <div class="value">
                                                                                <span class="mr-1-1">
                                                                                    @money_format($price['price'])
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                    @endif

                                                                    <div class="detail-col">
                                                                        <label>Stückpreis</label>
                                                                        <div class="value">
                                                                            <div class="price-wraps">
                                                                                <input type="text" class="form-control input-price only-numeric-data" name="unit_price[]" value="{{ $storeProduct['unit_price_value'] ?? 0 }}" placeholder="">
                                                                                <span class="euro_symbool">€</span>
                                                                                <input type="hidden" class="form-control" name="product_item_id[]" value="{{ $storeProduct->id }}">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="text-right enquire-apply-btn-wrap">
                                            <input type="hidden" name="error_msg">
                                            <button type="button" class="btn global-btn inquiry-apply-button" style="display:none">Änderungen übernehmen</button>
                                        </div>

                                        <div class="add-fundus-wrap"></div>
                                        <div class="add-fundus-btn-wrap @if(Auth::check() && !isset($userImpressions['hintbox-add-item-tothe-offer'])) position-relative @endif">
                                            @if(Auth::check() && !isset($userImpressions['hintbox-add-item-tothe-offer']))
                                                 <div class="hint-box-global text-left border-bottom-left-radius user-impression-block">
                                                    <p>{{ __('hintbox.ADD_ITEM_TOTHE_OFFER_HINT') }}</p>
                                                    <div class="hint-check">
                                                        <div class="option">
                                                            <label>
                                                                Ok
                                                                <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-add-item-tothe-offer" data-impression-value="yes">
                                                                <span class="checkmarks"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            <button type="button" class="btn btn-block add-fundus-enquiry"><i class="zmdi zmdi-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</section>

<script type="text/javascript">
    var add_fundus_form = `<div class="wrapper-form"> 
    <form action="{{route('fundus.inquiries.product.create')}}" method="POST" enctype="multipart/form-data" id="fundus_inquiry_add_product_form" class="fundus_inquiry_add_product_form">
        <input type="hidden" name="product_order_item_id" value="PRODUCT_ORDER_ITEM_ID">
        @csrf
        <input type="hidden" name="error_msg">
        <div class="add-fundus-form">
            <div class="product-wrap add-produc-wrap">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="product-img">
                            <div class="img-upload">
                                <label class="mb-0">
                                    <span><i class="zmdi zmdi-plus"></i></span>
                                    <input type='file' onchange="readURL(this);" name="product_image" accept="image/*"/>
                                    <img id="preview" src=""/>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-10">
                        <div class="product-dls-wrap">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control artikel" name="product_name" placeholder="Artikelbezeichnung">
                                    </div>
                                    <div class="form-group">
                                        <textarea rows="2" class="form-control" name="product_description" placeholder="Beschreibung"></textarea>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="select-count">
                                        <div class="product-count">
                                            <div class="counter inquiry-product-counter" data-id="6">
                                                <button type="button" class="btn decrement">-</button>
                                                <input type="number" name="requested_count" class="form-control" value="1">
                                                <button type="button" class="btn increment">+</button>
                                            </div>   
                                        </div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-col">
                                            <label>Wiederbeschaffungswert</label>
                                            <div class="value">
                                                <div class="replacement_value price">
                                                    <input type="text" class="form-control only-numeric-data" name="replacement_value" placeholder="">
                                                    <span class="euro_symbool">€</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="detail-col">
                                            <label class="angebotspreis-mt">Stückpreis </label>
                                            <div class="value">
                                                <div class="product_unit_price price">
                                                    <input type="text" class="form-control input-price only-numeric-data" name="product_unit_price" placeholder="">
                                                    <span class="euro_symbool">€</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="save-fundus-wrap @if(Auth::check() && !isset($userImpressions["hintbox-article-add-fundus"])) position-relative @endif">
        @if(Auth::check() && !isset($userImpressions["hintbox-article-add-fundus"]))
         <div class="hint-box-global text-left border-bottom-left-radius user-impression-block">
            <p>{{ __("hintbox.ARTICLE_ADD_FUNDUS_HINT") }}</p>
            <div class="hint-check">
                <div class="option">
                    <label>
                        Verstanden
                        <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-article-add-fundus" data-impression-value="yes">
                        <span class="checkmarks"></span>
                    </label>
                </div>
            </div>
        </div>
        @endif
            <label class="check_container style2">Füge den Artikel auch zu meinem Fundus hinzu
                <input type="checkbox" name="add_product" value="yes">
                <span class="checkmark"></span>
            </label>
            <div class="btns">
                <button type="button" class="btn style2 global-btn cancel-submit">Abbrechen</button>
                <button type="button" class="btn global-btn addon-product-create"><div class="spinner-border button_spinner"></div> Übernehmen</button>
            </div>
        </div>
    </form>
</div>`;
</script>
@endsection

@section('modal-windows')
@include('fundus.inquiries.partials.delete')
@endsection