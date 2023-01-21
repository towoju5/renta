<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DojaController extends Controller
{

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
        ], [
            'phone.required' => 'Phone Number is required!',
            'phone.numeric' => 'Invalid Phone Number provided!',
        ]);

        $resp = [
            'channel'       => 'whatsapp',
            'sender_id'     => getenv("DOJA_SENDER_ID"),
            'destination'   => $request->phone
        ];

        $result = $this->process("messaging/otp", "POST", $resp);

        if (array_key_exists('entity', $result) && !empty($result)) {
            $data = [
                'status'    => true,
                'code'      =>  http_response_code(),
                'message'   =>  "Verification code Sent Successfully",
                'data'      =>  to_array($result)
            ];
        } else {
            $data = get_error_response($result, 400);
        }
        return response()->json($data);
    }

    public function validate_otp(Request $request)
    {
        $request->validate([
            'reference_id' => 'required',
            'code'  =>  'required'
        ]);

        $endpoint = "messaging/otp/validate?code=$request->code&reference_id=$request->reference_id";

        $result = $this->process($endpoint, "GET");

        if (!empty($result) && array_key_exists('valid', $result['entity'])) {
            $mainResult = to_array($result['entity']);
            if ($mainResult['valid'] == true) {
                $data = [
                    'status'    => 'success',
                    'code'      =>  http_response_code(),
                    'message'   =>  "O.T.P verified Successfully",
                    'data'      =>  to_array($result)
                ];
            }
        } else {
            $data = get_error_response($result)->original;
        }

        return response()->json($data);
    }

    public function process($endpoint, $method = 'GET', array $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, getenv('DOJA_URL') . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $headers = array();
        $headers[] = 'Appid: ' . getenv("DOJA_APP_ID");
        $headers[] = 'Content-Type: application/x-www-form-urlencoded';
        $headers[] = 'Authorization: ' . getenv("DOJA_PRIVATE_KEY");

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = to_array(curl_exec($ch));
        curl_close($ch);
        return json_decode($result, true);
    }
}
