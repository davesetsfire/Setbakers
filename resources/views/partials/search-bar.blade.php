<form action="{{ Auth::check() && Route::currentRouteNamed('index') ? route('product.category', app('request')->route('category', '')) : route(Route::currentRouteName(), app('request')->route('category', '')) }}" id="frm-article-search">
    <div class="filter-form showfilter @if(Auth::check() && !isset($userImpressions['hintbox-articles'])) position-relative @endif">

        @php
        $searchGeoText = '';
        $searchGeoLocation = '';
        $searchSearchRange = '';

        $searchLocationArray = explode('::', $userImpressions['search-location']?? '');
        if(empty($fundusIndex)) {
        $searchGeoText = $searchLocationArray[0] ?? '';
        $searchGeoLocation = $searchLocationArray[1] ?? '';
        $searchSearchRange = $searchLocationArray[2] ?? '';
        }

        @endphp

        <?php $categoryWiseFields = config('product.fields'); ?>
        <?php $selectedCategory = !empty($selectedCategory) ? $selectedCategory : 'blank'; ?>

        @if(Auth::check() && !isset($userImpressions['hintbox-articles']))
        <div class="hint-box-global hint-check-top-right border-top-right-radius user-impression-block">
            <div class="hint-check">
                <div class="option">
                    <label>
                        Copy!
                        <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-articles" data-impression-value="yes">
                        <span class="checkmarks"></span>
                    </label>
                </div>
            </div>
            <p>{{ __('hintbox.LIMIT_ARTICLES_HINT') }}</p>
        </div>
        @endif   
        <div class="input-box">
            <div class="dropdown">{{ old('searched_category_name', app('request')->input('searched_category_name', 'ALLE KATEGORIEN')) ?? 'ALLE KATEGORIEN' }}</div>
            <input type="hidden" id="searched_category_id" name="searched_category_id" value="{{ old('searched_category_id', app('request')->input('searched_category_id', '')) }}">
            <input type="hidden" id="searched_category_name" name="searched_category_name" value="{{ old('searched_category_name', app('request')->input('searched_category_name', '')) }}">
            <div class="search-box {{ empty($selectedCategory) ? 'full-width' : '' }}">
                <input type="text" id="search_text" name="search_text" class="form-control input" placeholder="{{ !empty($fundusIndex) && $fundusIndex == 1 ? 'Fundus durchsuchen' : 'Durchsuchen' }}" autocomplete="off"
                       value="{{ old('search_text', app('request')->input('search_text', '')) }}">
                <div id="result">
                    <div select2-id="mindestmenge" id="mindestmenge-data" class="data">
                        <div class="value-wrap {{ app('request')->input('min_amount', '') != '' ? 'd-flex' : '' }}">
                            <span class="value">
                                {{ app('request')->input('min_amount', '') != '' ? '> ' . app('request')->input('min_amount', '') : '' }}
                            </span>
                            <button type="button">×</button>
                        </div>
                    </div>
                    <div select2-id="farbe" class="data"></div>
                    <div select2-id="epoche" class="data"></div>
                    <div select2-id="stil" class="data"></div>
                    <div select2-id="file_format" class="data"></div>
                    <div select2-id="graphic_form" class="data"></div>
                    <div select2-id="copy_right" class="data"></div>
                    <div select2-id="manufacturer_id" class="data"></div>
                    <div select2-id="manufacture_country" class="data"></div>
                    <!-- <div select2-id="ortorder" class="data"></div> -->
                    @if(in_array('location_at', $categoryWiseFields[$selectedCategory]) || $selectedCategory == 'blank')
                    <div select2-id="location-distance" id="location-distance" class="data">
                        <div class="value-wrap {{ app('request')->input('location', '') != '' ? 'd-flex' : '' }}">
                            <span class="value loc">
                                {{ app('request')->input('location', '') }}
                            </span>
                            <span class="value km">
                                {{ app('request')->input('location', '') != '' && app('request')->input('radius', '') != '' && app('request')->input('radius', '') != '0' ? '+'.app('request')->input('radius', '') . ' km' : '' }}
                            </span>
                            <button type="button">×</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- category dropdown start -->
            <div class="category-dropdown">
                <input type="hidden" value="" id="category_value">
                <button type="button" class="btn-second-label">
                    <i class="zmdi zmdi-chevron-left"></i>
                </button>
                <div class="row">
                    <div class="col-sm-3 col-left">
                        <ul class="text-right label0">
                            <li><a href="#" data-option="ALLE KATEGORIEN" data-value="0">Alle</a></li>
                            @if(isset($categoryList[$categoryLevel][$globalRouteCategoryId]))
                            @foreach($categoryList[$categoryLevel][$globalRouteCategoryId] as $key => $categoryItem)
                            @if(isset($categoryList[$categoryLevel+1][$categoryItem->id]))
                            <li><a href="#cat{{ $categoryItem->id }}" data-label="1">{{ $categoryItem->name }}</a></li>
                            @else
                            <li><a href="#" data-option="{{ $categoryItem->name }}" data-value="{{ $categoryItem->id }}">{{ $categoryItem->name }}</a></li>
                            @endif
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="col-sm-9 col-right">
                        <div class="row">
                            <div class="col-sm-3 col-right-left">
                                <div class="content-wrap label1" data-content="1">
                                    @php($categoryLevel++)
                                    @foreach($categoryList[$categoryLevel] as $key => $categorySlugItem)
                                    <div class="tab_content" id="{{ 'cat' . $key }}">
                                        <ul>
                                            <li><a href="#" data-option="ALLE {{ $globalCategoryMaster['id'][$key]->name }}" data-value="{{ $key }}" class="">Alle</a></li>
                                            @foreach($categorySlugItem as $categoryItem)
                                            @if(isset($categoryList[$categoryLevel+1][$categoryItem->id]))
                                            <li><a href="#cat{{ $categoryItem->id }}" data-label="2">{{ $categoryItem->name }}</a></li>
                                            @else
                                            <li><a href="#" data-option="{{ $categoryItem->name }}" data-value="{{ $categoryItem->id }}">{{ $categoryItem->name }}</a></li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-sm-9 col-right-right">
                                <div class="content-wrap label2" data-content="2">
                                    @php($categoryLevel++)
                                    @if(isset($categoryList[$categoryLevel]))
                                    @foreach($categoryList[$categoryLevel] as $key => $categorySlugItem)
                                    <div class="tab_content" id="{{ 'cat' . $key }}">
                                        <ul>
                                            <li><a href="#" data-option="ALLE {{ $globalCategoryMaster['id'][$key]->name }}" data-value="{{ $key }}" class="">Alle</a></li>
                                            @foreach($categorySlugItem as $categoryItem)
                                            @if(isset($categoryList[$categoryLevel+1][$categoryItem->id]))
                                            <li><a href="#cat{{ $categoryItem->id }}" data-label="3">{{ $categoryItem->name }}</a></li>
                                            @else
                                            <li><a href="#" data-option="{{ $categoryItem->name }}" data-value="{{ $categoryItem->id }}">{{ $categoryItem->name }}</a></li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- category dropdown end -->
        </div>
        <button type="submit" class="search-btn btn"><i class="zmdi zmdi-search"></i></button>
    </div>
    <!--<div class="selects-wrapper {{ !empty(old('searched_category_id', app('request')->input('searched_category_id', ''))) && !empty(old('search_text', app('request')->input('search_text', ''))) ? 'open' : '' }}">-->
    <div class="selects-wrapper
         {{ count(collect(request()->all())->filter(function($item, $key) {
            return !empty($item) && $key != 'searched_category_name' && $key!= 'searched_category_id';
        })->toArray()) > 0 ? 'open' : '' }}
         {{ !empty(old('searched_category_id', app('request')->input('searched_category_id', ''))) ? 'open' : '' }}">
        <div class="filter-selects">

            <input type="number" class="form-control" min="0" 
                   oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" 
                   data-width="170" placeholder="Mindestmenge" id="mindestmenge" name="min_amount" 
                   value="{{ old('min_amount', app('request')->input('min_amount', '')) }}"
                   style="{{ !in_array('price', $categoryWiseFields[$selectedCategory]) || $selectedCategory == 'grafik' ? 'display:none;'  : ''}}">

            <div class="select_box" style="{{ !in_array('color', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="107" id="farbe" name="color[]" multiple="" data-placeholder="">
                    @foreach($attributes['color'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('color', app('request')->input('color', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Farbe</span>
            </div>
            <div class="select_box" style="{{ !in_array('epoche', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="161" id="epoche" name="epoche[]" multiple="" 
                        data-placeholder="" placeholder="Epoche">
                    @foreach($attributes['epoche'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('epoche', app('request')->input('epoche', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Epoche</span>
            </div>
            <div class="select_box" style="{{ !in_array('style', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="stil" name="style[]" multiple="" data-placeholder="">
                    @foreach($attributes['style'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('style', app('request')->input('style', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Stil</span>
            </div>
            <div class="select_box" style="{{ !in_array('file_format', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="file_format" name="file_format[]" multiple="" data-placeholder="">
                    @foreach($attributes['file_format'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('file_format', app('request')->input('file_format', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Format</span>
            </div>
            <div class="select_box" style="{{ !in_array('graphic_form', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="graphic_form" name="graphic_form[]" multiple="" data-placeholder="">
                    @foreach($attributes['graphic_form'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('graphic_form', app('request')->input('graphic_form', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Verfügbarkeit</span>
            </div>
            <div class="select_box" style="{{ !in_array('copy_right', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="copy_right" name="copy_right[]" multiple="" data-placeholder="">
                    @foreach($attributes['copy_right'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('copy_right', app('request')->input('copy_right', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Rechte</span>
            </div>
            <div class="select_box" style="{{ !in_array('manufacturer_id', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="manufacturer_id" name="manufacturer_id[]" multiple="" data-placeholder="">
                    @foreach($attributes['manufacture'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('manufacturer_id', app('request')->input('manufacturer_id', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Marke</span>
            </div>
            <div class="select_box" style="{{ !in_array('manufacture_country', $categoryWiseFields[$selectedCategory]) ? 'display:none;'  : ''}}">
                <select class="select2-single" data-width="160" id="manufacture_country" name="manufacture_country[]" multiple="" data-placeholder="">
                    @foreach($attributes['manufacture_country'] as $option)
                    <option value="{{$option['id']}}" 
                            @if(in_array($option['id'], old('manufacture_country', app('request')->input('manufacture_country', []))))
                        selected
                        @endif
                        >{{$option['option_display']}}</option>
                    @endforeach
                </select>
                <span class="placeholder">Herstellerland</span>
            </div>

            <input type="hidden" id="geo_location" name="geo_location" value="{{ old('geo_location', app('request')->input('geo_location', $searchGeoLocation)) }}" />
            <div class="oder-distance" style="{{ in_array('location_at', $categoryWiseFields[$selectedCategory]) || $selectedCategory == 'blank'  ? '' : 'display:none;'}}">
                <div class="ort-order">
                    <input type="text" class="form-control" placeholder="Ort oder PLZ" id="ort-order-plz" name="location" value="{{ old('location', app('request')->input('location', $searchGeoText)) }}">
                </div>
                <div class="distance">
                    <select class="select2-single-distance" data-width="70" id="ortorder" data-placeholder="" name="radius" >
                        <option value="0">+0 km</option>
                        @foreach($attributes['radius'] as $option)
                        <option value="{{$option['option_value']}}" 
                                @if(old('radius', app('request')->input('radius', $searchSearchRange)) == $option['option_value'])
                            selected
                            @endif
                            >{{$option['option_display']}}</option>
                        @endforeach
                    </select>
                    <span class="placeholder">+0 km</span>
                </div>
            </div>
            @if(Auth::check() && empty($fundusIndex))
            <div class="toggle_button show-toggle location-toggle" 
                 style="{{ in_array('save_location', $categoryWiseFields[$selectedCategory]) || $selectedCategory == 'blank' ? '' : 'display:none;'}}">
                <button type="button" class="btn btn-toggle show-toggle search-location-impression" data-toggle="button" aria-pressed="{{ isset($userImpressions['search-location']) ? 'true' : 'false' }}" autocomplete="off">
                    <div class="handle"></div>
                    <span class="infobox_location">Hier wird Dein Standort fürs nächste Mal gespeichert.</span>
                </button>
            </div>
            @endif
        </div>
    </div>
</form>