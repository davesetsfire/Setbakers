<?php

namespace App\Http\Controllers\Fundus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FundusInfiniteNotification;
use App\Models\FundusDetail;
use App\Models\FundusDowngradeRequest;
use Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Traits\Notifications\EmailNotifications;

class ChangePlanController extends Controller {

    use EmailNotifications;

    protected $provider;

    public function __construct() {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function upgradeToInfinite(Request $request) {

        $emailData['email'] = Auth::user()->email;
        $emailData['fundus_package'] = 'Funduskonto Infinite';
        $emailData['article_count'] = $request->input('infinite_required_article_count');
        $emailData['subscription_type'] = $request->input('infinite_subscription_type') == 'monthly' ? 'Monatliche Zahlung' : 'Jährliche Zahlung';
        $emailData['payment_method'] = $request->input('infinite_payment_method') == 'bank_account' ? 'Banküberweisung' : 'Paypal';

        Notification::route('mail', config('app.contactus_email_id'))->notify(new FundusInfiniteNotification(Auth::user()->name, $emailData));
//Notification::send(Auth::user(), new FundusInfiniteNotification(Auth::user()->name, $emailData));

        $response = ['status' => 'success', 'message' => 'Request accepted'];

        return response()->json($response, 200);
    }

    public function downgradePackage(Request $request) {
        $fundusDetail = FundusDetail::where('user_id', Auth::user()->id)->first();

        $currentPackage = $fundusDetail->package_type ?? '';
        $newPackage = $request->input('new_fundus_package');

        if (empty($currentPackage) || empty($newPackage)) {
            //return error message
            return redirect()->route('data.show', [0])
                            ->with('fundus_error', __('status_message.UNABLE_TO_PROCCESS_REQUEST'));
        }
        $subscriptionStartDate = date('Y-m-d', strtotime($fundusDetail->subscription_end_date . ' +1 day'));
        $status = 'pending';

        if (($currentPackage == 'pro' || $currentPackage == 'infinite') && $newPackage == 'basic') {
            if (strtotime($fundusDetail->subscription_end_date) < date('Y-m-d H:i:s')) {
                $fundusDetail->product_upload_limit = config('app.max_articles_fundus');
                $fundusDetail->subscription_start_date = null;
                $fundusDetail->subscription_end_date = null;
                $subscriptionStartDate = date('Y-m-d');
                $status = 'processed';
            }

            if (!empty($fundusDetail->paypal_subscription_id)) {
                $subsResponse = $this->provider->cancelSubscription($fundusDetail->paypal_subscription_id, 'Downgrading Package');
                if (empty($subsResponse)) {
                    $fundusDetail->paypal_subscription_id = '';
                } else {
                    logger(print_r($subsResponse, true));
                }
            }

            $fundusDetail->package_type = 'basic';
            $fundusDetail->save();

            $this->addDowngradeRequest($fundusDetail->id, $currentPackage, $newPackage, $subscriptionStartDate, $status);

            //Email notification to admin for downgrade from infinite to basic
            if ($currentPackage == 'infinite' && $newPackage == 'basic') {
                $emailData['email'] = Auth::user()->email;
                $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $emailData['package_name'] = strtoupper($newPackage);
                $this->sendInfinitePackageDowngradeEmail($emailData);
            }
            //Cancel paypal subscription if any exists
        } else if ($currentPackage == 'infinite' && $newPackage == 'pro') {
            //subscription payment flow should play
            if (strtotime($fundusDetail->subscription_end_date) < date('Y-m-d H:i:s')) {
                $fundusDetail->product_upload_limit = config('app.max_articles_fundus_pro');
                $subscriptionStartDate = date('Y-m-d');
                $status = 'processed';
            }
            $fundusDetail->package_type = 'pro';
            $fundusDetail->save();

            $this->addDowngradeRequest($fundusDetail->id, $currentPackage, $newPackage, $subscriptionStartDate, $status);

            //Email notification to admin for downgrade from infinite to pro
            if ($currentPackage == 'infinite' && $newPackage == 'pro') {
                $emailData['email'] = Auth::user()->email;
                $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $emailData['package_name'] = strtoupper($newPackage);
                $this->sendInfinitePackageDowngradeEmail($emailData);
            }
        }

        return redirect()->route('data.show', [0])
                        ->with('fundus_success', __('status_message.FUNDUS_PACKAGE_DOWNGRADED'));
    }

    public function addDowngradeRequest($fundusId, $currentPackage, $newPackage, $subscriptionStartDate, $status) {
        FundusDowngradeRequest::create([
            'fundus_id' => $fundusId,
            'current_package' => $currentPackage,
            'new_package' => $newPackage,
            'start_date' => $subscriptionStartDate,
            'status' => $status
        ]);
    }

}
