@if(!empty(Auth::user()->fundusDetail))
<div class="logged-in-menu">
    <div class="container-fluid">
        <ul>
            <li class="{{ Route::currentRouteNamed( 'fundus.inquiries.index' ) ?  'active' : '' }}"><a href="{{ route('fundus.inquiries.index') }}">{{ __('lang.my_requests') }}</a></li>

            <li class="{{ Route::currentRouteNamed( 'fundus.index' ) ?  'active' : '' }}"><a href="{{ route('fundus.index') }}">{{ __('lang.my_fundus') }}</a></li>  
        </ul>
    </div>
</div>
@endif