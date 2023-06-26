<div class="logged-in-menu">
    <div class="container-fluid">
        <ul>
            <li class="{{ Route::currentRouteNamed( 'data.show' ) ?  'active' : '' }}"><a href="{{ route('data.show', [0]) }}">{{ __('lang.my_data') }}</a></li>
            <li>
                <a href="#" class="logout-button">{{ __('lang.logout') }}</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>