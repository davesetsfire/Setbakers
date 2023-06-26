<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use App\Models\CookieConsent;
use App\Traits\CookieConsentFunctions;
use Auth;

class CookiesController extends Controller {

    use CookieConsentFunctions;

    public function store(Request $request) {
        $request->validate([
            'action' => ['required', 'string', 'max:20', 'in:accepted,rejected,custom'],
            'analyse' => ['nullable', 'string', 'max:20', 'in:yes,no'],
            'marketing' => ['nullable', 'string', 'max:20', 'in:yes,no'],
        ]);

        $userId = Auth::user()->id ?? 0;
        $uuid = Str::uuid()->toString();

        $action = $request->input('action');
        $analyse = $request->input('analyse', 'no');
        $marketing = $request->input('marketing', 'no');

        $cookieValue = $this->getCookieString($action, $uuid, $analyse, $marketing);

        $consentDataArray = [
            'uuid' => $uuid,
            'user_action' => $action,
            'analyse' => ($analyse == 'yes') ? 1 : 0,
            'marketing' => ($marketing == 'yes') ? 1 : 0,
            'ip' => $request->ip()
        ];
        
        if ($userId > 0) {
            CookieConsent::updateOrCreate(['user_id' => $userId],$consentDataArray);
        } else {
            CookieConsent::create($consentDataArray);
        }

        Cookie::queue(Cookie::make('CONSENT', $cookieValue, 5256000, null, null, false, false));

        $response = ['status' => 'success', 'message' => 'success'];
        return response()->json($response, 200);
    }

}
