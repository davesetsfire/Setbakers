@extends('layouts.app')

@section('submenu')
@include('layouts.submenu.home')
@endsection

@section('content')
<section class="item-add-edit">
    <div class="container">
        <form action="{{ route('fundus.store') }}" method="post" enctype="multipart/form-data" id="product_add_form">
            <input type="hidden" name="orditemid" value="{{ old('orditemid', app('request')->input('orditemid', '')) }}">
            <input type="hidden" name="requested_count" value="{{ old('requested_count', app('request')->input('requested_count', '')) }}">
            <input type="hidden" name="unit_price" value="{{ old('unit_price', app('request')->input('unit_price', '')) }}">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="item-images">
                        <div class="item-img product_image">
                            <div class="img-upload lg">
                                <label>
                                    <span><i class="zmdi zmdi-plus"></i></span>
                                    <input type='file' onchange="readURL(this);" name="product_image[]" accept="image/*"/>
                                    <img id="preview" src=""/>
                                </label>
                            </div>
                        </div>
                        @if($errors->has('product_image'))
                        <span class="error">{{$errors->first('product_image')}}</span>
                        @endif
                        @if($errors->has('product_image.0'))
                        <span class="error">{{$errors->first('product_image.0')}}</span>
                        @endif
                        <div class="row product_image_upload_row">

                        </div>
                        <ul class="global-instruction-msg">
                            <li>Deine Bilder dürfen keine Logos oder eingebettete Texte enthalten die auf Dich oder Deinen Fundus verweisen</li>
                            <li>Maximale Dateigröße 7MB</li>
                            <li>Die Mindestgröße für Bilder ist {{config('app.image_thumbnail_max_width')}}x{{config('app.image_thumbnail_max_height')}}px</li>
                            <li>Unterstützte Dateiformate: jpg, jpeg, png oder gif</li>
                        </ul>
                        <div class="option mb-3">
                            <label>
                                Wasserzeichen zu Bildern hinzufügen
                                <input type="checkbox" name="watermark" value="yes">
                                <span class="checkmarks"></span>
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
                                      >{{ old('product_keywords', app('request')->input('product_keywords', '')) }}</textarea>
                            @if($errors->has('product_keywords'))
                            <span class="error">{{$errors->first('product_keywords')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="item-details">
                        <div class="input-field">
                            <label>Kategorie</label>
                            <p>In welcher Kategorie soll Dein Artikel angezeigt werden?</p>
                            <div class="custom-dropdown">
                                <div class="label">{{ old('category_name', app('request')->input('category_name', 'Kategorie')) }}</div>
                                <input type="hidden" value="{{ old('category', app('request')->input('category', '')) }}" name="category" id="" required>
                                <input type="hidden" value="{{ old('category_name', app('request')->input('category_name', 'Kategorie')) }}" name="category_name">
                                <input type="hidden" value="{{ old('product_category_slug', app('request')->input('product_category_slug', 'blank')) }}" name="product_category_slug">
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
                                                        <li><a href="#{{ 'cat'.$categorySubItem->id }}" class="s-parent-category {{ $firstItem++ == 1 ? 'active': '' }}" data-slug="{{$categorySubItem->slug}}">{{ $categorySubItem->name }}</a></li>
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
                        </div>
                        <div class="form-group mt-5">
                            <input type="text" class="form-control" 
                                   placeholder="Artikelbezeichnung" 
                                   data-requisiten-und-einrichtung="Artikelbezeichnung"
                                   data-grafik="Artikelbezeichnung"
                                   data-dienstleistung="Dienstleistung"
                                   data-fahrzeuge="Artikelbezeichnung"
                                   id="artikelbezeichnung" name="product_name" value="{{ old('product_name', app('request')->input('product_name', '')) }}">
                            @if($errors->has('product_name'))
                            <span class="error">{{$errors->first('product_name')}}</span>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <textarea class="form-control" rows="3" placeholder="Beschreibung" name="product_description">{{ old('product_description', app('request')->input('product_description', '')) }}</textarea>
                            @if($errors->has('product_description'))
                            <span class="error">{{$errors->first('product_description')}}</span>
                            @endif
                        </div>
                        <?php $parentCategory = old('product_category_slug', app('request')->input('product_category_slug', 'blank')); ?>

                        <div class="mt-40 input-field new-article-field-quantity" style="{{ !in_array('quantity', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Menge</label>
                            <p>Wie viele Artikel dieser Art hast Du?</p>
                            <div class="product-count">
                                <div class="counter" style="width: 120px;">
                                    <button type="button" class="btn decrement">-</button>
                                    <input type="number" name="quantity" class="form-control" value="{{ old('quantity', app('request')->input('quantity', 1)) }}">
                                    <button type="button" class="btn increment">+</button>
                                </div>
                                @if($errors->has('quantity'))
                                <span class="error">{{$errors->first('quantity')}}</span>
                                @endif
                            </div>
                        </div>

                        <div class="ort input-field mt-35 new-article-field-graphic_form" style="{{ !in_array('graphic_form', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Verfügbarkeit</label>
                            <p>Wie stellts Du deine Grafik zur Verfügung?</p>
                            @foreach($attributes['graphic_form'] as $option)
                            <div class="option">
                                <label class="check_container">
                                    {{$option['option_display']}}
                                    <input type="radio" name="graphic_form" value="{{$option['id']}}" {{ old('graphic_form', app('request')->input('graphic_form', '')) == $option['id'] ? 'checked' : '' }}>
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
                            <select class="select2-single" name="manufacturer_id" data-placeholder="Marke" data-width="100%">
                                <option value="">Marke</option>
                                @foreach($attributes['manufacture'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('manufacturer_id', app('request')->input('manufacturer_id', '')) == $option['id'])
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
                            <select class="select4-single" name="manufacture_country" data-placeholder="Herstellerland" data-width="100%">
                                <option value="">Herstellerland</option>
                                @foreach($attributes['manufacture_country'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('manufacture_country', app('request')->input('manufacture_country', '')) == $option['id'])
                                    selected
                                    @endif
                                    >{{$option['option_display']}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('manufacture_country'))
                            <span class="error">{{$errors->first('manufacture_country')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field price  new-article-field-price" style="{{ !in_array('price', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Preis</label>
                            <p class="add-edit-price-q" data-default="Was ist der Preis pro definiertem Zeitraum?" data-grafik="Verkaufst Du die Grafik pro Stück oder die Nutzungslizenz für ein Filmprojekt?">Was ist der Preis pro definiertem Zeitraum?</p>
                            @foreach(old('price', app('request')->input('price', [''])) as $key => $priceItem)
                            <div class="price-wrapper price-wrapper-change position-relative">
                                <button type="button" class="btn add-price add-price-plus"><i class="zmdi zmdi-plus"></i></button>
                                <div class="article-price">
                                    <div class="row">
                                        <div class="col-sm-2 price_euro">
                                            <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="price[]" value="{{ $priceItem ?? '' }}">
                                            <span class="euro_symbool">€</span>
                                        </div>
                                        <div class="col-sm-1">pro</div>
                                        <div class="col-sm-2 new-article-field-price-duration">
                                            <select name="duration[]" data-placeholder="Tag" data-width="200">
                                                @foreach($attributes['duration'] as $option)
                                                <option value="{{$option['option_display']}}" 
                                                        @if(old('duration', app('request')->input('duration', '')) == $option['option_display'])
                                                    selected
                                                    @endif
                                                    >{{$option['option_display']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <button type="button" class="btn add-price remove"><i class="zmdi zmdi-minus"></i></button>
                                </div>
                            </div>
                            @endforeach
                            <div class="add-price-input"></div>
                            <div class="option checkbox-option-style mb-3">
                                <input type="checkbox" name="custom_price_available" value="1">
                                <span class="checkmarks"></span>
                                <label class="add-edit-custom_price_available-q" 
                                       data-default="Pauschale möglich" data-grafik="Bei Stückpreis, Mengenrabatt möglich">
                                    Pauschale möglich
                                </label>
                            </div>
                            @if($errors->has('price.0'))
                            <span class="error">{{$errors->first('price.0')}}</span>
                            @endif
                        </div>

                        <div class="mt-35 input-field new-article-field-replacement_value" style="{{ !in_array('replacement_value', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Wiederbeschaffungswert</label>
                            <p>Was kostet es, den Artikel im Falle eines Verlustes zu ersetzen?</p>
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="replacement_value" value="{{ old('replacement_value', app('request')->input('replacement_value', '')) }}">
                                    <span class="euro_symbool euro_symbool_space">€</span>
                                </div>
                            </div>
                            @if($errors->has('replacement_value'))
                            <span class="error">{{$errors->first('replacement_value')}}</span>
                            @endif
                        </div>

                        <div class="ort input-field mt-35 new-article-field-file_format" style="{{ !in_array('file_format', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Format</label>
                            <p>In welchem Dateiformat liegt die Grafik vor?</p>
                            @foreach($attributes['file_format'] as $option)
                            <div class="option">
                                <label class="check_container round-checkobox">
                                    {{$option['option_display']}}
                                    <input type="checkbox" name="file_format" value="{{$option['id']}}" {{ old('file_format', app('request')->input('file_format', '')) == $option['id'] ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            @endforeach
                            @if($errors->has('file_format'))
                            <span class="error">{{$errors->first('file_format')}}</span>
                            @endif
                        </div>

                        <div class="ort input-field mt-35 new-article-field-copy_right" style="{{ !in_array('copy_right', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Rechte</label>
                            <p>Wo liegen die rechte für diese Grafik?</p>
                            @foreach($attributes['copy_right'] as $option)
                            <div class="option">
                                <label class="check_container">
                                    {{$option['option_display']}}
                                    <input type="radio" name="copy_right" value="{{$option['id']}}" {{ old('copy_right', app('request')->input('copy_right', '')) == $option['id'] ? 'checked' : '' }}>
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
                            <p class="add-edit-epoche-q" data-default="Aus welcher Zeit stammt dein Artikel?" data-grafik="Aus welcher Zeit stammt deine Grafik?">Aus welcher Zeit stammt dein Artikel?</p>
                            <select class="select2-single" id="epoche" name="epoche" data-placeholder="Epoche" data-width="200">
                                <option value="">Epoche</option>
                                @foreach($attributes['epoche'] as $option)
                                <option value="{{$option['id']}}" 
                                        @if(old('epoche', app('request')->input('epoche', '')) == $option['id'])
                                    selected
                                    @endif
                                    >{{$option['option_display']}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('epoche'))
                            <span class="error">{{$errors->first('epoche')}}</span>
                            @endif
                            <p class="mt-4">Du kennst das genaue Jahr?  Erspare Deinen Kunden zeitintensive Recherche!</p>
                            <input type="number" class="form-control sm" placeholder="Jahr" name="year" value="{{ old('year', app('request')->input('year', '')) }}">
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
                                                @if(old('style', app('request')->input('style', '')) == $option['id'])
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
                        <div class="ort mt-35 input-field new-article-field-location_at" style="{{ !in_array('location_at', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Standort</label>
                            <p class="add-edit-location-q" data-default="Wo befindet sich der Artikel?" data-dienstleistung="Wo bietest Du Deine Dienstleistung hauptsächlich an?">Wo befindet sich der Artikel?</p>
                            <div class="option">
                                <label class="check_container">
                                    <span class="add-edit-location-opt1" data-default="An meinem Fundusstandort" data-dienstleistung="An meinem Standort">An meinem Fundusstandort</span>
                                    <input type="radio" name="location_at" value="fundus" {{ old('location_at', app('request')->input('location_at', 'fundus')) == 'fundus' ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="option">
                                <label class="check_container">
                                    <span class="add-edit-location-opt2" data-default="An einem anderen Standort" data-dienstleistung="An einem anderen Standort">An einem anderen Standort</span>
                                    <input type="radio" name="location_at" value="others" {{ old('location_at', app('request')->input('location_at', '')) == 'others' ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="row mt-2" id="product_ort_add-edit" 
                                 @if(old('location_at', app('request')->input('location_at', 'fundus')) == 'fundus')
                                style="display:none"
                                @endif
                                >
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <input type="hidden" id="geo_location" name="geo_location" value="{{ old('geo_location', app('request')->input('geo_location', '')) }}" />
                                        <input type="text" id="location" name="location" class="form-control sm" placeholder="Ort" value="{{ old('location', app('request')->input('location', '')) }}">
                                        @if($errors->has('location'))
                                        <span class="error">{{$errors->first('location')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input type="text" name="postal_code" class="form-control sm" placeholder="Postleitzahl" value="{{ old('postal_code', app('request')->input('postal_code', '')) }}">
                                        @if($errors->has('postal_code'))
                                        <span class="error">{{$errors->first('postal_code')}}</span>
                                        @endif
                                    </div>
                                </div>
                                <!--<span class="error">The field is required</span>-->
                            </div>
                        </div>
                        <div class="farbe input-field mt-35 color-palette new-article-field-color" style="{{ !in_array('color', $categoryWiseFields[$parentCategory]) ? 'display:none;'  : ''}}">
                            <label>Farbe</label>
                            <p>Welche Farbe kommt deinem Artikel am nähesten?</p>
                            <div class="colors">
                                @foreach($attributes['color'] as $option)
                                <label class="color">
                                    <input type="checkbox" name="color" value="{{$option['id']}}"
                                           @if(old('color', app('request')->input('color', 0)) == $option['id'])
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
                                    <input type="text" class="form-control only-numeric-data" placeholder="Länge" name="length" value="{{ old('length', app('request')->input('length')) }}">
                                    <span class="multiply">x</span>
                                    <input type="text" class="form-control only-numeric-data" placeholder="Breite" name="width" value="{{ old('width', app('request')->input('width')) }}">
                                    <span class="multiply">x</span>
                                    <input type="text" class="form-control only-numeric-data" placeholder="Höhe" name="height" value="{{ old('height', app('request')->input('height')) }}">
                                    <div class="unit">
                                        <select class="form-control select2-single" data-width="100" name="dimension_unit" data-minimum-results-for-search="Infinity">
                                            <option value="mm" {{ old('dimension_unit', app('request')->input('dimension_unit', '')) == "mm" ? 'selected' : '' }}>mm</option>
                                            <option value="cm" {{ old('dimension_unit', app('request')->input('dimension_unit', '')) == "cm" ? 'selected' : '' }}>cm</option>
                                            <option value="m" {{ old('dimension_unit', app('request')->input('dimension_unit', '')) == "m" ? 'selected' : '' }}>m</option>
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
                            <button type="button" class="btn global-btn create-product-button"><div class="spinner-border button_spinner"></div> Artikeldetails übernehmen</button>
                            <!--<div class="spinner-border button_spinner"></div> <input type="button" class="global-btn create-product-button" value="Artikeldetails übernehmen"/>-->
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
                    <input type="text" class="form-control sm text-right only-numeric-data" placeholder="" name="price[]">
                    <span class="euro_symbool">€</span>
                </div>
                <div class="col-sm-1">pro</div>
                <div class="col-sm-9 new-article-field-price-duration">
                    <select name="duration[]" data-placeholder="Tag" data-width="200">
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

    $(function () {
        if (window.opener) {
            let sourceFile = window.opener.$('#fundus_inquiry_add_product_form').find('[name="product_image"]');
            let productImageReader = new FileReader();
            let destinationFile = $('input[name="product_image[]"]');
            let destinationFilePreview = $('#preview');
            let files = sourceFile[0].files;
            let dt = new DataTransfer();

            for (let i = 0; i < files.length; i++) {
                let f = files[i];
                dt.items.add(
                        new File(
                                [f.slice(0, f.size, f.type)],
                                f.name
                                ));
            }

            destinationFile[0].files = dt.files;

            productImageReader.onload = function (e) {
                destinationFilePreview.attr('src', e.target.result).css('opacity', 1);

            };

            if (destinationFile[0].files.length > 0) {
                productImageReader.readAsDataURL(destinationFile[0].files[0]);
            }
        }

        @if(Auth::check() && !isset($userImpressions['article-creation-message']))
         $('#fundus-notice-model').modal({
            backdrop: 'static',
            keyboard: false
        }, 'show');
        
        $('#fundus_notice_approval').click(function () {
            if($(this).is(':checked')) { 
                $('.fundus-notice-btn').attr('disabled',false);
            }else{
                $('.fundus-notice-btn').attr('disabled',true);
            }  
        });
        @endif

    });
</script>
@endsection

@section('modal-windows')
@include('partials.article-creation-message')
@endsection