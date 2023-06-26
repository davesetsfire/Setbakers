<?php

if (!function_exists('successResponse')) {

    function successResponse($successMessage, $additionalData = Array()) {
        $response["status"] = "success";
        $response["message"] = $successMessage;
        if (!empty($additionalData)) {
            foreach ($additionalData as $dataItemKey => $dataItemValue) {
                $response[$dataItemKey] = $dataItemValue;
            }
        }
        return $response;
    }

}

if (!function_exists('errorResponse')) {

    function errorResponse($errorMessage, $errorCode, $additionalData = Array()) {
        $response["status"] = "error";
        $response["code"] = $errorCode;
        $response["message"] = $errorMessage;
        if (!empty($additionalData)) {
            foreach ($additionalData as $dataItemKey => $dataItemValue) {
                $response[$dataItemKey] = $dataItemValue;
            }
        }
        return $response;
    }

}

function callURL($api_url, $params, $logFile = '', $headers = Array(), $executionInfo = false) {
    //echo $api_url . '?' . http_build_query($params);
    $response = array();
    $queryParams = '';
    if (gettype($params) == 'array') {
        $queryParams = http_build_query($params);
    } else {
        $queryParams = $params;
    }
    $curl_handle = curl_init();

    //$getUrl = $url."?".$data;



    curl_setopt($curl_handle, CURLOPT_URL, $api_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $queryParams);
    if (!empty($headers)) {
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
    }
    if (parse_url($api_url, PHP_URL_SCHEME) == 'https') {
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    }

    $apiResponse = curl_exec($curl_handle);

    //CURL Response logging
    $curlInfo = curl_getinfo($curl_handle);
    $errorMsg = curl_error($curl_handle);
    $errorNo = curl_errno($curl_handle);

    if (!empty($logFile)) {
        $content = date('Y-m-d H:i:s') . ' - ' . $curlInfo['total_time'] . ' - ' . $errorNo . ' - ' . $errorMsg . ' - ' . $queryParams . ' - ' . $apiResponse;
        logMessage($logFile, $content);
    }
    if (!empty($apiResponse)) {
        $response = json_decode($apiResponse, true);
    }
    if ($executionInfo == true) {
        $response['execution_time'] = $curlInfo['total_time'];
        $response['execution_error_no'] = $errorNo;
        $response['execution_error_msg'] = $errorMsg;
    }
    curl_close($curl_handle);

    return $response;
}

function callURLGetMethod($api_url, $params, $logFile = '', $headers = Array(), $executionInfo = false) {
    $response = array();
    $queryParams = '';
    if (gettype($params) == 'array') {
        $queryParams = http_build_query($params);
    } else {
        $queryParams = $params;
    }
    $curl_handle = curl_init();

    $api_url = $api_url . '?' . $queryParams;

    curl_setopt($curl_handle, CURLOPT_URL, $api_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);

    if (!empty($headers)) {
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
    }
    if (parse_url($api_url, PHP_URL_SCHEME) == 'https') {
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, false);
    }

    $apiResponse = curl_exec($curl_handle);

    //CURL Response logging
    $curlInfo = curl_getinfo($curl_handle);
    $errorMsg = curl_error($curl_handle);
    $errorNo = curl_errno($curl_handle);

    if (!empty($logFile)) {
        $content = date('Y-m-d H:i:s') . ' - ' . $curlInfo['total_time'] . ' - ' . $errorNo . ' - ' . $errorMsg . ' - ' . $queryParams . ' - ' . $apiResponse;
        logMessage($logFile, $content);
    }
    $response = $apiResponse;
    //if (!empty($apiResponse)) {
    //    $response = json_decode($apiResponse, true);
    //}
    if ($executionInfo == true) {
        $response['execution_time'] = $curlInfo['total_time'];
        $response['execution_error_no'] = $errorNo;
        $response['execution_error_msg'] = $errorMsg;
    }
    curl_close($curl_handle);

    return $response;
}

