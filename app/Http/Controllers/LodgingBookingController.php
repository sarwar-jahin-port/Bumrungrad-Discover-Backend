<?php

namespace App\Http\Controllers;

use App\Http\Requests\LodgingBookingRequest;
use App\Models\LodgingBooking;

class LodgingBookingController extends Controller
{
    public function store(LodgingBookingRequest $request)
    {
        LodgingBooking::create($request->validated());
        return response()->json(['status' => 200, 'msg' => 'Lodging booking request created.']);
    }

    public function index($id = '')
    {
        if ($id !== '') {
            $booking = LodgingBooking::where('id', $id)->first();
            if ($booking) {
                return response()->json(['status' => 200, 'data' => $booking]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
        $bookings = LodgingBooking::latest()->get();
        if ($bookings->count() > 0) {
            return response()->json(['status' => 200, 'data' => $bookings]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
