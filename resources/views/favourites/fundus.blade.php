@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.favourite')
@endsection

@section('content')
<section class="edit-products-wrap favourite-funds minheight_850 favourite_main_section" id="favourite_funds">
    <div class="container">
        @if(session('error_message') || $errors->has('favourite_id'))
        <div class="error-msg">
            <i class="zmdi zmdi-close-circle"></i>
            {{ session('error_message') }} {{ $errors->first('favourite_id')}}
        </div>
        @endif
        @if(session('success_message'))
        <div class="success-msg">
            <i class="zmdi zmdi-check"></i>
            {{ session('success_message') }}
        </div>
        @endif
        <div class="funds-list">
            @if(count($favouritesByFundus) == 0)
            @if($bookmarkExists == 1)
            <div class="nodata-available"><h6>Du hast noch keine Favoriten zugeordnet</h6></div>
            @else
            <div class="nodata-available"><h6>Du hast noch keine Favoriten ausgewählt</h6></div>
            @endif
            @endif
            @foreach($favouritesByFundus as $storeId => $stores)
            <div class="funds-wrapper shop-space">
                <form action="{{ route('favourites.store.order') }}" method="post" class="fundus_favourite_form">
                    <input type="hidden" name="store_id" value="{{ $storeId }}">
                    @csrf
                    <div class="funds-name @if(Auth::check() && !isset($userImpressions['hintbox-collection-return'])) position-relative @endif">
                      @if(Auth::check() && !isset($userImpressions['hintbox-collection-return']))
                         <div class="hint-box-global border-bottom-left-radius user-impression-block">
                            <p>{{ __('hintbox.COLLECTION_RETURN_HINT') }}</p>
                            <div class="hint-check">
                                <div class="option">
                                    <label>
                                        Ok
                                        <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-collection-return" data-impression-value="yes">
                                        <span class="checkmarks"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="name">
                            <h6>
                                <a href="{{ !empty([$storeMaster[$storeId]->fundus_name]) ? route('store.index', [$storeMaster[$storeId]->fundus_name]) : '#' }}">{{ $storeMaster[$storeId]->fundus_name ?? '' }}</a>
                            </h6>
                            <p>{{ $storeMaster[$storeId]->location ?? '' }}</p>
                        </div>
                        <div class="btns">
                            <button type="button" class="btn global-btn style2 download_favourites_by_store" data-action="{{ route('favourites.download',[$storeId]) }}"><div class="spinner-border button_spinner"></div> Exportieren</button>
                            <button type="button" class="btn global-btn send_request_to_store"><div class="spinner-border button_spinner"></div> Artikel anfragen</button>
                            <label class="check_container">
                                <input type="checkbox" class="store-checkbox" name="check_store_id" value="{{ $storeId }}">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                    </div>

                    <div class="funds-detail-ac shooting-period-date">

                        <div id="funds-accordion1">
                            @foreach($stores as $favouriteId => $favouriteItems)
                            <div class="card">
                                <div class="card-header">
                                    <div class="favourite-funds-children-header">
                                        <div class="clickdate_booking"  data-toggle="collapse" href="#funds-product-one-{{$storeId}}-{{$favouriteId}}"></div>
                                        <div class="favourite-funds-children-header-left d-flex">
                                            <a class="card-link" data-toggle="collapse" href="#funds-product-one-{{$storeId}}-{{$favouriteId}}"></a>
                                            <div class="favourite-funds-children-dates">

                                                <div class="fundus-dates-container">
                                                    @if($favouriteMaster[$favouriteId]->user_id != 0)
                                                    <div class="fundus-dates-wrap">
                                                        @foreach($favouriteMaster[$favouriteId]['favouriteDateRanges'] as $key => $dateRange)
                                                        <div class="fundus-dates {{ ($favouriteStoreDateRanges[$storeId][$dateRange['id']]->is_active ?? 1) ? '' : 'deactivated-dates' }}">
                                                            <input type="hidden" name="favourite_date_id" value="{{ $dateRange['id'] ?? 0 }}">
                                                            <div class="spinner-border" style="display:none;"></div>
                                                            <input type="hidden" name="favourite_store_id" value="{{ $storeId }}">
                                                            <div class="start_date">
                                                                <input type="text" 
                                                                       class="form-control daterange-single3 pickup_date {{ empty($favouriteStoreDateRanges[$storeId][$dateRange['id']]->pickup_date) ? 'inputbgColor' : ''  }}"
                                                                       placeholder="Abholung" 
                                                                       value="{{ !empty($favouriteStoreDateRanges[$storeId][$dateRange['id']]->pickup_date) ? $favouriteStoreDateRanges[$storeId][$dateRange['id']]->pickup_date->format('d.m.Y') : '' }}"
                                                                       readonly="off"> {{-- inputbgColor --}}
                                                            </div>
                                                            <div class="period-date {{ ($favouriteStoreDateRanges[$storeId][$dateRange['id']]->favourite_date_change_flag ?? 0) ? 'period-color' : '' }}"
                                                                >
                                                                @if($dateRange['start_date']->format('d.m.Y') == $dateRange['end_date']->format('d.m.Y'))
                                                                {{ $dateRange['start_date']->format('d.m.Y') ?? '' }} 
                                                                @else
                                                                {{ $dateRange['start_date']->format('d.m.Y') ?? '' }} - {{ $dateRange['end_date']->format('d.m.Y') ?? '' }} 
                                                                @endif
                                                            </div>
                                                            <div class="end_date">
                                                                <input type="text" 
                                                                       class="form-control daterange-single3 return_date {{ empty($favouriteStoreDateRanges[$storeId][$dateRange['id']]->return_date) ? 'inputbgColor' : ''  }}"
                                                                       placeholder="Rückgabe" 
                                                                       value="{{ !empty($favouriteStoreDateRanges[$storeId][$dateRange['id']]->return_date) ? $favouriteStoreDateRanges[$storeId][$dateRange['id']]->return_date->format('d.m.Y') : '' }}"
                                                                       readonly="off">
                                                            </div>
                                                            <div class="toggle_button">
                                                                @if(count($favouriteMaster[$favouriteId]['favouriteDateRanges']) > 1)
                                                                <button type="button" 
                                                                        class="btn btn-toggle"
                                                                        data-toggle="button" aria-pressed="{{ ($favouriteStoreDateRanges[$storeId][$dateRange['id']]->is_active ?? 1) ? 'true' : 'false' }}" autocomplete="off">
                                                                    <div class="handle"></div>
                                                                </button>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <!-- |  -->
                                                    @endif
                                                    <div class="funds-name-none-edit">{{ $favouriteMaster[$favouriteId]->name }}
                                                        <span>({{ count($favouriteItems) }})</span>
                                                    </div> 
                                                </div>
                                                <div class="fundus-dates-desc d-none">
                                                    <div class="start-date-txt">Abholung</div>
                                                    <div class="period-date-txt">Drehzeitraum</div>
                                                    <div class="end-date-txt">Rückgabe</div>
                                                </div>
                                                <div class="fundus-dates-errormsg error"></div>
                                            </div>
                                        </div>
                                        @if($favouriteMaster[$favouriteId]->user_id != 0)
                                        <div class="checkbox info-message">
                                            <!-- Change indication icon -->
                                            @if(!empty($favouriteStoreChangeRequests[$storeId][$favouriteId]) &&  $favouriteStoreChangeRequests[$storeId][$favouriteId] == 1)
                                            <i class="zmdi zmdi-circle zmdi-hc-lg info_icon store_request_sent" style="color:#8fb3bc;" aria-hidden="true" data-toggle="tooltip" 
                                               data-placement="left" title="Diese Liste hast Du bereits angefragt">
                                            </i>
                                            <i class="zmdi zmdi-circle info_icon store_request_changed" style="color:#FC1794; display:none;" aria-hidden="true" data-toggle="tooltip" 
                                               data-placement="left" title="Diese Liste hast Du seit Deiner letzten Anfrage verändert.">
                                            </i>
                                            @elseif(!empty($favouriteStoreChangeRequests[$storeId][$favouriteId]) &&  $favouriteStoreChangeRequests[$storeId][$favouriteId] == 2)
                                            <i class="zmdi zmdi-circle info_icon" style="color:#FC1794;" aria-hidden="true" data-toggle="tooltip" 
                                               data-placement="left" title="Diese Liste hast Du seit Deiner letzten Anfrage verändert.">
                                            </i>
                                            @endif

                                            <label class="check_container">
                                                <input type="checkbox" class="favourite-checkbox-{{ $storeId }}" name="favourite_id[]" value="{{ $favouriteId }}">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <div id="funds-product-one-{{$storeId}}-{{$favouriteId}}" class="collapse" data-parent="#funds-accordion1">
                                    <div class="card-body">

                                        <div class="products-container">
                                            @foreach($favouriteItems as $favouriteItem)
                                            <!--                                            <form class="favourite-theme-product-container">
                                                                                            @csrf-->
                                            <div class="item-box">
                                                <div class="product-img">
                                                    <a href="#" class="open-product-detail-popup" 
                                                       data-product="{{ json_encode(collect($favouriteItem->product)->only(['code','name','slug','location', 'postal_code', 'year', 'quantity', 'replacement_value', 'custom_price_available'])) }}"
                                                       data-color="{{ $favouriteItem->product['color']['option_value'] ?? '' }}" 
                                                       data-color-name="{{ $favouriteItem->product['color']['option_display'] ?? '' }}" 
                                                       data-style="{{ $favouriteItem->product['style']['option_display'] ?? '' }}" 
                                                       data-epoche="{{ $favouriteItem->product['epocheText']['option_display'] ?? '' }}"
                                                       data-graphic_form="{{ $favouriteItem->product['graphicForm']['option_display'] ?? '' }}"
                                                       data-manufacturer_id="{{ $favouriteItem->product['manufacture']['option_display'] ?? '' }}"
                                                       data-manufacture_country="{{ $favouriteItem->product['manufactureCountry']['option_display'] ?? '' }}"
                                                       data-file_format="{{ $favouriteItem->product['fileFormat']['option_display'] ?? '' }}"
                                                       data-copy_right="{{ $favouriteItem->product['copyright']['option_display'] ?? '' }}"
                                                       data-price="{{ $favouriteItem->product['prices'] ?? '' }}"
                                                       data-dimensions="{{ $favouriteItem->product['dimensions'] ?? '' }}"
                                                       data-description="{{ $favouriteItem->product['description'] ?? '' }}" 
                                                       data-category="{{ $favouriteItem->product['category_name'] ?? '' }}"
                                                       data-parent-category-slug="{{ $favouriteItem->product['top_category_slug'] ?? '' }}"
                                                       data-image="{{ !empty($favouriteItem->product['image']) ? config('app.website_media_base_url') . $favouriteItem->product['image'] : ''}}" 
                                                       data-media='{{ isset($favouriteItem->product['productMedia']) ? $favouriteItem->product['productMedia']->pluck('file_name') : '' }}'
                                                       data-fundus-name="{{ $favouriteItem->product['fundusDetail']['fundus_name'] ?? '' }}" 
                                                       data-fundus-email="{{ $favouriteItem->product['fundusDetail']['fundus_email'] ?? '' }}" 
                                                       data-fundus-phone="{{ $favouriteItem->product['fundusDetail']['fundus_phone'] ?? '' }}" 
                                                       data-fundus-location="{{ $favouriteItem->product['fundusDetail']['fundus_location'] ?? '' }}"
                                                       data-fundus-store="{{route('store.index',[$favouriteItem->product['fundusDetail']['fundus_name'] ?? '']) }}"
                                                       data-fundus-logo="{{ !empty($favouriteItem->product['fundusDetail']['logo_image_path']) ? config('app.website_media_base_url') . $favouriteItem->product['fundusDetail']['logo_image_path'] : asset('assets/images/lm-logo.png') }}"

                                                       >
                                                        <img src="{{ !empty($favouriteItem->product['image']) ? config('app.website_media_base_url') . $favouriteItem->product['image'] : '' }}" width="400" height="200">
                                                    </a>
                                                </div>
                                                <div class="product-detail">
                                                    <div class="title-year">
                                                        <input type="hidden" name="favourite_item_id" value="{{ $favouriteItem->id }}">
                                                        <select multiple="multiple" name="selected_favourite_id[]" data-width="100%" class="select2-theme form-control fav-theme-motiv-change" data-id="{{ $favouriteItem->id }}">
                                                            @if(isset($favouriteMaster) && count($favouriteMaster) > 0)
                                                            @foreach($favouriteMaster as $userFavourite)
                                                            <option value="{{ $userFavourite->id }}" 
                                                                    @if(in_array($userFavourite->id, $productFavouriteList[$favouriteItem->product_id])) selected @endif
                                                                >{{ $userFavourite->name }}</option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="product-count">
                                                        <div class="counter fav-fundus-product-counter" data-id="{{ $favouriteItem->id }}">
                                                            <button type="button" class="btn decrement">-</button>
                                                            <input type="number" name="requested_item_count" class="form-control" value="{{ $favouriteItem->requested_count ?? '0' }}">
                                                            <button type="button" class="btn increment" data-available="{{ $favouriteItem->product['quantity'] ?? '0' }}">+</button>
                                                        </div>
                                                    </div>
                                                    <div class="status-count">
                                                        <span class="status"></span>
                                                        <span class="count">{{ $favouriteItem->product['quantity'] ?? '0' }}</span>
                                                    </div>
                                                    <input type="hidden" name="current_state_checksum" value="{{implode(',',$productFavouriteList[$favouriteItem->product_id])}}-{{ $favouriteItem->requested_count ?? '0' }}">
                                                </div>
                                                <div class="update-btn">
                                                    <button type="button" class="btn global-btn motiv-apply-button" style="display:none;"><div class="spinner-border button_spinner"></div> Änderungen übernehmen</button>
                                                </div>
                                            </div>
                                            <!--</form>-->
                                            @endforeach
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
    let categoryWiseFields = {!! json_encode(config('product.fields')) !!}
    ;
</script>
@endsection

@section('modal-windows')
@include('favourites.partials.input-pickup-drop-dates')
@include('favourites.partials.message-fundus')
@endsection