<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmergencyDeskRequest;
use App\Models\EmergencyDesk;

class EmergencyDeskController extends Controller
{
    public function store(EmergencyDeskRequest $request)
    {
        EmergencyDesk::create($request->validated());
        return response()->json(['status' => 200, 'msg' => 'Emergency desk request created.']);
    }

    public function index($id = '')
    {
        if ($id !== '') {
            $request = EmergencyDesk::where('id', $id)->first();
            if ($request) {
                return response()->json(['status' => 200, 'data' => $request]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
        $requests = EmergencyDesk::latest()->get();
        if ($requests->count() > 0) {
            return response()->json(['status' => 200, 'data' => $requests]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
