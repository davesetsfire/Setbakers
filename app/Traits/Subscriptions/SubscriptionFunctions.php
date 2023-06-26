<?php

namespace App\Traits\Subscriptions;

use App\Models\FreeTrialUser;

trait SubscriptionFunctions {

    public function getFreeTrialStatus() {
        $showFreeTrial = false;
        if (\Auth::check()) {
            $userHash = hash('sha256', \Auth::user()->email);
            $freeTrial = FreeTrialUser::where('user_hash', $userHash)->first();
            if (empty($freeTrial)) {
                $showFreeTrial = true;
            }
        }
        return $showFreeTrial;
    }

    public function updateFreeTrialStatus() {
        if (\Auth::check()) {
            $userHash = hash('sha256', \Auth::user()->email);
            $freeTrial = FreeTrialUser::updateOrCreate(
                            ['user_hash' => $userHash],
                            ['user_id' => \Auth::user()->id]
            );
        }
        return true;
    }

}
