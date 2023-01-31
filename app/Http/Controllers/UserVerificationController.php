<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserVerificationController extends Controller
{
    public function getStatus()
    {
        $user = UserVerification::where('user_id', auth()->id())->first();
        if ($user && $user->verification_status == 1) {
            return get_success_response(['msg' => 'User as been successfully verified', 'data' => $user]);
        }
        return get_error_response(["error" => "User has not been verified or verification not successful"]);
    }


    /**
     * Receive verification documents from customer.
     */
    public function verify(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'nationality'   =>  'required',
                'firstName'     =>  'required|min:3',
                'lastName'      =>  'required|min:3',
                'middleName'    =>  'nullable',
                'dateOfBirth'   =>  'required',
                'idType'        =>  'required',
                'backPage'      =>  'nullable|image:jpg, jpeg, png, bmp, gif, svg, or webp',
                'frontPage'     =>  'required|image:jpg, jpeg, png, bmp, gif, svg, or webp',
                'selfieImg'     =>  'required|image:jpg, jpeg, png, bmp, gif, svg, or webp'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        $saveData = UserVerification::create([
            'nationality'   =>  $request->nationality,
            'firstName'     =>  $request->firstName,
            'lastName'      =>  $request->lastName,
            'middleName'    =>  $request->middleName,
            'dateOfBirth'   =>  $request->dateOfBirth,
            'idType'        =>  $request->idType,
            'backPage'      =>  save_image('verification', $request->file('backPage')),
            'frontPage'     =>  save_image('verification', $request->file('frontPage')),
            'selfieImg'     =>  save_image('verification', $request->file('selfieImg')),
        ]);

        if($saveData):
            return get_success_response(["msg" => "Property added successfully"]);
        else: 
            return get_error_response(["error" => "Unable to add property"], 400);
        endif;
    }
}
