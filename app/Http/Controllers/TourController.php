<?php

namespace App\Http\Controllers;

use App\Models\TourBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TourController extends Controller
{
    public function book_tour(Request $request)
    {
        $validators = Validator::make($request->all(), [
            'date_time' => 'required'
        ]);

        if($validators->fails()) {
            return $validators->errors();
        }

        $booking = TourBooking::create([
            'user_id'   =>  $request->date_time
        ]);

        return get_success_response([
            "msg"   =>  "slot booked successfully",
            "data"  =>  $booking
        ]);
    }
}
