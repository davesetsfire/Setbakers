<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Traits\CookieConsentFunctions;

class CookieComposer {

    use CookieConsentFunctions;

    public function compose(View $view) {

        list($cookieConsent, $cookieAnalyse, $cookieMarketing) = $this->getCookieConsent();

        $view->with('cookieAnalyse', $cookieAnalyse)
                ->with('cookieMarketing', $cookieMarketing)
                ->with('cookieConsent', $cookieConsent);
    }

}
