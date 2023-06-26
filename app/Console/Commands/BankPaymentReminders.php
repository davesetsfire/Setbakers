<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FundusDetail;
use App\Models\OrderDetail;
use App\Models\FundusDowngradeRequest;
use App\Traits\Notifications\EmailNotifications;
use App\Traits\SevDesk\SevDeskFunctions;

class BankPaymentReminders extends Command {

    use EmailNotifications,
        SevDeskFunctions;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:bankpayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Bank Payment Email Reminders';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $fundusDetails = FundusDetail::where('subscription_end_date', date('Y-m-d 23:59:59', strtotime('+1 Month')))
                        ->where('package_type', 'pro')
                        ->whereHas('subscription')->with([
                    'subscription' => function ($query) {
                        $query->where('type', 'recurring')->where('duration_in', 'year');
                    }
                ])->get();

        foreach ($fundusDetails as $fundusDetail) {
            $userId = $fundusDetail->user_id;
            $orderDetail = OrderDetail::where('user_id', $userId)
                            ->where('subscription_id', $fundusDetail->subscription_id)
                            ->where('payment_mode', 'bank')
                            ->where('status', 'pending')->latest()->first();

            if (!empty($orderDetail)) {
                $fundusDowngradeRequest = FundusDowngradeRequest::where('fundus_id', $fundusDetail->id)
                                ->where('current_package', 'infinite')
                                ->where('new_package', 'pro')
                                ->where('start_date', '>', date('Y-m-d'))
                                ->where('status', 'pending')->first();

                if (!empty($fundusDowngradeRequest)) {
                    $emailId = $fundusDetail->fundus_email;
                    $name = $fundusDetail->fundus_owner_name;
                    $emailData['subscription_end_date'] = date('d.m.Y', strtotime($fundusDetail->subscription_end_date));

                    $startDate = date('Y-m-d', strtotime($fundusDetail->subscription_end_date . ' +1 day'));
                    $endDate = date('Y-m-d 23:59:59', strtotime("$startDate + 1 year"));
                    
                    $invoiceDuration = [$startDate, $endDate];
                    $invoiceObject = $this->createInvoice($userId, $orderDetail->id, $orderDetail->subscription_id, $invoiceDuration);
                    list($attachmentName, $attachment) = $this->downloadInvoice($invoiceObject->sevdesk_invoice_id);

                    $this->sendFundusProAdvanceBankTransferInvoice($emailId, $name, $emailData, $attachment, $attachmentName);
                }
            }
        }
    }

}
