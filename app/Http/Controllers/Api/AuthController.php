<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return get_error_response($validateUser->errors(), 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
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
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email'         =>  'required|email|unique:users,email',
                    'password'      =>  'required|min:6',
                    'country'       =>  'required',
                    'firstName'     =>  'required|min:3',
                    'lastName'      =>  'required|min:3',
                    'device_id'     =>  'required|unique:users,device_id',
                    'phoneNumber'   =>  'required|unique:users,phoneNumber',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $phone = format_phone($request->phoneNumber, $request->country);
            $checkphoneNumber = User::where("phoneNumber", $phone)->count();
            if ($checkphoneNumber > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
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
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email'         =>  'required|email|unique:users,email',
                    'password'      =>  'required|min:6',
                    'country'       =>  'required',
                    'firstName'     =>  'required|min:3',
                    'lastName'      =>  'required|min:3',
                    'phoneNumber'   =>  'required|unique:users,phoneNumber',
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::where('id', $request->user()->id)->first();
            if (!empty($user)) :
                $user->email         =  $request->email;
                $user->country       =  $request->country;
                $user->firstName     =  $request->firstName;
                $user->lastName      =  $request->lastName;
                $user->phoneNumber   =  $request->phoneNumber;
                $user->name          =  "$request->firstName $request->lastName";
                if ($request->has('password')) :
                    $user->password      =  Hash::make($request->password);
                endif;
                if ($request->has('profile_image')) :
                    $user->profile_image      =  save_image('profile', $request->profile_image);
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

    public function getUser()
    {
        $user = User::where('id', request()->user()->id)->first();
        return get_success_response($user);
    }

    /**
     * Receive user email address and generate reset token
     */
    public function forgot_password(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status) {
            return get_success_response(['status' => "Password reset token successfully sent to your mail"]);
        }
        return get_error_response(['email' => __($status)]);
    }

    /**
     * Verify if reset token is valid and reset user password
     */
    public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if($status) {
            return get_success_response(['msg' => "Password updated successfully"]);
        }
        return get_error_response(["error" => "Unable to update password, please contact support"]);
    }
}
