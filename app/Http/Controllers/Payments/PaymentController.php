<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Models\SubscriptionPlan;
use App\Models\OrderDetail;
use App\Models\PaymentHistory;
use App\Models\ProjectDetail;
use App\Models\FundusDetail;
use App\Models\SubscriptionHistory;
use Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;
use Session;
use App\Models\User;
use App\Models\FundusDowngradeRequest;
use App\Notifications\PaymentNotification;
use App\Notifications\FreeTrialPaymentNotification;
use App\Notifications\ProjectPaymentRenewalNotification;
use App\Notifications\FundusProBankTransferNotification;
use App\Notifications\FundusProPaypalNotification;
use App\Notifications\FundusProPaypalRenewalNotification;
use App\Notifications\FundusProAdvancePaypalNotification;
use App\Notifications\FundusProAdvanceBankTransferNotification;
use App\Traits\Notifications\EmailNotifications;
use App\Traits\SevDesk\SevDeskFunctions;
use App\Traits\Subscriptions\SubscriptionFunctions;

class PaymentController extends Controller {

    use SevDeskFunctions,
        SubscriptionFunctions,
        EmailNotifications;

    protected $provider;

    const PAYPAL_SUBSCRIPTION = 'paypal_subscription';
    const PAYPAL_ORDER = 'paypal_order';

