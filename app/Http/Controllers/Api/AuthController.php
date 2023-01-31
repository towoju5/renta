<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return get_error_response($validateUser->errors(), 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return get_error_response([
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status'    => true,
                'message'   => 'User Logged In Successfully',
                'data'      => $user
            ], 200);

        } catch (\Throwable $th) {
            return get_error_response($th->getMessage(), 500);
        }
    }

    public function register(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'email'         =>  'required|email|unique:users,email',
                'password'      =>  'required|min:6',
                'country'       =>  'required',
                'firstName'     =>  'required|min:3',
                'lastName'      =>  'required|min:3',
                'device_id'     =>  'required|unique:users,device_id',
                'phoneNumber'   =>  'required|unique:users,phoneNumber',
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $phone = format_phone($request->phoneNumber, $request->country);
            $checkphoneNumber = User::where("phoneNumber", $phone)->count();
            if($checkphoneNumber > 0){
                return response()->json([
                    'status' => false,
                    'message'=> 'validation error',
                    'errors' => [
                        "phone" => [
                            "Phone Number Already Exists"
                        ]
                    ]
                ], 401);
            }

            $user = User::create([
                "name"          =>  "$request->firstName $request->lastName",
                "country"       =>  $request->country,
                "device_id"     =>  $request->device_id,
                "firstName"     =>  $request->firstName,
                "lastName"      =>  $request->lastName,
                "email"         =>  $request->email,
                "phoneNumber"   =>  $phone,
                "password"      =>  Hash::make($request->password)
            ]);
            $apiToken = $user->createToken("ACCESS_TOKEN")->plainTextToken;
            $user->api_token = get_api_token($apiToken);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'User Created Successfully',
                'data'    => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make($request->all(), 
            [
                'email'         =>  'required|email|unique:users,email',
                'password'      =>  'required|min:6',
                'country'       =>  'required',
                'firstName'     =>  'required|min:3',
                'lastName'      =>  'required|min:3',
                'phoneNumber'   =>  'required|unique:users,phoneNumber',
                'profile_image' =>  'nullable|image:jpg, jpeg, png, bmp, gif, svg, or webp'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('id', $request->user()->id)->first();
            if(!empty($user)):
                if($request->has('email') && !empty($request->email)):
                    $user->email         =  $request->email;
                endif;
                if($request->has('country') && !empty($request->country)):
                    $user->country       =  $request->country;
                endif;
                if($request->has('address') && !empty($request->address)):
                    $user->address       =  $request->address;
                endif;
                if($request->has('firstName') && !empty($request->firstName)):
                    $user->firstName     =  $request->firstName;
                endif;
                if($request->has('lastName') && !empty($request->lastName)):
                    $user->lastName      =  $request->lastName;
                endif;
                if($request->has('phoneNumber') && !empty($request->phoneNumber)):
                    $user->phoneNumber   =  $request->phoneNumber;
                endif;
                if($request->has('firstName') && !empty($request->firstName)):
                    $user->name          =  "$request->firstName $request->lastName";
                endif;
                if($request->has('password') && !empty($request->password)):
                    $user->password      =  Hash::make($request->password);
                endif;
                $user->save();
            endif;

            return response()->json([
                'status'    => true,
                'message'   => 'User updated Successfully',
                'data'      => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get user wallet balance.
     */

     public function balance()
     {
        $user = User::findorFail(auth()->id());
        $wallet = $user->getWallet("NGN");
        $result = [
            "currency"  =>  $wallet->currency,
            "balance"   =>  number_format($wallet->balance, 2)
        ];
        return get_success_response($result);
     }

    /**
     * Receive user email address and generate reset token
     */
    public function forgot_password()
    {
        //
    }

    /**
     * Verify if reset token is valid and reset user password
     */
    public function reset_password()
    {
        //
    }
}