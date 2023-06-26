<?php

namespace App\Traits\Notifications;

use Illuminate\Support\Facades\Notification;
use App\Notifications\PackageDowngradeAdminNotification;
use App\Notifications\FundusProAdvanceBankTransferReminderNotification;
use App\Notifications\UserDeleteAdminNotification;

trait EmailNotifications {

    public function sendInfinitePackageDowngradeEmail($emailData) {
        Notification::route('mail', config('app.contactus_email_id'))->notify(new PackageDowngradeAdminNotification($emailData));
        return true;
    }

    public function sendFundusProAdvanceBankTransferInvoice($emailId, $name, $emailData, $attachment, $attachmentName) {
        Notification::route('mail', $emailId)->notify(new FundusProAdvanceBankTransferReminderNotification($name, $emailData, $attachment, $attachmentName));
        return true;
    }

    public function sendInfiniteAccountDeleteEmail($emailData) {
        Notification::route('mail', config('app.contactus_email_id'))->notify(new UserDeleteAdminNotification($emailData));
        return true;
    }

}
