<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FundusDetail;
use App\Models\Product;
use App\Models\FundusDowngradeRequest;

class ProcessFundusDowngradeRequests extends Command {

    protected $signature = 'process:fundusDowngrade';
    protected $description = 'Process fundus downgrade requests';

    public function __construct() {
        parent::__construct();
    }

    public function handle() {
        $downgradeRequests = FundusDowngradeRequest::where('status', 'pending')
                ->where('start_date', '<=', date('Y-m-d'))
                ->get();

        foreach ($downgradeRequests as $rowItem) {

            if (($rowItem->current_package == 'pro' || $rowItem->current_package == 'infinite') && $rowItem->new_package == 'basic') {
                FundusDetail::where('id', $rowItem->fundus_id)->update([
                    'product_upload_limit' => config('app.max_articles_fundus'),
                    'subscription_start_date' => null,
                    'subscription_end_date' => null
                ]);

                $this->deactivateProducts($rowItem->fundus_id, config('app.max_articles_fundus'));

                FundusDowngradeRequest::where('id', $rowItem->id)->update(['status' => 'processed']);
            }

            if ($rowItem->current_package == 'infinite' && $rowItem->new_package == 'pro') {
                FundusDetail::where('id', $rowItem->fundus_id)->update([
                    'product_upload_limit' => config('app.max_articles_fundus_pro'),
                    //'subscription_start_date' => $rowItem->start_date,
                    //'subscription_end_date' => $rowItem->start_date
                ]);

                $this->deactivateProducts($rowItem->fundus_id, config('app.max_articles_fundus_pro'));

                FundusDowngradeRequest::where('id', $rowItem->id)->update(['status' => 'processed']);
            }

            if ($rowItem->current_package == 'basic' && $rowItem->new_package == 'pro') {

                $this->activateProducts($rowItem->fundus_id, config('app.max_articles_fundus_pro'));

                FundusDowngradeRequest::where('id', $rowItem->id)->update(['status' => 'processed']);
            }
        }
    }

    public function deactivateProducts($storeId, $maxProductCount) {
        $totalActiveProductCount = Product::where('store_id', $storeId)->active()->count();

        if ($totalActiveProductCount > $maxProductCount) {
            Product::where('store_id', $storeId)
                    ->active()
                    ->latest()
                    ->limit($totalActiveProductCount - $maxProductCount)
                    ->update(['is_active' => -1]);
        }
    }

    public function activateProducts($storeId, $maxProductCount) {
        $totalActiveProductCount = Product::where('store_id', $storeId)->active()->count();

        $totalInactiveProductCount = Product::where('store_id', $storeId)
                        ->where('is_active', -1)->count();

        if ($totalInactiveProductCount > 0 && $maxProductCount > $totalActiveProductCount) {
            Product::where('store_id', $storeId)
                    ->where('is_active', -1)
                    ->oldest()
                    ->limit($maxProductCount - $totalActiveProductCount)
                    ->update(['is_active' => 1]);
        }
    }

}
