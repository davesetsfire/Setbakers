@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')
<section class="item-add-edit edit">
    <div class="container">
        <form action="{{ route('fundus.update', [$product->slug]) }}" method="post" enctype="multipart/form-data" id="product_edit_form">
            @method('PUT')
            @csrf
            <div class="row">
                <input type="hidden" name="current_selected_products" value="0" />
                <div class="col-md-6">
                    <div class="item-images">
                        <div class="item-img product_image">
                            <div class="img-upload lg">
                                <label>
                                    <span><i class="zmdi zmdi-plus"></i></span>
<!--                                    <input type="hidden" name="current_image[]" />-->
                                    <input type='file' onchange="readURL(this);" name="primary_product_image" accept="image/*"/>
                                    <img src="{{ !empty($product->image) ? config('app.website_media_base_url') . $product->image : ''}}" style="opacity: 1;" height="350" accept="image/*">
<!--                                    <button type="button" class="btn"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>-->
                                </label>
                            </div>
                            @if($errors->has('primary_product_image'))
                            <span class="error">{{$errors->first('primary_product_image')}}</span>
                            @endif
                        </div>
                        <div class="row product_image_upload_row">
                            @foreach($product->productMedia as $productMedia)
                            @if($productMedia['is_primary'] == 0)
                            <div class="col-sm-6">
                                <div class="upload-viewimg">
                                    <div class="item-img">
                                        <input type="hidden" name="current_image[]" value="{{ $productMedia['id'] }}" />
                                        <img src="{{ !empty($productMedia['file_name']) ? config('app.website_media_base_url') . $productMedia['file_name'] : ''}}">
                                        <button type="button" class="btn close-image-btn"><img src="{{ asset('assets/images/icons/cancel.png') }}"></button>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            <div class="col-sm-6">
                                <div class="img-upload">
                                    <label>
                                        <span><i class="zmdi zmdi-plus"></i></span>
                                        <input type='file' onchange="readURL(this);" name="product_image[]" accept="image/*" />
                                        <img id="preview" src=""/>
                                    </label>
                                </div>
                            </div>
                            @if($errors->has('product_image.0'))
                            <span class="error">{{$errors->first('product_image.0')}}</span>
                            @endif
                        </div>
                        <ul class="global-instruction-msg">
                            <li>Deine Bilder dürfen keine Logos oder eingebettete Texte enthalten die auf Dich oder Deinen Fundus verweisen</li>
                            <li>Maximale Dateigröße 7MB</li>
                            <li>Die Mindestgröße für Bilder ist {{config('app.image_thumbnail_max_width')}}x{{config('app.image_thumbnail_max_height')}}px</li>
                            <li>Unterstützte Dateiformate: jpg, jpeg, png oder gif</li>
                        </ul>
                        <div class="option mb-3">
                            <label class="check_container">
                                Wasserzeichen zu Bildern hinzufügen
                                <input type="checkbox" name="watermark" value="yes">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="keywords">
                            <label>Keywords</label>
                            <textarea class="form-control" 
                                      placeholder="Trennung durch Komma z.B. Telefon, historisch"
                                      rows="4" name="product_keywords"
                                      data-requisiten-und-einrichtung="Trennung durch Komma z.B. Telefon, historisch, Siemens, vintage"
                                      data-grafik="Trennung durch Komma z.B. Label, Bierflasche, Alkohol, brauen, Berlin"
                                      data-dienstleistung="Trennung durch Komma z.B. Sprache, Übersetzung, Mandarin, Englisch"
                                      data-fahrzeuge="Trennung durch Komma z.B. Mercedes, Deutschland, teuer, Oldtimer, selten"

                                      >{{ old('product_keywords', app('request')->input('product_keywords', $product->keywords ?? '')) }}</textarea>
                            @if($errors->has('product_keywords'))
                            <span class="error">{{$errors->first('product_keywords')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 edit_fundus">
                    <div class="item-details">
                        <label>Kategorie</label>
                        <p>In welcher Kategorie soll Dein Artikel angezeigt werden?</p>
                        <div class="custom-dropdown">
                            <div class="label">{{ old('category_name', app('request')->input('category_name', $product->productcategory[0]['name'] ?? 'Kategorie')) }}</div>
                            <input type="hidden" value="{{ old('category', app('request')->input('category', $product->productcategory[0]['id'] ?? '')) }}" name="category" id="" required>
                            <input type="hidden" value="{{ old('category_name', app('request')->input('category_name', $product->productcategory[0]['name'] ?? 'Kategorie')) }}" name="category_name">
                            <input type="hidden" value="{{ old('product_category_slug', app('request')->input('product_category_slug', $product->top_category_slug ?? 'blank')) }}" name="product_category_slug">
                            <div class="dropdown-wrap">
                                <button type="button" class="btn-second-label">
                                    <i class="zmdi zmdi-chevron-left"></i>
                                </button>
                                <div class="category-tab">
                                    <div class="row">
                                        <div class="col-sm-4 tab-content-level1">
                                            <div class="category-tab1-btn">
                                                <ul>
                                                    @php($firstItem = 1)
                                                    @foreach($categoryList[1] as $categoryItem)
                                                    @foreach($categoryItem as $categorySubItem)
                                                    <li><a href="#{{ 'cat'.$categorySubItem->id }}" class="s-parent-category {{ $categorySubItem->slug == $product->top_category_slug ? 'active': '' }}" data-slug="{{$categorySubItem->slug}}">{{ $categorySubItem->name }}</a></li>
                                                    @endforeach
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 tab-content-level2">
                                            <div class="category-tab2-btn">
                                                @foreach($categoryList[2] as $key => $categoryItem)
                                                <div class="content" id="{{ 'cat'.$key }}">
                                                    <ul>
                                                        @foreach($categoryItem as $categorySubItem)
                                                        @if(isset($categoryList[3][$categorySubItem->id]))
                                                        <li><a href="#{{ 'cat'.$categorySubItem->id }}">{{ $categorySubItem->name }}</a></li>
                                                        @else
                                                        <li><a href="#" class="option" data-option="{{ $categorySubItem->name }}" data-value="{{ $categorySubItem->id }}">{{ $categorySubItem->name }}</a></li>
                                                        @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-sm-4 tab-content-level3">
                                            <div class="category-tab-content">
                                                @foreach($categoryList[3] as $key => $categoryItem)
                                                <div class="content" id="{{ 'cat'.$key }}">
                                                    <ul>
                                                        @foreach($categoryItem as $categorySubItem)
                                                        <li><a href="#" class="option" data-option="{{ $categorySubItem->name }}"  data-value="{{ $categorySubItem->id }}">{{ $categorySubItem->name }}</a></li>
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
                        @if($errors->has('category'))
                        <span class="error">{{$errors->first('category')}}</span>
                        @endif
                        <div class="form-group mt-5">
                            <input type="text" class="form-control" 
                                   placeholder="Artikelbezeichnung" 
                                   data-requisiten-und-einrichtung="Artikelbezeichnung"
                                   data-grafik="Artikelbezeichnung"
                                   data-dienstleistung="Dienstleistung"
                                   data-fahrzeuge="Artikelbezeichnung"
                                   id="artikelbezeichnung" name="product_name" value="{{ old('product_name', app('request')->input('product_name', $product->name ?? '')) }}">
                            @if($errors->has('product_name'))
                            <span class="error">{{$errors->first('product_name')}}</span>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <textarea class="form-control" rows="3" placeholder="Beschreibung" name="product_description">{{ old('product_description', app('request')->input('product_description', $product->description ?? '')) }}</textarea>
                            @if($errors->has('product_description'))
                            <span class="error">{{$errors->first('product_description')}}</span>
                            @endif
                        </div>
                        <?php $parentCategory = old('product_category_slug', app('request')->input('product_category_slug', $product->top_category_slug ?? 'blank')); ?>
                        <?php $priceAttributeName = ($parentCategory == 'grafik') ? 'duration_graphics' : 'duration';  ?>
                        <div class="mt-40 new-article-field-quantity" style="{{ !in_array('quantity', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Menge</label>
                            <p>Wie viele Artikel dieser Art hast Du?</p>
                            <div class="product-count">
                                <div class="counter" style="width: 120px;">
                                    <button type="button" class="btn decrement">-</button>
                                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', app('request')->input('quantity', $product->quantity ?? 1)) }}">
                                    <button type="button" class="btn increment">+</button>
                                </div>
                                @if($errors->has('quantity'))
                                <span class="error">{{$errors->first('quantity')}}</span>
                                @endif
                            </div>
                        </div>

                        <div class="ort mt-35 new-article-field-graphic_form" style="{{ !in_array('graphic_form', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Verfügbarkeit</label>
                            <p>Wie stellts Du deine Grafik zur Verfügung?</p>
                            @foreach($attributes['graphic_form'] as $option)
                            <div class="option">
                                <label class="check_container">
                                    {{$option['option_display']}}
                                    <input type="radio" name="graphic_form" value="{{$option['id']}}" {{ old('graphic_form', app('request')->input('graphic_form', $product->graphic_form ?? '')) == $option['id'] ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('graphic_form'))
                            <span class="error">{{$errors->first('graphic_form')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field new-article-field-manufacturer_id" style="{{ !in_array('manufacturer_id', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Marke</label>
                            <p>Von welcher Marke ist Dein Fahrzeug</p>
                            <select class="select2-single" name="manufacturer_id" data-placeholder="Marke" data-width="200">
                                <option value="">Marke</option>
                                @foreach($attributes['manufacture'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('manufacturer_id', app('request')->input('manufacturer_id', $product->manufacturer_id ?? '')) == $option['id'])
                                    selected
                                    @endif
                                    >{{$option['option_display']}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('manufacturer_id'))
                            <span class="error">{{$errors->first('manufacturer_id')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field new-article-field-manufacture_country" style="{{ !in_array('manufacture_country', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Herstellerland</label>
                            <p>In welchem Land wurde das Fahrzeug produziert?</p>
                            <select class="select4-single" name="manufacture_country" data-placeholder="Herstellerland" data-width="200">
                                <option value="">Herstellerland</option>
                                @foreach($attributes['manufacture_country'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('manufacture_country', app('request')->input('manufacture_country', $product->manufacture_country ?? '')) == $option['id'])
                                    selected
                                    @endif
                                    >{{$option['option_display']}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('manufacture_country'))
                            <span class="error">{{$errors->first('manufacture_country')}}</span>
                            @endif
                        </div>
                        <div class="mt-35 price  new-article-field-price" style="{{ !in_array('price', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <input type="hidden" name="current_selected_prices" value="0" />
                            <label>Preis</label>
                            <p class="add-edit-price-q" data-default="Was ist der Preis pro definiertem Zeitraum?" data-grafik="Verkaufst Du die Grafik pro Stück oder die Nutzungslizenz für ein Filmprojekt?">
                                {{ $parentCategory == "grafik" ? "Verkaufst Du die Grafik pro Stück oder die Nutzungslizenz für ein Filmprojekt?" : "Was ist der Preis pro definiertem Zeitraum?" }}
                            </p>
                            @foreach(old('price', app('request')->input('price', !empty($product->prices) && count($product->prices)>0 ? $product->prices : [''])) as $key => $priceItem)
                            <div class="price-wrapper price-wrapper-change position-relative">
                                @if($key == 0)
                                <button type="button" class="btn add-price add-price-plus"><i class="zmdi zmdi-plus"></i></button>
                                @endif
                                <div class="article-price">
                                    <div class="row">
                                        <div class="col-sm-2 price_euro">
                                            <input type="hidden" name="price_index[]" value="{{ old('price_index', app('request')->input('price_index', []))[$key] ?? $priceItem['id'] ?? '' }}">
                                            <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="price[]" value="{{ old('price', app('request')->input('price', []))[$key] ?? $priceItem['price_value'] ?? '' }}">
                                            <span class="euro_symbool">€</span>
                                        </div>
                                        <div class="col-sm-1">pro</div>
                                        <div class="col-sm-2 new-article-field-price-duration" style="{{ !in_array('price-duration', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                                            <select  name="duration[]" data-placeholder="Tag" data-width="200">
                                                @foreach($attributes[$priceAttributeName] as $option)
                                                <option value="{{$option['option_display']}}" 
                                                        @if(old('duration', app('request')->input('duration', []))[$key] ?? ($priceItem['duration_text'] ?? '') == $option['option_display'])
                                                    selected
                                                    @endif
                                                    >{{$option['option_display']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @if($key > 0)
                                    <button type="button" class="btn add-price remove"><i class="zmdi zmdi-minus"></i></button>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                            <div class="add-price-input"></div>
                            <div class="option checkbox-option-style mb-3">                                
                                <input type="checkbox" name="custom_price_available" 
                                           value="1" {{ old('custom_price_available', app('request')->input('custom_price_available', $product->custom_price_available ?? '0')) == '1' ? 'checked' : '' }}>
                                <span class="checkmarks"></span>
                                <label class="add-edit-custom_price_available-q" 
                                       data-default="Pauschale möglich" data-grafik="Bei Stückpreis, Mengenrabatt möglich">
                                    {{ $parentCategory == "grafik" ? "Bei Stückpreis, Mengenrabatt möglich" : "Pauschale möglich" }}
                                </label>
                            </div>
                            @if($errors->has('price.0'))
                            <span class="error">{{$errors->first('price.0')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 new-article-field-replacement_value" style="{{ !in_array('replacement_value', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Wiederbeschaffungswert</label>
                            <p>Was kostet es, den Artikel im Falle eines Verlustes zu ersetzen?</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="replacement_value" value="{{ old('replacement_value', app('request')->input('replacement_value', $product->replacement_amount_value ?? '')) }}">
                                    <span class="euro_symbool euro_symbool_space">€</span>
                                </div>
                            </div>
                            @if($errors->has('replacement_value'))
                            <span class="error">{{$errors->first('replacement_value')}}</span>
                            @endif
                        </div>

                        <div class="ort mt-35 new-article-field-file_format" style="{{ !in_array('file_format', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Format</label>
                            <p>In welchem Dateiformat liegt die Grafik vor?</p>
                            @foreach($attributes['file_format'] as $option)
                            <div class="option">
                                <label class="check_container round-checkobox">
                                    {{$option['option_display']}}
                                    <input type="checkbox" name="file_format" value="{{$option['id']}}" {{ old('file_format', app('request')->input('file_format', $product->file_format ?? '')) == $option['id'] ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('file_format'))
                            <span class="error">{{$errors->first('file_format')}}</span>
                            @endif
                        </div>

                        <div class="ort mt-35 new-article-field-copy_right" style="{{ !in_array('copy_right', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Rechte</label>
                            <p>Wo liegen die rechte für diese Grafik?</p>
                            @foreach($attributes['copy_right'] as $option)
                            <div class="option">
                                <label class="check_container">
                                    {{$option['option_display']}}
                                    <input type="radio" name="copy_right" value="{{$option['id']}}" {{ old('copy_right', app('request')->input('copy_right', $product->copy_right ?? '')) == $option['id'] ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('copy_right'))
                            <span class="error">{{$errors->first('copy_right')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field new-article-field-epoche" style="{{ !in_array('epoche', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Epoche</label>
                            <p class="add-edit-epoche-q" data-default="Aus welcher Zeit stammt dein Artikel?" data-grafik="Aus welcher Zeit stammt deine Grafik?">
                                {{ $parentCategory == "grafik" ? "Aus welcher Zeit stammt deine Grafik?" : "Aus welcher Zeit stammt dein Artikel?" }}
                            </p>
                            <select class="select2-single" id="epoche" name="epoche" data-placeholder="Epoche" data-width="200">
                                <option value="">Epoche</option>
                                @foreach($attributes['epoche'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('epoche', app('request')->input('epoche', $product->epoche ?? '')) == $option['id'])
                                    selected
                                    @endif
                                    >{{$option['option_display']}}</option>
                                @endforeach
                            </select> 
                            @if($errors->has('epoche'))
                            <span class="error">{{$errors->first('epoche')}}</span>
                            @endif
                            <p class="mt-4">Du kennst das genaue Jahr?  Erspare Deinen Kunden zeitintensive Recherche!</p>
                            <input type="number" class="form-control sm" placeholder="Jahr" name="year" value="{{ old('year', app('request')->input('year', $product->year ?? '')) }}">
                        </div>

                        <div class="mt-35 input-field new-article-field-style" style="{{ !in_array('style', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Stil</label>
                            <p>Welchem Stil ist dein Artikel zuzuordnen?</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <select id="style" name="style" class="select2-single" data-placeholder="Stil" data-width="200">
                                        <option value="0">keiner</option>
                                        @foreach($attributes['style'] as $option)
                                        <option value="{{$option['id']}}" 
                                                @if(old('style', app('request')->input('style', $product->style_id ?? '')) == $option['id'])
                                            selected
                                            @endif
                                            >{{$option['option_display']}}</option>
                                        @endforeach
                                    </select> 
                                </div>
                            </div>
                            @if($errors->has('style'))
                            <span class="error">{{$errors->first('style')}}</span>
                            @endif
                        </div>
                        <div class="ort mt-35 new-article-field-location_at" style="{{ !in_array('location_at', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Standort</label>
                            <p class="add-edit-location-q" data-default="Wo befindet sich der Artikel?" data-dienstleistung="Wo bietest Du Deine Dienstleistung hauptsächlich an?">
                                {{ $parentCategory == "dienstleistung" ? "Wo bietest Du Deine Dienstleistung hauptsächlich an?" : "Wo befindet sich der Artikel?" }}
                            </p>
                            <div class="option">
                                <label class="check_container">
                                    <span class="add-edit-location-opt1" data-default="An meinem Fundusstandort" data-dienstleistung="An meinem Standort">
                                        {{ $parentCategory == "dienstleistung" ? "An meinem Standort" : "An meinem Fundusstandort" }}
                                    </span>
                                    <input type="radio" name="location_at" value="fundus" {{ old('location_at', app('request')->input('location_at', $product->location_at ?? '')) == 'fundus' ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="option">
                                <label class="check_container">
                                    <span class="add-edit-location-opt2" data-default="An einem anderen Standort" data-dienstleistung="An einem anderen Standort">An einem anderen Standort</span>
                                    <input type="radio" name="location_at" value="others" {{ old('location_at', app('request')->input('location_at', $product->location_at ?? '')) == 'others' ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="row mt-2" id="product_ort_add-edit" 
                                 @if(old('location_at', app('request')->input('location_at', $product->location_at ?? '')) == 'fundus')
                                style="display:none"
                                @endif
                                >
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <input type="hidden" id="geo_location" name="geo_location" value="{{ old('geo_location', app('request')->input('geo_location', $product->geo_loc ?? '')) }}" />
                                        <input type="text" id="location" name="location" class="form-control sm" placeholder="Ort" value="{{ old('location', app('request')->input('location', $product->location ?? '')) }}">
                                        @if($errors->has('location'))
                                        <span class="error">{{$errors->first('location')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="postal_code" class="form-control sm" placeholder="Postleitzahl" value="{{ old('postal_code', app('request')->input('postal_code', $product->postal_code ?? '')) }}">
                                        @if($errors->has('postal_code'))
                                        <span class="error">{{$errors->first('postal_code')}}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="farbe  mt-35 color-palette new-article-field-color" style="{{ !in_array('color', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Farbe</label>
                            <p>Welche Farbe kommt deinem Artikel am nähesten?</p>
                            <div class="colors">
                                @foreach($attributes['color'] as $option)
                                <label class="color">
                                    <input type="checkbox" name="color" value="{{$option['id']}}" 
                                           @if(old('color', app('request')->input('color', $product->color_id ?? 0)) == $option['id'])
                                    checked
                                    @endif
                                    >
                                    <span class="check-mark" style="background: #{{$option['option_value']}};border: 1px solid #000000;"><b class="color-name">{{$option['option_display']}}</b></span>
                                </label>
                                @endforeach
                            </div>
                            @if($errors->has('color'))
                            <span class="error">{{$errors->first('color')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field new-article-field-dimensions"  style="{{ !in_array('dimensions', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Artikelgröße</label>
                            <p>Welche Abmessungen hat Dein Artikel?</p>
                            <div class="dimension">
                                <div class="dimension-fields">
                                    <input type="text" class="form-control only-numeric-data" placeholder="Länge" name="length" value="{{ old('length', app('request')->input('length', $product->length_value ?? '')) }}">
                                    <span class="multiply">x</span>
                                    <input type="text" class="form-control only-numeric-data" placeholder="Breite" name="width" value="{{ old('width', app('request')->input('width', $product->width_value ?? '')) }}">
                                    <span class="multiply">x</span>
                                    <input type="text" class="form-control only-numeric-data" placeholder="Höhe" name="height" value="{{ old('height', app('request')->input('height', $product->height_value ?? '')) }}">
                                    <div class="unit">
                                        <select class="form-control select2-single" name="dimension_unit" data-minimum-results-for-search="Infinity">
                                            <option value="mm" {{ old('dimension_unit', app('request')->input('dimension_unit', $product->dimension_unit ?? '')) == "mm" ? 'selected' : '' }}>mm</option>
                                            <option value="cm" {{ old('dimension_unit', app('request')->input('dimension_unit', $product->dimension_unit ?? '')) == "cm" ? 'selected' : '' }}>cm</option>
                                            <option value="m" {{ old('dimension_unit', app('request')->input('dimension_unit', $product->dimension_unit ?? '')) == "m" ? 'selected' : '' }}>m</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if($errors->has('length'))
                            <span class="error">{{$errors->first('length')}}</span>
                            @endif
                            @if($errors->has('width'))
                            <span class="error">{{$errors->first('width')}}</span>
                            @endif
                            @if($errors->has('height'))
                            <span class="error">{{$errors->first('height')}}</span>
                            @endif
                        </div>

                        <div class="btns text-left mt-5">
                            <!--<input type="submit" class="global-btn" value="Artikeldetails übernehmen"/>-->
                            <button type="button" class="btn global-btn edit-product-button"><div class="spinner-border button_spinner"></div> Artikeldetails übernehmen</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
<div class="price-wrapper-clone d-none">
    <div class="price-wrapper price-wrapper-change">
        <div class="article-price">
            <div class="row">
                <div class="col-sm-2 price_euro">
                    <input type="hidden" name="price_index[]" value="">
                    <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="price[]">
                    <span class="euro_symbool">€</span>
                </div>
                <div class="col-sm-1">pro</div>
                <div class="col-sm-9 new-article-field-price-duration">
                    <select  name="duration[]" data-placeholder="Tag" data-width="200">
                        @foreach($attributes['duration'] as $option)
                        <option class="duration_option" value="{{$option['option_display']}}">{{$option['option_display']}}</option>
                        @endforeach
                        @foreach($attributes['duration_graphics'] as $option)
                        <option class="duration_graphics_option" value="{{$option['option_display']}}">{{$option['option_display']}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="button" class="btn add-price remove"><i class="zmdi zmdi-minus"></i></button>
        </div>
    </div>
</div>
<script>
    let categoryWiseFields = {!! json_encode($categoryWiseFields) !!}
    ;
</script>
@endsection