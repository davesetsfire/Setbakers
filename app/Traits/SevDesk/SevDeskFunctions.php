<?php

namespace App\Traits\SevDesk;

//use Exlo89\LaravelSevdeskApi\SevdeskApi;
use App\Vendor\LaravelSevdeskApi\SevdeskApi;
use App\Models\User;
use App\Models\SevdeskContact;
use App\Models\SubscriptionPlan;
use App\Models\SevdeskInvoice;
use App\Models\OrderDetail;

trait SevDeskFunctions {

    public function createContact($userId, $accountType) {
        $contact = null;
        $sevdeskApi = SevdeskApi::make();
        $parameters = ['category' => 3];
        $contactName = '';
        $userDetail = User::where('id', $userId)->with('projectDetail', 'fundusDetail')->first();
        if ($accountType == 'project') {
            if ($userDetail->projectDetail['is_company']) {
                $contactName = $userDetail->projectDetail['company_name'] ?? '';
                $parameters['vatNumber'] = $userDetail->projectDetail['ust_id'] ?? '';
            }
            $parameters['surename'] = $userDetail->first_name;
            $parameters['familyname'] = $userDetail->last_name;
        } else if ($accountType == 'fundus') {
            if ($userDetail->fundusDetail['is_company']) {
                $contactName = $userDetail->fundusDetail['company_name'] ?? '';
                $parameters['vatNumber'] = $userDetail->fundusDetail['ust_id'] ?? '';
            }
            $parameters['surename'] = $userDetail->fundusDetail['owner_first_name'];
            $parameters['familyname'] = $userDetail->fundusDetail['owner_last_name'];
        }

        $response = $sevdeskApi->contact()->createCustomer($contactName, $parameters);
        if (!empty($response['id'])) {
            $contact = SevdeskContact::create([
                        'user_id' => $userId,
                        'account_type' => $accountType,
                        'sevdesk_contact_id' => $response['id'],
                        'sevdesk_customer_number' => $response['customerNumber']
            ]);
        }
        return $contact;
    }

    public function getContactId($userId, $accountType) {
        $contact = SevdeskContact::where('user_id', $userId)
                        ->where('account_type', $accountType)->first();
        if (!empty($contact)) {
            return $contact->sevdesk_contact_id;
        } else {
            $contact = $this->createContact($userId, $accountType);
            return $contact->sevdesk_contact_id ?? 0;
        }
    }

    public function createInvoice($userId, $orderId, $subscriptionId, $invoiceDuration) {
        $invoiceObject = SevdeskInvoice::where('user_id', $userId)
                        ->where('order_id', $orderId)
                        ->where('invoice_date', date('Y-m-d'))->first();
        if (!empty($invoiceObject)) {
            return $invoiceObject;
        }

        $subscription = SubscriptionPlan::where('id', $subscriptionId)->first();
        $orderDetail = OrderDetail::where('id', $orderId)->first();

        $contactId = $this->getContactId($userId, $subscription->account_type);

        $userDetail = User::where('id', $userId)->with('projectDetail', 'fundusDetail')->first();

        $companyName = '';
        $street = "";
        $city = "";

        if ($subscription->account_type == 'fundus') {
            if ($userDetail->fundusDetail['is_company']) {
                $companyName = $userDetail->fundusDetail['company_name'];
            } else {
                $companyName = $userDetail->fundusDetail['fundus_owner_name'] ?? '';
            }
            $street = $userDetail->fundusDetail['street'] . ' ' . $userDetail->fundusDetail['house_number'];
            $city = $userDetail->fundusDetail['postal_code'] . ' ' . $userDetail->fundusDetail['location'];
        }

        if ($subscription->account_type == 'project') {
            if ($userDetail->projectDetail['is_company']) {
                $companyName = $userDetail->projectDetail['company_name'];
            } else {
                $companyName = $userDetail->name ?? '';
            }
            $street = $userDetail->projectDetail['street'] . ' ' . $userDetail->projectDetail['house_number'];
            $city = $userDetail->projectDetail['postal_code'] . ' ' . $userDetail->projectDetail['location'];
        }

        $invoiceCustomerName = $subscription->account_type == 'project' ? $userDetail->name : $userDetail->fundusDetail['fundus_owner_name'];
        $invoiceCustomerAddress = "${companyName} \n${street} \n${city}";

        $durationIn = $subscription->duration_in;
        if ($subscription->account_type == 'project' && $subscription->type == 'onetime') {
            $durationIn = 'onetime';
        }
        $invoicePosName = __('invoice.' . $subscription->account_type . '.' . $durationIn . '.' . $orderDetail->payment_mode . '.pos_name');
        $invoiceHeadText = __('invoice.' . $subscription->account_type . '.' . $durationIn . '.' . $orderDetail->payment_mode . '.head_text', ['name' => $invoiceCustomerName]);
        $invoiceFootText = __('invoice.' . $subscription->account_type . '.' . $durationIn . '.' . $orderDetail->payment_mode . '.foot_text');

        $invoiceStatus = $orderDetail->payment_mode == 'paypal' ? config('sevdesk-api.invoice_paid') : config('sevdesk-api.invoice_open');

        $parameters = [
            "invoice" => [
                "headText" => $invoiceHeadText,
                "footText" => $invoiceFootText,
                "address" => $invoiceCustomerAddress,
                'status' => $invoiceStatus,
                "showNet" => "false",
                "deliveryDate" => date('Y-m-d', strtotime($invoiceDuration[0])),
                "deliveryDateUntil" => date('Y-m-d', strtotime($invoiceDuration[1]))
            ],
            "invoicePosSave" => [
                [
                    "objectName" => "InvoicePos",
                    "mapAll" => "true",
                    "name" => $invoicePosName,
                    "unity" => [
                        "id" => "37",
                        "objectName" => "Unity"
                    ],
                    "positionNumber" => "0",
                    "quantity" => $subscription->duration,
                    "price" => ($subscription->amount / $subscription->duration),
                    "priceTax" => $subscription->tax,
                    "taxRate" => config('sevdesk-api.tax_rate')
                //"priceNet" => $subscription->basic_amount,
                //"priceGross" => $subscription->amount,
//                    "discount" => "0",
//                    "sumNet" => $subscription->basic_amount,
//                    "sumGross" => $subscription->amount,
//                    "sumTax" => $subscription->tax,
//                    "sumNetAccounting" => $subscription->basic_amount,
//                    "sumTaxAccounting" => $subscription->tax,
//                    "sumGrossAccounting" => $subscription->amount,
//                    "sumDiscount" => "0",
//                    "isPercentage" => "1"
                ]
            ]
        ];

        $sevdeskApi = SevdeskApi::make();
        $invoiceData = $sevdeskApi->invoice()->create($contactId, $parameters);

        if (empty($invoiceData['invoice']['id'])) {
            //error in invoice creation
            logMessage('SEVDESK', $invoiceData);
        }
        $invoiceObject = SevdeskInvoice::create([
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'sevdesk_invoice_id' => $invoiceData['invoice']['id'],
                    'sevdesk_invoice_number' => $invoiceData['invoice']['id'],
                    'invoice_pdf_file_path' => '',
                    'invoice_date' => date('Y-m-d H:i:s')
        ]);
        return $invoiceObject;
    }

    public function downloadInvoice($invoiceId) {
        $sevdeskApi = SevdeskApi::make();
        return $sevdeskApi->invoice()->download($invoiceId);
    }

}
