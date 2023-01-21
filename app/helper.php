<?php

use Propaganistas\LaravelPhone\PhoneNumber;


if (!function_exists('to_array')) {
    function to_array($data)
    {
        if (is_array($data)) {
            return $data;
        } else if (is_object($data)) {
            return json_decode(json_encode($data), true);
        } else {
            return json_decode($data, true);
        }
    }
}

if (!function_exists('_getTransactionId')) {
    function _getTransactionId(): string
    {
        return uniqid();
    }
}


if (!function_exists('error_processor')) {
    function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }
        return $err_keeper;
    }
}


if (!function_exists('format_phone')) {
    function format_phone($number, $country): string
    {
        $phone  = PhoneNumber::make($number, $country);
        $result = $phone->formatInternational();
        return str_replace(' ', '', $result);
    }
}

if (!function_exists('get_api_token')) {
    function get_api_token($token): string
    {
        if (empty($token)) {
            return $token;
        }
        $token  = explode('|', $token);
        $result = $token[1];
        return $result;
    }
}


if (!function_exists('get_error_response')) {
    function get_error_response($data, $code = 400)
    {
        $newArr = to_array($data);
        if (is_array($newArr) && in_array("message", $newArr) or isset($newArr['message'])) {
            $msg = $newArr['message'];
            if (is_array($msg)) {
                $msg = $newArr['message'][0];
            }
        } else {
            $msg = "Error encountered";
        }
        if (isset($newArr['statusCode'])) $code = $newArr['statusCode'];
        if ($code == 0 or !is_numeric($code)) $code = 400;
        if (isset($newArr['data'])) $data = $newArr['data'];
        $resp = [
            "status"        =>  false,
            "message"       =>  $msg,
            "error"         =>  $data
        ];
        return response()->json($resp, $code);
    }
}


if (!function_exists('get_success_response')) {
    function get_success_response($data)
    {
        $newArr = to_array($data);
        if (isset($newArr['data'])) $data = $newArr['data'];
        $resp = [
            'status'        =>  true,
            'status_code'   =>  200,
            'message'       =>  "success",
            "data"          =>  $data
        ];
        return response()->json($resp, 200);
    }
}
