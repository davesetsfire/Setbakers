<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\FundusDetail;
use App\Models\Product;

class CheckProductAddLimit {

    public function handle(Request $request, Closure $next) {

        $userId = \Auth::user()->id;

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        $productCounts = Product::where('store_id', $fundusDetail->id)->where('is_active', 1)->count();
        $maxAllowedCount = $fundusDetail->product_upload_limit ?? config('app.max_articles_fundus');
        if ($productCounts >= $maxAllowedCount) {
            return redirect()->route('fundus.index')
                            ->with('error_message', __('status_message.ARTICLE_UPLOAD_MAX_LIMIT_REACHED'));
        }

        return $next($request);
    }

}
