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
            return get_error_response($validators->errors(), 400);
        }

        $booking = TourBooking::create([
            'user_id'   =>  $request->user()->id,
            'date_time' =>  $request->date_time
        ]);

        return get_success_response([
            "msg"   =>  "Slot booked successfully",
            "data"  =>  $booking
        ]);
    }

    public function bookings(Request $request)
    {
        $bookings = TourBooking::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
        return get_success_response($bookings);
    }

    public function delete($id)
    {
        if($slot = TourBooking::destroy([$id])){
            return get_success_response(["msg" => "Slot deleted successfully"]);
        } else {
            return get_error_response(["error" => "Unable to process request"]);
        }
    }
}
