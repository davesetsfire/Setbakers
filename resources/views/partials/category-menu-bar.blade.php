<div class="categories-wrap position-relative">
    @if(Auth::check() && !isset($userImpressions['hintbox-category-menu']))
     <div class="hint-box-global border-bottom-left-radius user-impression-block">
        <p>{{ __('hintbox.CATEGORY_MENU_HINT') }}</p>
        <div class="hint-check">
            <div class="option">
                <label>
                    Alles klar!
                    <input type="checkbox" name="allesklar" value="yes" class="user-impression" data-impression-key="hintbox-category-menu" data-impression-value="yes">
                    <span class="checkmarks"></span>
                </label>
            </div>
        </div>
    </div>
    @endif
    <div class="menu-overlay"></div>
    <div class="catergory-list">
        <div class="close-menu">
            <!-- schließen -->
            <div id="nav-icon2" class="menu-toggler open">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
        <ul class="product-categories">
            @foreach($topLevelCategories as $key => $category)
            <li><a href="{{ Auth::check() && Route::currentRouteNamed('index') ? route('product.category', [$category->slug]) : route(Route::currentRouteName(), [$category->slug]) }}" class="{{ ($selectedCategory == $category->slug || ($key == 0 && ($selectedCategory == '' || $selectedCategory == 'default'))) ? 'active' : ''}}">{{ $category->name ?? ''}}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="category_menu_mobile">
        <div class="active_categoryname_mobile">
            @foreach($topLevelCategories as $key => $category)
            @if ($selectedCategory == $category->slug || ($key == 0 && ($selectedCategory == '' || $selectedCategory == 'default'))) 
            {{ $category->name ?? ''}}
            @endif
            @endforeach
        </div>
        <div id="nav-icon2" class="menu-toggler nav-icon2-mobile">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
</div>
