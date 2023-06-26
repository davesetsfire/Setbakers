<?php

return [
    'google_place_key' => $_SERVER['GOOGLE_PLACE_KEY'] ?? env('GOOGLE_PLACE_KEY', ''),
    'website_media_base_url' => $_SERVER['WEBSITE_MEDIA_BASE_URL'] ?? env('WEBSITE_MEDIA_BASE_URL', ''),
    'max_articles_complete' => env('MAX_ALLOWED_ARTICLES_FOR_COMPLETE', 100),
    'max_articles_fundus' => env('MAX_ALLOWED_ARTICLES_FOR_FUNDUS', 100),
    'max_articles_fundus_pro' => env('MAX_ALLOWED_ARTICLES_FOR_FUNDUS_PRO', 2000),
    'guest_product_view_limit' => env('GUEST_PRODUCT_VIEW_LIMIT', 20),
    'pagination_per_page_limit' => env('PAGINATION_PER_PAGE_LIMIT', 50),
    'image_thumbnail_max_width' => env('IMAGE_THUMBNAIL_MAX_WIDTH', 700),
    'image_thumbnail_max_height' => env('IMAGE_THUMBNAIL_MAX_HEIGHT', 450),
    'storage_disk' => $_SERVER['STORAGE_DISK'] ?? env('STORAGE_DISK', 's3'),
    
    'name' => $_SERVER['APP_NAME'] ?? env('APP_NAME', 'SetBakers'),
    'env' => $_SERVER['APP_ENV'] ?? env('APP_ENV', 'production'),
    'debug' => filter_var(($_SERVER['APP_DEBUG'] ?? env('APP_DEBUG', false)), FILTER_VALIDATE_BOOLEAN),
    'url' => $_SERVER['APP_URL'] ?? env('APP_URL', 'http://localhost'),
    'contactus_email_id' => 'all@setbakers.de',
    
    'tax_percentage' => 19,
    
    'asset_url' => env('ASSET_URL', null),
    'timezone' => 'UTC',
    'locale' => 'de',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => $_SERVER['APP_KEY'] ?? env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    
    'providers' => [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Srmklive\PayPal\Providers\PayPalServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        /*
         * Package Service Providers...
         */
        Barryvdh\DomPDF\ServiceProvider::class,
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\ViewServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
    ],
    /*
      |--------------------------------------------------------------------------
      | Class Aliases
      |--------------------------------------------------------------------------
      |
      | This array of class aliases will be registered when this application
      | is started. However, feel free to register as many as you wish as
      | the aliases are "lazy" loaded so they don't hinder performance.
      |
     */
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'Date' => Illuminate\Support\Facades\Date::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Http' => Illuminate\Support\Facades\Http::class,
        'Js' => Illuminate\Support\Js::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'RateLimiter' => Illuminate\Support\Facades\RateLimiter::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        // 'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'PDF' => Barryvdh\DomPDF\Facade::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
    ],
];
