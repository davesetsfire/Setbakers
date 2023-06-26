@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.favourite')
@endsection

@section('content')
<section class="edit-products-wrap minheight_850 favourite_main_section" id="favourite-theme">
    <div class="container">
        @if(session('errors'))
        @foreach(session('errors')->all() as $errorText)
        <div class="error-msg">
            <i class="zmdi zmdi-close-circle"></i>
            {{ $errorText }}
        </div>
        @endforeach
        @endif
        @if(session('success_message'))
        <div class="success-msg">
            <i class="zmdi zmdi-check"></i>
            {{ session('success_message') }}
        </div>
        @endif
        <div class="edits-wrapper @if(Auth::check() && !isset($userImpressions['hintbox-create-motif-shooting-time'])) position-relative @endif">
            @if(Auth::check() && !isset($userImpressions['hintbox-create-motif-shooting-time']))
             <div class="hint-box-global hint-check-top-right border-bottom-right-radius user-impression-block">
                <p>{{ __('hintbox.CREATE_MOTIF_SHOOTING_TIME_HINT') }}</p>
                <div class="hint-check">
                    <div class="option">
                        <label>
                            Copy!
                            <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-create-motif-shooting-time" data-impression-value="yes">
                            <span class="checkmarks"></span>
                        </label>
                    </div>
                </div>
            </div>
            @endif
            <div class="text-right hint_bookmarks mb-3 @if(Auth::check() && !isset($userImpressions['hintbox-motif-bookmark'])) position-relative @endif">
                @if(Auth::check() && !isset($userImpressions['hintbox-motif-bookmark']))
                 <div class="hint-box-global text-left border-bottom-left-radius user-impression-block">
                    <p>{{ __('hintbox.MOTIF_BOOKMARK_HINT') }}</p>
                    <div class="hint-check">
                        <div class="option">
                            <label>
                                Check
                                <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-motif-bookmark" data-impression-value="yes">
                                <span class="checkmarks"></span>
                            </label>
                        </div>
                    </div>
                </div>
                @endif
                <button type="button" class="btn global-btn motive-add-btn">Motiv hinzufügen</button>
            </div>
            <div class="global-accordion" id="accordion3">
                @foreach($userFavourites as $key => $favourite)
                <div class="card {{ $key == 0 ? 'card-add-template' : '' }}">
                    <form id="favourite-motiv-form-{{ $favourite->id }}" action="{{ route('favourites.update', [$favourite->id]) }}" method="POST" class="favourite-form-validator">
                        @csrf
                        <input type="hidden" name="_method" value="PUT"/>
                        <div class="card-header position-relative">
                            <div class="clickdate_booking"  data-toggle="collapse" href="#funds-details-one-{{ $favourite->id }}"></div>
                            <div class="motive-children-header">
                                <div class="motive-children-header-left d-flex">
                                <a class="card-link" data-toggle="collapse" href="#funds-details-one-{{ $favourite->id }}"></a>

                                <div class="input-fields">
                                    @if($favourite->user_id == 0)
                                    <div class="name-count">
                                        <input type="text" class="form-control edit-funds-name edit-funds-name-first" value="{{ $favourite->name ?? ''}}" disabled>
                                        <span class="count">({{ count($favourite->favouriteItems) }})</span>
                                    </div>
                                    @else
                                    <div class="dates-container">
                                        @foreach($favourite->favouriteDateRanges as $key => $dateRange)
                                        <div class="{{ $key == 0 ? 'wrapper' : 'dates-wrapper' }}">
                                            <input type="hidden" name="range_id[]" value="{{ $dateRange['id'] }}">
                                            <input type="text" class="form-control daterange-single2-fb-theme start_date" placeholder="von" name="start_date[]" value="{{ $dateRange['start_date']->format('d.m.Y') ?? '' }}" readonly="off">
                                            <span class="mx-1">-</span>
                                            <input type="text" class="form-control daterange-single2-fb-theme end_date" placeholder="bis" name="end_date[]" value="{{ $dateRange['end_date']->format('d.m.Y') ?? '' }}" readonly="">
                                            @if($key == 0)
                                            <button type="button" class="remove-add-btn" id="add-btn">
                                                <i class="zmdi zmdi-plus"></i>
                                            </button>
                                            @else
                                            <button type="button" class="remove-add-btn" id="remove-btn">
                                                <i class="zmdi zmdi-minus"></i>
                                            </button>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="name-count">
                                        <input type="text" class="form-control edit-funds-name" placeholder="Motivname" name="name" value="{{ $favourite->name ?? ''}}" readonly="">
                                        <span class="count">({{ count($favourite->favouriteItems) }})</span>
                                    </div>
                                    @endif
                                </div>
                                <!--                                <div class="favourites-error-placeholder">
                                                                    <label id="start_date-error" class="error" for="start_date"></label>
                                                                    <label id="end_date-error" class="error" for="end_date"></label>
                                                                    <label id="name-error" class="error" for="name"></label>
                                                                </div>-->
                            </div>
                            @if($favourite->user_id != 0)
                            <div class="group-theme-button">
                                <a href="#motiv-delete-popup" data-action="{{ route('favourites.remove', $favourite->id) }}" type="button" class="btn delete-button mr-2 motiv-delete-item" style="display:none"><i class="zmdi zmdi-delete"></i></a>
                                <button type="button" class="btn edit-button"><i class="zmdi zmdi-edit"></i><div class="spinner-border button_spinner"></div><span class="save" data-favourite="{{ $favourite->id }}" style="display:none">übernehmen</span></button>
                            </div>
                            @endif
                            </div>
                        </div>
                    </form>

                    <div id="funds-details-one-{{ $favourite->id }}" class="collapse" data-parent="#accordion3">
                        <div class="card-body">
                            <div class="products-container">
                                @foreach($favourite->favouriteItems as $favouriteItem)
                                <form class="favourite-theme-product-container">
                                    @csrf
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
                                            @if(Auth::check() && !isset($userImpressions['hintbox-product-image']))
                                                 <div class="hint-box-global hint-check-top-right border-bottom-right-radius user-impression-block">
                                                    <p>{{ __('hintbox.PRODUCT_IMAGE_HINT') }}</p>
                                                    <div class="hint-check">
                                                        <div class="option">
                                                            <label>
                                                                Verstanden
                                                                <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-product-image" data-impression-value="yes">
                                                                <span class="checkmarks"></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                        </div>
                                        <div class="product-detail">
                                            <div class="title-year">
                                                <input type="hidden" name="favourite_item_id" value="{{ $favouriteItem->id }}">
                                                <select multiple="multiple" name="selected_favourite_id[]" data-width="100%" class="select2-theme form-control fav-theme-motiv-change" data-id="{{ $favouriteItem->id }}">
                                                    @if(isset($userFavourites) && count($userFavourites) > 0)
                                                    @foreach($userFavourites as $userFavourite)
                                                    <option value="{{ $userFavourite->id }}" 
                                                            @if(in_array($userFavourite->id, $productFavouriteList[$favouriteItem->product_id])) selected @endif
                                                        >{{ $userFavourite->name }}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="product-count">
                                                <div class="counter fav-theme-product-counter" data-id="{{ $favouriteItem->id }}">
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
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
<script>
    let newCardElement =
            '<div class="card edit-mode">' +
            '<form id="favourite-motiv-form-new-" method="POST" class="favourite-form-validator">' +
            '<input type="hidden" name="_token" value="{{ csrf_token() }}">' +
            '<div class="card-header">' +
            '<a class="card-link" data-toggle="collapse" href="#funds-details-one-new-">' +
            '<div class="input-fields">' +
            '<div class="dates-container">' +
            '<div class="wrapper">' +
            '<div>' +
            '<input type="text" class="form-control daterange-single2add start_date" placeholder="von" name="start_date[]" value="" readonly="off">' +
            '</div>' +
            '<span class="mx-1">-</span>' +
            '<div>' +
            '<input type="text" class="form-control daterange-single2add end_date" placeholder="bis" name="end_date[]" value="" readonly="">' +
            '</div>' +
            '<button type="button" class="remove-add-btn" id="add-btn"><i class="zmdi zmdi-plus"></i></button>' +
            '</div>' +
            '</div>' +
            '<div>' +
            '<input type="text" class="form-control edit-funds-name" placeholder="Motivname" name="name" value="" readonly="">' +
            '</div>' +
            '</div>' +
            '<div class="favourites-error-placeholder"><label id="start_date-error" class="error" for="start_date"></label><label id="end_date-error" class="error" for="end_date"></label><label id="name-error" class="error" for="name"></label></div>' +
            '</a>' +
            '<button type="button" class="btn edit-button funds-details-one-new-9"><i class="zmdi zmdi-edit"></i><div class="spinner-border button_spinner"></div><span class="save" data-favourite="">übernehmen</span></button>' +
            '</div>' +
            '</form>' +
            '<div id="funds-details-one-new-" class="collapse" data-parent="#accordion3">' +
            '<div class="card-body">' +
            '<div class="products-container">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
</script>
<script type="text/javascript">
    let categoryWiseFields = {!! json_encode(config('product.fields')) !!}
    ;
</script>

@endsection
@section('modal-windows')
<!-- modal motiv delete confirmation -->
<div class="modal fade global-modal" id="motiv-delete-popup">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <button class="close btn" data-toggle="modal" data-target="#motiv-delete-popup"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
            <div class="modal-body">
                <form action="" method="GET" id="motiv_delete_form">
                    @csrf
                    <h3>Motiv löschen</h3>
                    <p>Möchtest Du dieses Motiv wirklich löschen? </br>
                        Alle darin enthaltenen Favoriten werden dadurch entfernt.</p>
                    <div class="btns">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn mb-2 global-btn style2 btn-block" data-toggle="modal" data-target="#motiv-delete-popup">Abbrechen</button>
                            </div>
                            <div class="col-sm-6">
                                <button type="submit" class="btn mb-2 global-btn btn-block">Motiv löschen</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection