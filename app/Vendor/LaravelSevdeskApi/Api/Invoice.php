<?php

namespace App\Vendor\LaravelSevdeskApi\Api;

use Exlo89\LaravelSevdeskApi\Api\Utils\Routes;

class Invoice extends \Exlo89\LaravelSevdeskApi\Api\Invoice {

    public function create($contactId, array $parameters = []) {
        $requiredParameters = [
            'invoice' => [
                'objectName' => 'Invoice',
                'contact' => [
                    'id' => $contactId,
                    'objectName' => 'Contact'
                ],
                'invoiceDate' => date('Y-m-d H:i:s'),
                'discount' => 0,
                'addressCountry' => [
                    'id' => 1,
                    'objectName' => 'StaticCountry'
                ],
                'status' => self::DRAFT,
                'contactPerson' => [
                    'id' => config('sevdesk-api.sev_user_id'),
                    'objectName' => 'SevUser'
                ],
                'taxRate' => config('sevdesk-api.tax_rate'),
                'taxText' => config('sevdesk-api.tax_text'),
                'taxType' => config('sevdesk-api.tax_type'),
                'invoiceType' => config('sevdesk-api.invoice_type'),
                'currency' => config('sevdesk-api.currency'),
                'mapAll' => 'true'
            ],
            'invoicePosSave' => [
                [
                    'objectName' => 'InvoicePos',
                    'quantity' => 1,
                    'unity' => [
                        'id' => 1,
                        'objectName' => 'Unity',
                    ]
                ]
            ]
        ];
        $allParameters = array_replace_recursive($requiredParameters, $parameters);
        return $this->_post(Routes::CREATE_INVOICE, $allParameters);
    }

    public function download($invoiceId) {
        $response = $this->_get(Routes::INVOICE . '/' . $invoiceId . '/getPdf');
        $file = $response['filename'];
        return [$file, base64_decode($response['content'])];
    }

}