function callURLWithJson($api_url, $params, $logFile = '') {
    $curl_handle = curl_init();
    $queryParams = json_encode($params);
    curl_setopt($curl_handle, CURLOPT_URL, $api_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 10);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $queryParams);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($queryParams))
    );

    $apiResponse = curl_exec($curl_handle);

    //CURL Response logging
    $curlInfo = curl_getinfo($curl_handle);
    $errorMsg = curl_error($curl_handle);
    $errorNo = curl_errno($curl_handle);

    if (!empty($logFile)) {
        $content = date('Y-m-d H:i:s') . ' - ' . $curlInfo['total_time'] . ' - ' . $errorNo . ' - ' . $errorMsg . ' - ' . $queryParams . ' - ' . $apiResponse;
        logMessage($logFile, $content);
    }

    curl_close($curl_handle);
    $response = array();
    if (!empty($apiResponse)) {
        $response = json_decode($apiResponse, true);
    }
    return $response;
}

function logMessage($logtype, $content) {
    if (is_array($content)) {
        $content = print_r($content, true);
    }
    $formattedDate = date('Ymd');
    $fileName = storage_path() . '/logs/' . $logtype . '_' . $formattedDate . '.txt';
    file_put_contents($fileName, $content . "\n", FILE_APPEND);
}

function generateUniqueCodes($length = 5) {
    //$randomStr = random_str(10);
    $randomStr = strtoupper(bin2hex(openssl_random_pseudo_bytes($length)));
    return $randomStr;
}

function random_str($length, $keyspace = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces [] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function generateOTP() {
    return random_str(6, '0123456789');
}

function generateActivationCode() {
    return random_str(10, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz');
}

function sendSMS($mobileNumber, $smsText, $contentId) {
    $params['EntityID'] = env('SMS_ENTITY_ID');
    $params['username'] = env('SMS_API_USER');
    $params['password'] = env('SMS_API_PWD');
    $params['messageType'] = 'text';
    $params['mobile'] = $mobileNumber;
    $params['message'] = $smsText;
    $params['senderId'] = env('SMS_SENDER');
    $params['ContentID'] = $contentId;

    if (!Illuminate\Support\Facades\App::environment('local')) {
        callURL(env('SMS_API'), $params, 'sentSMS');
    }
}

function resizeImage($file, $w, $h, $extension) {
    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    $startx = 0;
    $starty = 0;

    if ($w / $h > $r) {
        $newwidth = $h * $r;
        $newheight = $h;
        $startx = abs(($w - $newwidth) / 2);
        //$newwidth = $w * $r;
        //$newheight = $h * $r;
    } else {
        $newheight = $w / $r;
        $newwidth = $w;
        $starty = abs(($h - $newheight) / 2);
        //$newheight = $h / $r;
        //$newwidth = $w / $r;
    }

    $src = null;
    if ($extension == 'jpg' || $extension == 'jpeg') {
        $src = imagecreatefromjpeg($file);
    } else if ($extension == 'png') {
        $src = imagecreatefrompng($file);
    } else if ($extension == 'gif') {
        $src = imagecreatefromgif($file);
    }

    //$dst = imagecreatetruecolor($w, $h);
    $dst = imagecreatetruecolor($newwidth, $newheight);
    //$colour = imagecolorallocate($dst, 255,255, 255);
    //imagefill($dst, 0, 0, $colour);
    //imagecopyresampled($dst, $src, $startx, $starty, 0, 0, $newwidth, $newheight, $width, $height);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    return $dst;
}

function saveImage($imageObject, $imageName, $extension) {

    if ($extension == 'jpg' || $extension == 'jpeg') {
        imagejpeg($imageObject, storage_path('app/media/public') . '/' . $imageName);
    } else if ($extension == 'png') {
        imagepng($imageObject, storage_path('app/media/public') . '/' . $imageName);
    } else if ($extension == 'gif') {
        imagegif($imageObject, storage_path('app/media/public') . '/' . $imageName);
    }
}

function formatNumber($number, $decimals = 1) { // decimals: 0=never, 1=if needed, 2=always
    $formattedNumber = $number;
    if (is_numeric($number)) {
        if (!$number) { // zero
            $formattedNumber = ($decimals == 2 ? '0.00' : '0');
        } else {
            if (floor($number) == $number) { // whole number
                $formattedNumber = number_format($number, ($decimals == 2 ? 2 : 0), ',', '.');
            } else {
                $formattedNumber = number_format(round($number, 2), ($decimals == 0 ? 0 : 2), ',', '.');
            }
        }
    }
    return $formattedNumber;
}
