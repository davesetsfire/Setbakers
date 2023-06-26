<?php

use Illuminate\Support\Facades\Route;

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

//Route::get('/auth', [App\Http\Controllers\HomeController::class, 'login'])->name('auth.login');
Route::match(['get', 'post'], '/payment/paypal/notify', [App\Http\Controllers\Payments\PaymentController::class, 'paypalNotify'])->name('paypal.notify');

Route::get('/category/{category?}', [App\Http\Controllers\Products\ProductController::class, 'categoryPage'])->name('product.category')->middleware('email.verified');

Route::group(['middleware' => ['auth', 'email.verified']], function () { //verified
    Route::post('/payment/bank', [App\Http\Controllers\Payments\PaymentController::class, 'bankPayment'])->name('payment.bank');
    Route::post('/payment/paypal', [App\Http\Controllers\Payments\PaymentController::class, 'paypalPayment'])->name('payment.paypal');

    Route::post('/fundus/payment/paypal', [App\Http\Controllers\Payments\PaymentController::class, 'fundusPaypalPayment'])->name('fundus.payment.paypal');
    Route::post('/fundus/payment/bank', [App\Http\Controllers\Payments\PaymentController::class, 'fundusBankPayment'])->name('fundus.payment.bank');

    Route::get('/payment/paypal/order/response', [App\Http\Controllers\Payments\PaymentController::class, 'paypalOrderResponse'])->name('paypal.order.response');
    Route::get('/payment/paypal/order/cancel', [App\Http\Controllers\Payments\PaymentController::class, 'paypalOrderCancel'])->name('paypal.order.cancel');

    Route::get('/payment/paypal/subscription/response', [App\Http\Controllers\Payments\PaymentController::class, 'paypalSubscriptionResponse'])->name('paypal.subscription.response');
    Route::get('/payment/paypal/subscription/cancel', [App\Http\Controllers\Payments\PaymentController::class, 'paypalSubscriptionCancel'])->name('paypal.subscription.cancel');

    Route::post('/changePassword', [App\Http\Controllers\Auth\ChangePasswordController::class, 'changePassword'])->name('changePassword');

    Route::post('/data/fundus/pause', [App\Http\Controllers\Users\ProfileController::class, 'pauseFundus'])->name('fundus.pause');
    Route::post('/data/fundus/unpause', [App\Http\Controllers\Users\ProfileController::class, 'unpauseFundus'])->name('fundus.unpause');

    Route::post('/data/project/pause', [App\Http\Controllers\Users\ProfileController::class, 'pauseProject'])->name('project.pause');
    Route::get('/data/project/unpause', [App\Http\Controllers\Users\ProfileController::class, 'unpauseProject'])->name('project.unpause');

    Route::get('/fundus/inquiries', [App\Http\Controllers\Fundus\InquiryController::class, 'index'])->name('fundus.inquiries.index');
    Route::post('/fundus/inquiries/product/create', [App\Http\Controllers\Fundus\InquiryController::class, 'createProduct'])->name('fundus.inquiries.product.create');
    Route::post('/fundus/inquiries/download', [App\Http\Controllers\Fundus\InquiryController::class, 'downloadInquiry'])->name('fundus.inquiries.download');
    Route::post('/fundus/inquiries/download/gallery', [App\Http\Controllers\Fundus\InquiryController::class, 'downloadInquiryGallery'])->name('fundus.inquiries.download.gallery');
    Route::post('/fundus/inquiries/update', [App\Http\Controllers\Fundus\InquiryController::class, 'update'])->name('fundus.inquiries.update');
    Route::post('/fundus/inquiries/updateCount/{id?}', [App\Http\Controllers\Fundus\InquiryController::class, 'updateProductCount'])->name('fundus.inquiries.updateCount');
    Route::get('/fundus/inquiries/deleteItem/{id?}', [App\Http\Controllers\Fundus\InquiryController::class, 'deleteOrderProduct'])->name('fundus.inquiries.deleteOrderProduct');
    Route::post('/fundus/inquiries/addProduct', [App\Http\Controllers\Fundus\InquiryController::class, 'addProduct'])->name('fundus.inquiries.addProduct');
    Route::post('/fundus/inquiries/delete', [App\Http\Controllers\Fundus\InquiryController::class, 'destroy'])->name('fundus.inquiries.delete');

    Route::post('/fundus/plans/infinite', [App\Http\Controllers\Fundus\ChangePlanController::class, 'upgradeToInfinite'])->name('fundus.plans.infinite');
    Route::post('/fundus/plans/downgrade', [App\Http\Controllers\Fundus\ChangePlanController::class, 'downgradePackage'])->name('fundus.plans.downgrade');

    Route::post('/fundus/product/bulk/create', [App\Http\Controllers\Fundus\ProductController::class, 'bulkCreate'])->name('fundus.product.bulk.create');
    Route::get('/fundus/product/bulk/download/{action}', [App\Http\Controllers\Fundus\ProductController::class, 'downloadDocument'])->name('fundus.product.bulk.download');

    Route::resource('data', App\Http\Controllers\Users\ProfileController::class)->except(['index']);
    Route::resource('fundus', App\Http\Controllers\Fundus\ProductController::class);
    Route::post('/updateProductCount/{productSlug?}', [App\Http\Controllers\Fundus\ProductController::class, 'updateProductCount'])->name('updateProductCount');

    Route::get('/favourites/fundus', [App\Http\Controllers\Favourites\FundusController::class, 'index'])->name('favourites.fundus');
    Route::post('/favourites/storeOrder', [App\Http\Controllers\Favourites\FundusController::class, 'generateStoreOrder'])->name('favourites.store.order');
    Route::post('/favourites/storeMessage', [App\Http\Controllers\Favourites\FundusController::class, 'storeMessage'])->name('favourites.store.message');
    Route::post('/favourites/download/{storeId?}', [App\Http\Controllers\Favourites\FundusController::class, 'downloadFavourite'])->name('favourites.download');
    Route::post('/favourites/fundus/updateCount/{id?}', [App\Http\Controllers\Favourites\FundusController::class, 'updateProductCount'])->name('favourites.updateCount');
    Route::post('/favourites/fundus/changeFavourite', [App\Http\Controllers\Favourites\FundusController::class, 'changeFavourite'])->name('favourites.changeFavourite');
    Route::post('/favourites/fundus/changeRentDate', [App\Http\Controllers\Favourites\FundusController::class, 'changeRentDate'])->name('favourites.changeRentDate');

    Route::get('/favourites/theme/{id}/remove', [App\Http\Controllers\Favourites\ThemeController::class, 'destroy'])->name('favourites.remove');
    Route::get('/favourites/theme', [App\Http\Controllers\Favourites\ThemeController::class, 'index'])->name('favourites.theme');
    Route::post('/favourites/theme', [App\Http\Controllers\Favourites\ThemeController::class, 'store'])->name('favourites.store');
    Route::put('/favourites/theme/{id}', [App\Http\Controllers\Favourites\ThemeController::class, 'update'])->name('favourites.update');

    Route::post('/bookmark', [App\Http\Controllers\Favourites\BookmarkController::class, 'bookmark'])->name('bookmark');
    Route::post('/bookmarkProduct/{productSlug?}', [App\Http\Controllers\Favourites\BookmarkController::class, 'bookmarkProduct'])->name('bookmarkProduct');

    Route::get('/store/{category?}', [App\Http\Controllers\Products\ProductController::class, 'categoryPage'])->name('store.index');
    
    Route::post('/impressions', [App\Http\Controllers\Users\ImpressionController::class, 'store'])->name('impressions.store');
    Route::delete('/impressions/{keyName}', [App\Http\Controllers\Users\ImpressionController::class, 'destroy'])->name('impressions.destroy');
});

//Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
//Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
//Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');
//
//Route::match(['get', 'post'], '/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.notice');
//Route::match(['get', 'post'], '/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.verify');

Auth::routes(['verify' => true]);

Route::get('/imprint', function () {
    return view('pages.imprint');
})->name('imprint');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

Route::get('/contact', [App\Http\Controllers\ContactUsController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\ContactUsController::class, 'store'])->name('contact.store');
Route::post('/cookies', [App\Http\Controllers\CookiesController::class, 'store'])->name('cookies.store');

Route::get('/feedback', function () {
    return view('pages.feedback');
})->name('feedback');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/cancellation', function () {
    return view('pages.cancellation');
})->name('cancellation');

Route::get('/sevdesk', function () {
//    echo '<pre>';
//    $subscriptionDate = "2022-02-29";
//    echo "\n" . $subscriptionDate;
//    for ($i = 1; $i < 72; $i++) {
//        $subscriptionDate = date('Y-m-d', strtotime($subscriptionDate . ' +1 month'));
//        echo "\n" . $subscriptionDate;
//    }
});

Route::get('/{category?}', [App\Http\Controllers\HomeController::class, 'index'])->name('index')->middleware('email.verified');

Route::fallback(function(){
    return view('errors.404');
});