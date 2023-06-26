<?php

namespace App\Traits\Paypal;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\User;

trait PaypalFunctions {

    public function getPaypalProvider() {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        return $provider;
    }

    public function cancelAllSubscriptions($userId) {
        $userDetail = User::where('id', $userId)->with('projectDetail', 'fundusDetail')->first();
        $paypalProvider = $this->getPaypalProvider();

        if (!empty($userDetail->fundusDetail['paypal_subscription_id'])) {
            $subsResponse = $paypalProvider->cancelSubscription($userDetail->fundusDetail['paypal_subscription_id'], 'Account closure');
            if (!empty($subsResponse)) {
                logger(print_r($subsResponse, true));
            }
        }

        if (!empty($userDetail->projectDetail['paypal_subscription_id'])) {
            $subsResponse = $paypalProvider->cancelSubscription($userDetail->projectDetail['paypal_subscription_id'], 'Account closure');
            if (!empty($subsResponse)) {
                logger(print_r($subsResponse, true));
            }
        }

        return true;
    }

    public function suspandPaypalSubscription($subscriptionId, $suspensionReason) {
        $paypalProvider = $this->getPaypalProvider();

        $subsResponse = $paypalProvider->suspendSubscription($subscriptionId, $suspensionReason);
        if (empty($subsResponse)) {
            return true;
        } else {
            logger(print_r($subsResponse, true));
            return $this->checkInvalidSubsIdResponse($subsResponse);
        }
    }

    public function activatePaypalSubscription($subscriptionId, $activateReason) {
        $paypalProvider = $this->getPaypalProvider();

        $subsResponse = $paypalProvider->activateSubscription($subscriptionId, $activateReason);
        if (empty($subsResponse)) {
            return true;
        } else {
            logger(print_r($subsResponse, true));
            return $this->checkInvalidSubsIdResponse($subsResponse);
        }
    }

    public function cancelPaypalSubscription($subscriptionId, $cancelReason) {
        $paypalProvider = $this->getPaypalProvider();

        $subsResponse = $paypalProvider->cancelSubscription($subscriptionId, $cancelReason);
        if (empty($subsResponse)) {
            return true;
        } else {
            logger(print_r($subsResponse, true));
            return $this->checkInvalidSubsIdResponse($subsResponse);
        }
    }

    public function checkInvalidSubsIdResponse($subsResponse) {
        $subsResponse = json_decode($subsResponse, true);
        $name = $subsResponse['name'] ?? '';
        $issue = $subsResponse['details'][0]['issue'] ?? '';
        if ($name == 'UNPROCESSABLE_ENTITY' && $issue == 'SUBSCRIPTION_STATUS_INVALID') {
            return true;
        } else {
            return false;
        }
    }

}
