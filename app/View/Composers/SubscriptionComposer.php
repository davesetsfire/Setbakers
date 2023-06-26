<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\SubscriptionPlan;

class SubscriptionComposer {

    use \App\Traits\Subscriptions\SubscriptionFunctions;
    
    public function __construct() {
        
    }

    public function compose(View $view) {
        $subscriptionPlans = SubscriptionPlan::active()->current()
                ->get();

        $subsPlans = [];
        $taxPercentage = config('app.tax_percentage');

        foreach ($subscriptionPlans as $plan) {

            $plan->basic_amount = number_format($plan->basic_amount, 2, ',', '.');
            $plan->tax_amount = number_format($plan->tax, 2, ',', '.');
            $plan->total_amount = number_format($plan->amount, 2, ',', '.');
            $plan->display_amount = str_replace(',00', '', $plan->total_amount);

            $subsPlans[$plan->account_type][$plan->type][$plan->duration . $plan->duration_in] = $plan;
        }

        $projectSubsPlans = $subsPlans['project'];
        $fundusSubsPlans = $subsPlans['fundus'];

        $showFreeTrial = $this->getFreeTrialStatus();
        
        $view->with('projectSubsPlans', $projectSubsPlans)
                ->with('fundusSubsPlans', $fundusSubsPlans)
                ->with('subsTaxPercentage', $taxPercentage)
                ->with('showFreeTrial', $showFreeTrial);
    }

}