    public function __construct() {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function fundusPaypalPayment(Request $request) {
//        $userId = \Auth::user()->id;
//        $invoiceObject = $this->createInvoice($userId, 109, 5);
//        list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);
//        Notification::send(Auth::user(), new FundusProPaypalNotification(Auth::user()->name, [], $fileContent, $fileName));
//        exit();

        $subscriptionType = $request->input('fundus_subscription_type');
        $paymentMethod = $request->input('fundus_payment_method');

        $amount = 0;
        $currency = 'EUR';
        $subsId = 0;
        $subscription = null;
        $paypalPlanId = '';
        $redirectUrl = '';
        if ($subscriptionType == 'monthly') {
            $subscription = SubscriptionPlan::active()->current()->fundus()
                    ->where('type', 'recurring')
                    ->where('duration', 1)
                    ->where('duration_in', 'month')
                    ->first(['id', 'amount', 'currency', 'paypal_plan_id']);
        } else if ($subscriptionType == 'yearly') {
            $subscription = SubscriptionPlan::active()->current()->fundus()
                    ->where('type', 'recurring')
                    ->where('duration', 1)
                    ->where('duration_in', 'year')
                    ->first(['id', 'amount', 'currency', 'paypal_plan_id']);
        }

        if (!empty($subscription)) {
            $subsId = $subscription->id;
            $amount = $subscription->amount;
            $currency = $subscription->currency;
            $paypalPlanId = $subscription->paypal_plan_id;
        } else {
            //error response and return
            return redirect()->back();
        }

        if ($subscriptionType == 'monthly' || ($subscriptionType == 'yearly' && $paymentMethod == 'paypal')) {

            $redirectUrl = $this->processPaypalSubscriptionRequest($subsId, $amount, $currency, $paypalPlanId);
        } else {
            //error response and return
            return redirect()->back();
        }

        return $redirectUrl;
    }

    public function fundusBankPayment(Request $request) {
        //Bank processing
        $userId = \Auth::user()->id;
        $subscriptionType = $request->input('fundus_subscription_type');
        $paymentMethod = $request->input('fundus_payment_method');
        $response = [];

        $amount = 0;
        $currency = 'EUR';
        $subsId = 0;
        $subscription = null;
        $redirectUrl = '';
        if ($subscriptionType == 'monthly') {
            $subscription = SubscriptionPlan::active()->current()->fundus()
                    ->where('type', 'recurring')
                    ->where('duration', 1)
                    ->where('duration_in', 'month')
                    ->first(['id', 'amount', 'currency', 'paypal_plan_id']);
        } else if ($subscriptionType == 'yearly') {
            $subscription = SubscriptionPlan::active()->current()->fundus()
                    ->where('type', 'recurring')
                    ->where('duration', 1)
                    ->where('duration_in', 'year')
                    ->first(['id', 'amount', 'currency', 'paypal_plan_id']);
        }

        if (!empty($subscription)) {
            $subsId = $subscription->id;
            $amount = $subscription->amount;
            $currency = $subscription->currency;
        } else {
            //error response and return
            $response = [
                'status' => 'error',
                'message' => 'wrong request'
            ];
        }

        if ($subscriptionType == 'yearly' && $paymentMethod == 'bank_account') {

            $orderDetails = $this->makeBankOrderEntry($subsId, $amount, $currency);
            $emailData['order_number'] = $orderDetails->order_number;
            $emailData['amount'] = $amount;

            $userId = \Auth::user()->id;
            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + 1 year"));

            $fundusDetail = FundusDetail::where('user_id', $userId)->first();
            $currentPackage = $fundusDetail->package_type ?? '';
            $newPackage = 'pro';

            if (!empty($fundusDetail->subscription_end_date) && $fundusDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                $subscriptionEndDate = date('d.m.Y', strtotime($fundusDetail->subscription_end_date));
                $newSubsStartDate = date('Y-m-d', strtotime($fundusDetail->subscription_end_date . ' +1 day'));

                FundusDetail::where('user_id', $userId)->update([
                    //'is_subscription_paused' => 0,
                    'paypal_subscription_id' => '',
                    'subscription_id' => $orderDetails->subscription_id,
                    //'subscription_start_date' => $startDate,
                    //'subscription_end_date' => $endDate,
                    //'product_upload_limit' => config('app.max_articles_fundus_pro'),
                    'package_type' => 'pro'
                ]);

                FundusDowngradeRequest::create([
                    'fundus_id' => $fundusDetail->id,
                    'current_package' => $currentPackage,
                    'new_package' => $newPackage,
                    'start_date' => $newSubsStartDate,
                    'status' => 'pending'
                ]);

                $emailData['subscription_end_date'] = $subscriptionEndDate;
                Notification::send(Auth::user(), new FundusProAdvanceBankTransferNotification(Auth::user()->name, $emailData));

                //Email notification to admin for downgrade from infinite to pro
                if ($currentPackage == 'infinite' && $newPackage == 'pro') {
                    $emailData = [];
                    $emailData['email'] = Auth::user()->email;
                    $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                    $emailData['package_name'] = strtoupper($newPackage);
                    $this->sendInfinitePackageDowngradeEmail($emailData);
                }

                Session::flash('showInformationModal', 'true');
                Session::flash('modalHeading', __('status_message.FUNDUS_ADVANCED_BANKTRANSFER_SUCCESS_HEADING'));
                Session::flash('modalMessage', __('status_message.FUNDUS_ADVANCED_BANKTRANSFER_SUCCESS_MESSAGE'));

                $response = [
                    'status' => 'success',
                    'message' => 'Request accepted',
                    'data' => ['banktransfer' => '1']
                ];

                return response()->json($response, 200);
            }


            FundusDetail::where('user_id', $userId)->update([
                'subscription_id' => $orderDetails->subscription_id,
                'subscription_start_date' => $startDate,
                'subscription_end_date' => $endDate,
                'product_upload_limit' => config('app.max_articles_fundus_pro'),
                'package_type' => 'pro'
            ]);

            if ($fundusDetail->package_type == 'basic') {
                FundusDowngradeRequest::create([
                    'fundus_id' => $fundusDetail->id,
                    'current_package' => $fundusDetail->package_type,
                    'new_package' => 'pro',
                    'start_date' => date('Y-m-d'),
                    'status' => 'pending'
                ]);
            }

            SubscriptionHistory::create([
                'user_id' => $userId,
                'subscription_id' => $orderDetails->subscription_id,
                'amount' => $orderDetails->amount,
                'currency' => $orderDetails->currency,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $invoiceDuration = [$startDate, $endDate];
            $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
            list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);
            Notification::send(Auth::user(), new FundusProBankTransferNotification(Auth::user()->name, $emailData, $fileContent, $fileName));

            //Email notification to admin for downgrade from infinite to pro
            if ($currentPackage == 'infinite' && $newPackage == 'pro') {
                $emailData = [];
                $emailData['email'] = Auth::user()->email;
                $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                $emailData['package_name'] = strtoupper($newPackage);
                $this->sendInfinitePackageDowngradeEmail($emailData);
            }

            $response = [
                'status' => 'success',
                'message' => 'Request accepted',
                'data' => ['order_number' => $orderDetails->order_number, 'amount' => $amount, 'package_name' => 'pro']
            ];
        } else {
            //error response and return
            $response = [
                'status' => 'error',
                'message' => 'wrong request'
            ];
        }

        return response()->json($response, 200);
    }

    public function bankPayment(Request $request) {
        //Bank processing
        $userId = \Auth::user()->id;
        $subscriptionType = $request->input('subscription_type');
        $subscriptionId = $request->input('duration');
        $paymentMethod = $request->input('payment_method');
        $response = [];

        $amount = 0;
        $currency = 'EUR';
        $subsId = 0;
        $subscription = null;
        $redirectUrl = '';
        if ($subscriptionType == 'onetime') {
            $subscription = SubscriptionPlan::active()->current()->project()
                            ->where('id', $subscriptionId)
                            ->where('type', 'onetime')->first(['id', 'amount', 'currency', 'account_type', 'duration', 'duration_in']);
        }

        if (!empty($subscription)) {
            $subsId = $subscription->id;
            $amount = $subscription->amount;
            $currency = $subscription->currency;
        } else {
            //error response and return
            $response = [
                'status' => 'error',
                'message' => 'wrong request'
            ];
        }

        if ($subscriptionType == 'onetime' && $paymentMethod == 'bank_account') {

            $orderDetails = $this->makeBankOrderEntry($subsId, $amount, $currency);
            $emailData['order_number'] = $orderDetails->order_number;
            $emailData['amount'] = $amount;

            $accountType = $subscription->account_type ?? '';
            $duration = $subscription->duration ?? 0;
            $durationUnit = $subscription->duration_in ?? 0;

            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 00:00:00');

            $projectDetail = ProjectDetail::where('user_id', $userId)->first();

            if (!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                $startDate = date('Y-m-d 00:00:00', strtotime($projectDetail->subscription_end_date . ' +1 day'));
                $endDate = date('Y-m-d 00:00:00', strtotime($projectDetail->subscription_end_date . ' +1 day'));
            }

            if ($durationUnit == 'day') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} day"));
            } else if ($durationUnit == 'month') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} month"));
            } else if ($durationUnit == 'year') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} year"));
            }

            ProjectDetail::where('user_id', $userId)->update([
                'subscription_id' => $orderDetails->subscription_id,
                'subscription_start_date' => $startDate,
                'subscription_end_date' => $endDate
            ]);

            $this->updateFreeTrialStatus();
            SubscriptionHistory::create([
                'user_id' => $userId,
                'subscription_id' => $orderDetails->subscription_id,
                'amount' => $orderDetails->amount,
                'currency' => $orderDetails->currency,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $invoiceDuration = [$startDate, $endDate];
            $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
            list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

            $emailData['invoice_number'] = $invoiceObject->sevdesk_invoice_id;
            Notification::send(Auth::user(), new PaymentNotification(Auth::user()->name, $paymentMethod, $emailData, $fileContent, $fileName));

            $response = [
                'status' => 'success',
                'message' => 'Request accepted',
                'data' => ['order_number' => $orderDetails->order_number, 'amount' => $amount]
            ];
        } else {
            //error response and return
            $response = [
                'status' => 'error',
                'message' => 'wrong request'
            ];
        }
        return response()->json($response, 200);
    }

    public function paypalPayment(Request $request) {
        $subscriptionType = $request->input('subscription_type');
        $subscriptionId = $request->input('duration');
        $paymentMethod = $request->input('payment_method');
        $userId = \Auth::user()->id;

        $amount = 0;
        $currency = 'EUR';
        $subsId = 0;
        $subscription = null;
        $paypalPlanId = '';
        $accountType = '';
        $redirectUrl = '';
        if ($subscriptionType == 'onetime') {
            $subscription = SubscriptionPlan::active()->current()->project()
                            ->where('id', $subscriptionId)
                            ->where('type', 'onetime')->first(['id', 'amount', 'currency', 'paypal_plan_id', 'paypal_trial_plan_id', 'account_type']);
        } else {
            $subscription = SubscriptionPlan::active()->current()->project()
                    ->where('type', 'recurring')
                    ->where('duration', 1)
                    ->where('duration_in', 'month')
                    ->first(['id', 'amount', 'currency', 'paypal_plan_id', 'paypal_trial_plan_id', 'account_type']);
        }

        if (!empty($subscription)) {
            $allowFreeTrial = $this->getFreeTrialStatus();
            $subsId = $subscription->id;
            $amount = $subscription->amount;
            $currency = $subscription->currency;
            $paypalPlanId = $allowFreeTrial ? $subscription->paypal_trial_plan_id : $subscription->paypal_plan_id;
            $accountType = $subscription->account_type;
        } else {
            //error response and return
            return redirect()->back();
        }

        if ($subscriptionType == 'onetime' && $paymentMethod == 'paypal') {
            // Order processing
            $orderRequest = $this->createOrderRequest($amount, $currency);
            $orderResponse = $this->provider->createOrder($orderRequest);

            logger(print_r($orderResponse, true));

            $paypalOrderNumber = $orderResponse['id'] ?? '';
            $orderDetail = $this->makePaypalOrderEntry($subsId, $amount, $currency, $paypalOrderNumber, self::PAYPAL_ORDER);

            $orderId = $orderDetail->id;
            $orderResponseStr = print_r($orderResponse, true);
            $paymentHistory = $this->makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $orderResponseStr);

            $redirectUrl = $orderResponse['links'][1]['href'];
            Session::flash('paymentBackUrl', url()->previous());
        } else if ($subscriptionType == 'recurring') {
            //Subscription processing
            $subscriptionDate = date('Y-m-d H:i:s', strtotime('+1 minutes'));
            $returnUrl = route('paypal.subscription.response');
            $cancelUrl = route('paypal.subscription.cancel');

            if ($accountType == 'project') {
                $projectDetail = ProjectDetail::where('user_id', $userId)->first();
                if (!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                    $subscriptionDate = date('Y-m-d 00:00:00', strtotime($projectDetail->subscription_end_date . ' +1 day'));
                }
            } else if ($accountType == 'fundus') {
                $fundusDetail = FundusDetail::where('user_id', $userId)->first();
                if (!empty($fundusDetail->subscription_end_date) && $fundusDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                    $subscriptionDate = date('Y-m-d 00:00:00', strtotime($fundusDetail->subscription_end_date . ' +1 day'));
                }
            }

            $subsResponse = $this->subscriptionRequestData($paypalPlanId, $subscriptionDate, $returnUrl, $cancelUrl);

            logger(print_r($subsResponse, true));

            if (empty($subsResponse['error'])) {
                $paypalSubsId = $subsResponse['id'] ?? '';
                $orderDetail = $this->makePaypalOrderEntry($subsId, $amount, $currency, $paypalSubsId, self::PAYPAL_SUBSCRIPTION);

                $orderId = $orderDetail->id;
                $subsResponseStr = print_r($subsResponse, true);
                $paymentHistory = $this->makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $subsResponseStr);

                $redirectUrl = $subsResponse['links'][0]['href'];
                Session::flash('paymentBackUrl', url()->previous());
            } else {
                //error response and return
                $orderId = 0;
                $subsResponseStr = print_r($subsResponse, true);
                $paymentHistory = $this->makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $subsResponseStr);

                return redirect()->back();
            }
        }

        return redirect($redirectUrl);
    }

    public function paypalOrderResponse(Request $request) {
        //token: 70072101Y9576121M
        //PayerID: K343FLZDTGNXY
        $userId = \Auth::user()->id;
        logger(print_r($request->all(), true));
        logger(print_r($request->json()->all(), true));
        $confirmOrder = $this->provider->capturePaymentOrder($request->input('token'));

        logger(print_r($confirmOrder, true));
        if (($confirmOrder['status'] ?? '') == 'COMPLETED' || ($confirmOrder['error']['details'][0]['issue'] ?? '') == 'ORDER_ALREADY_CAPTURED') {
            $orderDetails = OrderDetail::where('paypal_order_id', $request->input('token'))
                            ->where('user_id', $userId)
                            ->where('status', 'pending')
                            ->with('subscription:id,account_type,duration,duration_in,type')->latest()->first();

            if (!empty($orderDetails)) {
                $orderDetails->status = 'success';
                $orderDetails->save();

                $accountType = $orderDetails->subscription['account_type'] ?? '';
                $duration = $orderDetails->subscription['duration'] ?? 0;
                $durationUnit = $orderDetails->subscription['duration_in'] ?? 0;

                $startDate = date('Y-m-d 00:00:00');
                $endDate = date('Y-m-d 00:00:00');

                if ($accountType == 'project') {
                    $projectDetail = ProjectDetail::where('user_id', $userId)->first();

                    if (!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                        $startDate = date('Y-m-d 00:00:00', strtotime($projectDetail->subscription_end_date . ' +1 day'));
                        $endDate = date('Y-m-d 00:00:00', strtotime($projectDetail->subscription_end_date . ' +1 day'));
                    }
                }

                if ($durationUnit == 'day') {
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} day"));
                } else if ($durationUnit == 'month') {
                    //No one month free trial in case of onetime payment (Project subscription)
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} month"));
                } else if ($durationUnit == 'year') {
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} year"));
                }

                if ($accountType == 'project') {
                    ProjectDetail::where('user_id', $userId)->update([
                        'subscription_id' => $orderDetails->subscription_id,
                        'subscription_start_date' => $startDate,
                        'subscription_end_date' => $endDate
                    ]);

                    $this->updateFreeTrialStatus();
                } else if ($accountType == 'fundus') {
                    FundusDetail::where('user_id', $userId)->update([
                        'subscription_id' => $orderDetails->subscription_id,
                        'subscription_start_date' => $startDate,
                        'subscription_end_date' => $endDate,
                        'product_upload_limit' => config('app.max_articles_fundus_pro'),
                        'package_type' => 'pro'
                    ]);
                }

                SubscriptionHistory::create([
                    'user_id' => $userId,
                    'subscription_id' => $orderDetails->subscription_id,
                    'amount' => $orderDetails->amount,
                    'currency' => $orderDetails->currency,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);

                $invoiceDuration = [$startDate, $endDate];
                $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
                list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                $emailData['invoice_number'] = $invoiceObject->sevdesk_invoice_id;
                Notification::send(Auth::user(), new PaymentNotification(Auth::user()->name, 'paypal', $emailData, $fileContent, $fileName));

                Session::flash('showInformationModal', 'true');
                Session::flash('modalHeading', __('status_message.PAYPAL_SUCCESS_HEADING'));
                Session::flash('modalMessage', __('status_message.PAYPAL_SUCCESS_MESSAGE'));

                //ADVANCED_PAYPAL_SUCCESS_HEADING
                //Session::flash('modalHeading', __('status_message.ADVANCED_PAYPAL_SUCCESS_HEADING'));
                //Session::flash('modalMessage', __('status_message.ADVANCED_PAYPAL_SUCCESS_MESSAGE'));

                if (Session::has('paymentBackUrl')) {
                    return redirect(Session::get('paymentBackUrl'));
                } else {
                    return redirect()->route('product.category', ['category' => 'default']);
                }
            } else {
                //Error message and return
                return redirect()->back();
            }
        }
    }

    public function paypalOrderCancel(Request $request) {
        $orderId = $request->input('token', '');
        if (!empty($orderId)) {
            OrderDetail::where('paypal_order_id', $orderId)->where('status', 'pending')->update(['status' => 'cancel']);
        }

        if (Session::has('paymentBackUrl')) {
            return redirect(Session::get('paymentBackUrl'));
        } else {
            return redirect()->route('index');
        }
    }

    public function paypalSubscriptionResponse(Request $request) {
        $userId = \Auth::user()->id;
        $paypalSubscriptionId = $request->input('subscription_id', '');
        $subscription = $this->provider->showSubscriptionDetails($paypalSubscriptionId);
        $freeTrialStatus = false;
        $currentPackage = '';
        $newPackage = '';

        logger(print_r($request->all(), true));
        logger(print_r($subscription, true));
        //$confirmSubscription = $this->provider->captureSubscriptionPayment($request->input('subscription_id'), 'Subscription due', '14.99');

        $orderDetails = OrderDetail::where('paypal_subscription_id', $paypalSubscriptionId)
                        ->where('user_id', $userId)
                        ->where('status', 'pending')
                        ->with('subscription:id,account_type,duration,duration_in,type')->latest()->first();

        if (!empty($orderDetails)) {
            $orderDetails->status = 'success';
            $orderDetails->save();

            $accountType = $orderDetails->subscription['account_type'] ?? '';
            $duration = $orderDetails->subscription['duration'] ?? 0;
            $durationUnit = $orderDetails->subscription['duration_in'] ?? 0;
            $durationType = $orderDetails->subscription['type'] ?? '';

            if ($accountType == 'project') {
                $projectDetail = ProjectDetail::where('user_id', $userId)->first();

                if (!empty($projectDetail->subscription_end_date) && $projectDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                    ProjectDetail::where('user_id', $userId)->update([
                        'is_subscription_paused' => 0,
                        'paypal_subscription_id' => $paypalSubscriptionId,
                        'subscription_id' => $orderDetails->subscription_id,
                            //'subscription_start_date' => $startDate,
                            //'subscription_end_date' => $endDate
                    ]);

                    Session::flash('showInformationModal', 'true');
                    Session::flash('modalHeading', __('status_message.PROJECT_ADVANCED_PAYPAL_SUCCESS_HEADING'));
                    Session::flash('modalMessage', __('status_message.PROJECT_ADVANCED_PAYPAL_SUCCESS_MESSAGE'));

                    if (Session::has('paymentBackUrl')) {
                        return redirect(Session::get('paymentBackUrl'));
                    } else {
                        return redirect()->route('product.category', ['category' => 'default']);
                    }
                }
            } else if ($accountType == 'fundus') {
                $fundusDetail = FundusDetail::where('user_id', $userId)->first();
                $currentPackage = $fundusDetail->package_type ?? '';
                $newPackage = 'pro';

                if (!empty($fundusDetail->subscription_end_date) && $fundusDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
                    $subscriptionEndDate = date('d.m.Y', strtotime($fundusDetail->subscription_end_date));
                    $newSubsStartDate = date('Y-m-d', strtotime($fundusDetail->subscription_end_date . ' +1 day'));

                    FundusDetail::where('user_id', $userId)->update([
                        //'is_subscription_paused' => 0,
                        'paypal_subscription_id' => $paypalSubscriptionId,
                        'subscription_id' => $orderDetails->subscription_id,
                        //'subscription_start_date' => $startDate,
                        //'subscription_end_date' => $endDate,
                        //'product_upload_limit' => config('app.max_articles_fundus_pro'),
                        'package_type' => 'pro'
                    ]);

                    FundusDowngradeRequest::create([
                        'fundus_id' => $fundusDetail->id,
                        'current_package' => $currentPackage,
                        'new_package' => $newPackage,
                        'start_date' => $newSubsStartDate,
                        'status' => 'pending'
                    ]);

                    $emailData['subscription_end_date'] = $subscriptionEndDate;
                    Notification::send(Auth::user(), new FundusProAdvancePaypalNotification(Auth::user()->name, $emailData));

                    //Email notification to admin for downgrade from infinite to pro
                    if ($currentPackage == 'infinite' && $newPackage == 'pro') {
                        $emailData = [];
                        $emailData['email'] = Auth::user()->email;
                        $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                        $emailData['package_name'] = strtoupper($newPackage);
                        $this->sendInfinitePackageDowngradeEmail($emailData);
                    }

                    Session::flash('showInformationModal', 'true');
                    Session::flash('modalHeading', __('status_message.FUNDUS_ADVANCED_PAYPAL_SUCCESS_HEADING'));
                    Session::flash('modalMessage', __('status_message.FUNDUS_ADVANCED_PAYPAL_SUCCESS_MESSAGE'));

                    if (Session::has('paymentBackUrl')) {
                        return redirect(Session::get('paymentBackUrl'));
                    } else {
                        return redirect()->route('product.category', ['category' => 'default']);
                    }
                }
            }

            $startDate = date('Y-m-d 00:00:00');
            $endDate = date('Y-m-d 00:00:00');
            if ($durationUnit == 'day') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} day"));
            } else if ($durationUnit == 'month') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} month"));
            } else if ($durationUnit == 'year') {
                $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} year"));
            }

            if ($accountType == 'project') {
                ProjectDetail::where('user_id', $userId)->update([
                    'is_subscription_paused' => 0,
                    'paypal_subscription_id' => $paypalSubscriptionId,
                    'subscription_id' => $orderDetails->subscription_id,
                    'subscription_start_date' => $startDate,
                    'subscription_end_date' => $endDate
                ]);

                $freeTrialStatus = $this->getFreeTrialStatus();
                if ($freeTrialStatus == true) {
                    $this->updateFreeTrialStatus();
                }
            } else if ($accountType == 'fundus') {
                $fundusDetail = FundusDetail::where('user_id', $userId)->first();
                $currentPackage = $fundusDetail->package_type ?? '';
                $newPackage = 'pro';

                FundusDetail::where('user_id', $userId)->update([
                    'is_subscription_paused' => 0,
                    'paypal_subscription_id' => $paypalSubscriptionId,
                    'subscription_id' => $orderDetails->subscription_id,
                    'subscription_start_date' => $startDate,
                    'subscription_end_date' => $endDate,
                    'product_upload_limit' => config('app.max_articles_fundus_pro'),
                    'package_type' => 'pro'
                ]);

                if ($fundusDetail->package_type == 'basic') {
                    FundusDowngradeRequest::create([
                        'fundus_id' => $fundusDetail->id,
                        'current_package' => $currentPackage,
                        'new_package' => $newPackage,
                        'start_date' => date('Y-m-d'),
                        'status' => 'pending'
                    ]);
                }
            }

            SubscriptionHistory::create([
                'user_id' => $userId,
                'subscription_id' => $orderDetails->subscription_id,
                'amount' => $orderDetails->amount,
                'currency' => $orderDetails->currency,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $invoiceDuration = [$startDate, $endDate];
            if ($accountType == 'project') {
                if ($freeTrialStatus == true && $durationType == 'recurring') {
                    //Stop creating invoice for the trial month and send email without invoice
                    Notification::send(Auth::user(), new FreeTrialPaymentNotification(Auth::user()->name, 'paypal'));
                } else {
                    $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
                    list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                    $emailData['invoice_number'] = $invoiceObject->sevdesk_invoice_id;
                    Notification::send(Auth::user(), new PaymentNotification(Auth::user()->name, 'paypal', $emailData, $fileContent, $fileName));
                }
            } else if ($accountType == 'fundus') {
                $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
                list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                Notification::send(Auth::user(), new FundusProPaypalNotification(Auth::user()->name, [], $fileContent, $fileName));

                //Email notification to admin for downgrade from infinite to pro
                if ($currentPackage == 'infinite' && $newPackage == 'pro') {
                    $emailData = [];
                    $emailData['email'] = Auth::user()->email;
                    $emailData['name'] = Auth::user()->first_name . ' ' . Auth::user()->last_name;
                    $emailData['package_name'] = strtoupper($newPackage);
                    $this->sendInfinitePackageDowngradeEmail($emailData);
                }
            }

            if ($accountType == 'project' && $freeTrialStatus == true && $durationType == 'recurring') {
                //Trial Month use case
                Session::flash('showInformationModal', 'true');
                Session::flash('modalHeading', __('status_message.FREE_TRIAL_PAYPAL_SUCCESS_HEADING'));
                Session::flash('modalMessage', __('status_message.FREE_TRIAL_PAYPAL_SUCCESS_MESSAGE'));
            } else {
                Session::flash('showInformationModal', 'true');
                Session::flash('modalHeading', __('status_message.PAYPAL_SUCCESS_HEADING'));
                Session::flash('modalMessage', __('status_message.PAYPAL_SUCCESS_MESSAGE'));
            }

            if (Session::has('paymentBackUrl')) {
                return redirect(Session::get('paymentBackUrl'));
            } else {
                return redirect()->route('product.category', ['category' => 'default']);
            }
        } else {
            //Error message
            return redirect()->back();
        }
    }

    public function paypalSubscriptionCancel(Request $request) {
        $subscriptionId = $request->input('subscription_id', '');
        if (!empty($subscriptionId)) {
            OrderDetail::where('paypal_subscription_id', $subscriptionId)->where('status', 'pending')->update(['status' => 'cancel']);
        }

        if (Session::has('paymentBackUrl')) {
            return redirect(Session::get('paymentBackUrl'));
        } else {
            return redirect()->route('index');
        }
    }

    public function paypalNotify(Request $request) {
        logger(print_r($request->all(), true));
        $notificationData = $request->all();
        if (!empty($notificationData['recurring_payment_id']) && $notificationData['txn_type'] == 'recurring_payment' && $notificationData['payment_status'] == 'Completed') {

            $paypalSubscriptionId = $notificationData['recurring_payment_id'];
            $subscription = $this->provider->showSubscriptionDetails($paypalSubscriptionId);

            logger(print_r($subscription, true));

            $orderDetails = OrderDetail::where('paypal_subscription_id', $paypalSubscriptionId)
                            //->where('user_id', $userId)
                            //->where('status', 'pending')
                            ->with('subscription:id,account_type,duration,duration_in,type')->latest()->first();

            if (!empty($orderDetails)) {
                if (date('Y-m-d', strtotime($orderDetails->created_at)) == date('Y-m-d')) {
                    return response('success', 200);
                }

                $userId = $orderDetails->user_id;
                $userObject = User::where('id', $userId)->first();

                $accountType = $orderDetails->subscription['account_type'] ?? '';
                $duration = $orderDetails->subscription['duration'] ?? 0;
                $durationUnit = $orderDetails->subscription['duration_in'] ?? 0;

                $startDate = date('Y-m-d 00:00:00');
                $endDate = date('Y-m-d 00:00:00');
                if ($durationUnit == 'day') {
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} day"));
                } else if ($durationUnit == 'month') {
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} month"));
                } else if ($durationUnit == 'year') {
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + ${duration} year"));
                }

                if ($accountType == 'project') {
                    ProjectDetail::where('user_id', $userId)->update([
                        //'is_subscription_paused' => 0,
                        //'paypal_subscription_id' => $paypalSubscriptionId,
                        //'subscription_id' => $orderDetails->subscription_id,
                        'subscription_start_date' => $startDate,
                        'subscription_end_date' => $endDate
                    ]);
                } else if ($accountType == 'fundus') {
                    FundusDetail::where('user_id', $userId)->update([
                        //'is_subscription_paused' => 0,
                        //'paypal_subscription_id' => $paypalSubscriptionId,
                        //'subscription_id' => $orderDetails->subscription_id,
                        'subscription_start_date' => $startDate,
                        'subscription_end_date' => $endDate,
                            //'product_upload_limit' => config('app.max_articles_fundus_pro'),
                            //'package_type' => 'pro'
                    ]);
                }

                SubscriptionHistory::create([
                    'user_id' => $userId,
                    'subscription_id' => $orderDetails->subscription_id,
                    'amount' => $orderDetails->amount,
                    'currency' => $orderDetails->currency,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);

                $invoiceDuration = [$startDate, $endDate];
                if ($accountType == 'project') {
                    $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
                    list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                    Notification::send($userObject, new ProjectPaymentRenewalNotification($userObject->name, 'paypal', [], $fileContent, $fileName));
                } else if ($accountType == 'fundus') {
                    $invoiceObject = $this->createInvoice($userId, $orderDetails->id, $orderDetails->subscription_id, $invoiceDuration);
                    list($fileName, $fileContent) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                    Notification::send($userObject, new FundusProPaypalRenewalNotification($userObject->name, [], $fileContent, $fileName));
                }
            }
        }
        return response('success', 200);
    }

    private function createOrderRequest($amount, $currency) {
        $orderRequest = [];
        $orderRequest["intent"] = "CAPTURE";
        $orderRequest["purchase_units"][] = ['amount' => ["currency_code" => $currency, "value" => $amount]];
        $orderRequest["application_context"] = ["return_url" => route('paypal.order.response'), "cancel_url" => route('paypal.order.cancel')];

        return $orderRequest;
    }

    public function makePaypalOrderEntry($subsId, $amount, $currency, $paypalRefId, $paypalTransType) {
        $orderNumber = $this->generateOrderNumber();

        $orderDetail = OrderDetail::create([
                    'user_id' => Auth::user()->id,
                    'subscription_id' => $subsId,
                    'order_number' => $orderNumber,
                    'paypal_order_id' => $paypalTransType == self::PAYPAL_ORDER ? $paypalRefId : '',
                    'paypal_subscription_id' => $paypalTransType == self::PAYPAL_SUBSCRIPTION ? $paypalRefId : '',
                    'order_date' => date('Y-m-d H:i:s'),
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_mode' => 'paypal',
                    'status' => 'pending',
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
        ]);

        return $orderDetail;
    }

    public function makeBankOrderEntry($subsId, $amount, $currency) {
        $orderNumber = $this->generateOrderNumber();

        $orderDetail = OrderDetail::create([
                    'user_id' => Auth::user()->id,
                    'subscription_id' => $subsId,
                    'order_number' => $orderNumber,
                    'order_date' => date('Y-m-d H:i:s'),
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_mode' => 'bank',
                    'status' => 'pending',
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
        ]);

        return $orderDetail;
    }

    public function makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $orderResponseStr) {
        $paymentHistory = PaymentHistory::create([
                    'user_id' => Auth::user()->id,
                    'subscription_id' => $subsId,
                    'order_id' => $orderId,
                    'amount' => $amount,
                    'currency' => $currency,
                    'request' => $orderResponseStr,
                    'status' => 'pending',
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id,
        ]);

        return $paymentHistory;
    }

    public function processPaypalSubscriptionRequest($subsId, $amount, $currency, $paypalPlanId) {
        $userId = \Auth::user()->id;
        //Subscription processing
        $subscriberName = Auth::user()->name;
        $subscriberEmail = Auth::user()->email;
        $subscriptionDate = date('Y-m-d H:i:s', strtotime('+1 minutes'));
        $returnUrl = route('paypal.subscription.response');
        $cancelUrl = route('paypal.subscription.cancel');

        $fundusDetail = FundusDetail::where('user_id', $userId)->first();
        if (!empty($fundusDetail->subscription_end_date) && $fundusDetail->subscription_end_date >= date('Y-m-d 23:59:59')) {
            $subscriptionDate = date('Y-m-d 00:00:00', strtotime($fundusDetail->subscription_end_date . ' +1 day'));
        }

        $subsResponse = $this->subscriptionRequestData($paypalPlanId, $subscriptionDate, $returnUrl, $cancelUrl);

        logger(print_r($subsResponse, true));

        if (empty($subsResponse['error'])) {
            $paypalSubsId = $subsResponse['id'] ?? '';
            $orderDetail = $this->makePaypalOrderEntry($subsId, $amount, $currency, $paypalSubsId, self::PAYPAL_SUBSCRIPTION);

            $orderId = $orderDetail->id;
            $subsResponseStr = print_r($subsResponse, true);
            $paymentHistory = $this->makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $subsResponseStr);

            $redirectUrl = $subsResponse['links'][0]['href'];
            Session::flash('paymentBackUrl', url()->previous());
            return redirect($redirectUrl);
        } else {
            //error response and return
            $orderId = 0;
            $subsResponseStr = print_r($subsResponse, true);
            $paymentHistory = $this->makePaymentHistoryEntry($subsId, $amount, $currency, $orderId, $subsResponseStr);

            return redirect()->back();
        }
    }

    public function generateOrderNumber() {

        return $orderNumber = 'Z' . time() . rand(100, 999);
    }

    public function subscriptionRequestData($planCode, $subscriptionDate, $returnUrl, $cancelUrl) {
        $subscriberName = Auth::user()->name;
        $subscriberEmail = Auth::user()->email;
        $subscriptionDate = Carbon::parse($subscriptionDate)->toIso8601String();

        $data = json_decode('{
            "plan_id": "' . $planCode . '",
            "start_time": "' . $subscriptionDate . '",
            "quantity": "1",
            "subscriber": {
              "name": {
                "given_name": "' . $subscriberName . '",
                "surname": ""
              },
              "email_address": "' . $subscriberEmail . '"
            },
            "application_context": {
              "brand_name": "SetBakers",
              "user_action": "SUBSCRIBE_NOW",
              "payment_method": {
                "payer_selected": "PAYPAL",
                "payee_preferred": "IMMEDIATE_PAYMENT_REQUIRED"
              },
              "return_url": "' . $returnUrl . '",
              "cancel_url": "' . $cancelUrl . '"
            }
          }', true);

        $subscription = $this->provider->createSubscription($data);

        return $subscription;
    }

}
