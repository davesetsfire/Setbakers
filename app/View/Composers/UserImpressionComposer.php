<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\UserImpression;
use Auth;

class UserImpressionComposer {

    public function __construct() {
        
    }

    public function compose(View $view) {
        $userImpressions = [];

        if (Auth::check()) {
            $userImpressionObject = UserImpression::where('user_id', Auth::user()->id)
                    ->get();
            foreach ($userImpressionObject as $userImpression) {
                $userImpressions[$userImpression->key_name] = $userImpression->key_value;
            }
        }

        $view->with('userImpressions', $userImpressions);
    }

}
