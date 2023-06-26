<div class="logged-in-menu">
    <div class="container-fluid">
        <ul>
            <li class="{{ Route::currentRouteNamed( 'favourites.theme' ) ?  'active' : '' }}"><a href="{{ route('favourites.theme') }}">{{ __('lang.favourite_by_theme') }}</a></li>
            <li class="{{ Route::currentRouteNamed( 'favourites.fundus' ) ?  'active' : '' }}"><a href="{{ route('favourites.fundus') }}">{{ __('lang.favourite_by_fundus') }}</a></li>
        </ul>
    </div>
</div>      