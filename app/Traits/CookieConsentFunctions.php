<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cookie;

trait CookieConsentFunctions {

    public function getCookieString($action, $uuid, &$analyse, &$marketing) {
        if ($action == 'accepted') {
            $analyse = 'yes';
            $marketing = 'yes';
        } else if ($action == 'rejected') {
            $analyse = 'no';
            $marketing = 'no';
        }

        return $this->createConsentCookie($action, $uuid, $analyse, $marketing);
    }

    public function getCookieConsent() {
        $cookieConsent = false;
        $cookieAnalyse = false;
        $cookieMarketing = false;

        $cookieConsentContent = Cookie::get('CONSENT');
        if (!empty($cookieConsentContent)) {
            $cookieConsent = true;
            list($action, $uuid, $cookieAnalyse, $cookieMarketing) = $this->splitConsentCookie($cookieConsentContent);
        }

        return [$cookieConsent, $cookieAnalyse, $cookieMarketing];
    }

    public function createConsentCookie($action, $uuid, $analyse, $marketing) {
        return 'action=' . $action
                . '+uuid=' . $uuid
                . '+date=' . date('d-m-Y His')
                . '+analyse=' . $analyse
                . '+marketing=' . $marketing;
    }

    public function splitConsentCookie($cookieConsentContent) {
        $cookieContentArray = [];

        $contentArray = explode('+', $cookieConsentContent);
        if (count($contentArray) > 1) {
            foreach ($contentArray as $contentItem) {
                list($key, $value) = explode('=', $contentItem);
                $cookieContentArray[$key] = $value;
            }
        }

        $action = $cookieContentArray['action'] ?? '';
        $uuid = $cookieContentArray['uuid'] ?? '';
        $cookieAnalyse = $this->getConsentBoolean($cookieContentArray['analyse'] ?? '');
        $cookieMarketing = $this->getConsentBoolean($cookieContentArray['marketing'] ?? '');

        return [$action, $uuid, $cookieAnalyse, $cookieMarketing];
    }

    public function getConsentBoolean($cookieValue) {
        return (!empty($cookieValue) && $cookieValue == 'yes') ? true : false;
    }

}
