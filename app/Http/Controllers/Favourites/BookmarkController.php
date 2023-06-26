<?php

namespace App\Http\Controllers\Favourites;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavouriteItem;
use App\Models\Product;
use Auth;
use Illuminate\Validation\ValidationException;

class BookmarkController extends Controller {

    public function bookmark(Request $request) {
        $request->validate(
                [
                    'requested_count' => 'required|integer',
                    'favourite' => 'required',
                    'bookmark_product_slug' => 'required'
                ],
                [
                    'favourite.required' => 'Bitte wähle mindestens eine Liste aus.'
        ]);

        $slug = $request->input('bookmark_product_slug');
        $product = Product::where('slug', $slug)
                ->first();
        if (!empty($product)) {
            if (Auth::user()->account_type == 'complete') {
                $subscriptionEndDate = Auth::user()->projectDetail['subscription_end_date'];
                if (!empty($subscriptionEndDate) && $subscriptionEndDate > date('Y-m-d H:i:s')) {
                    $favouriteItems = FavouriteItem::where('user_id', Auth::user()->id)
                                    ->whereIn('favourite_id', $request->input('favourite'))
                                    ->where('product_id', $product->id)->get();
                    $existingFavourites = [];
                    if (count($favouriteItems) > 0) {
                        $existingFavourites = $favouriteItems->pluck('favourite_id')->toArray();
                    }

                    if (count($favouriteItems) == count($request->input('favourite'))) {
                        throw ValidationException::withMessages([
                                    'requested_count' => [__('status_message.ARTICLE_ALREADY_BOOKMARKED')],
                        ]);
                    }
                    //if (empty($favouriteItem)) {
                    foreach ($request->input('favourite') as $favouriteItem) {
                        if (!in_array($favouriteItem, $existingFavourites)) {
                            FavouriteItem::create([
                                'user_id' => Auth::user()->id,
                                'favourite_id' => $favouriteItem, //$request->input('favourite'),
                                'store_id' => $product->store_id,
                                'product_id' => $product->id,
                                'requested_count' => $request->input('requested_count')
                            ]);
                        }
                    }
                    return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_BOOKMARKED')]);
                    //} else {
                    //    throw ValidationException::withMessages([
                    //                'requested_count' => [__('status_message.ARTICLE_ALREADY_BOOKMARKED')],
                    //    ]);
                    //}
                } else {
                    //Subscription is expired or payment has not been done
                    throw ValidationException::withMessages([
                                'requested_count' => [__('status_message.SERVICE_CHARGE_UNPAID')],
                    ]);
                }
            } else {
                // error response "You can't bookmark the article"
                throw ValidationException::withMessages([
                            'requested_count' => [__('status_message.ARTICLE_BOOKMARKED_NOT_ALLOWED')],
                ]);
            }
        } else {
            // error response "Invalid article selected, this article does not exists"
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

    public function bookmarkProduct(Request $request, $productSlug = "") {

        $product = Product::where('slug', $productSlug)
                ->first();
        if (!empty($product)) {
            if (Auth::user()->account_type == 'complete') {
                if ($request->input('bookmarked') == 'false') {
                    $favouriteItem = FavouriteItem::where('user_id', Auth::user()->id)
                                    ->where('favourite_id', 1)
                                    ->where('product_id', $product->id)->first();
                    if (empty($favouriteItem)) {
                        FavouriteItem::create([
                            'user_id' => Auth::user()->id,
                            'favourite_id' => 1,
                            'store_id' => $product->store_id,
                            'product_id' => $product->id,
                            'requested_count' => 1
                        ]);
                        return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_BOOKMARKED')]);
                        // success response "You have bookmarked this aricle"
                    } else {
                        throw ValidationException::withMessages([
                                    'requested_count' => [__('status_message.ARTICLE_ALREADY_BOOKMARKED')],
                        ]);
                    }
                } else {
                    FavouriteItem::where('user_id', Auth::user()->id)
                            ->where('favourite_id', 1)
                            ->where('product_id', $product->id)->delete();

                    return response()->json(['status' => 'success', 'message' => __('status_message.ARTICLE_REMOVED_BOOKMARKED')]);
                }
            } else {
                // error response "You can't bookmark the article"
                throw ValidationException::withMessages([
                            'requested_count' => [__('status_message.ARTICLE_BOOKMARKED_NOT_ALLOWED')],
                ]);
            }
        } else {
            // error response "Invalid article selected, this article does not exists"
            throw ValidationException::withMessages([
                        'requested_count' => [__('status_message.INVALID_ARTICLE')],
            ]);
        }
    }

}
