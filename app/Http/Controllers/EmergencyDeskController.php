<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmergencyDeskRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\EmergencyDesk;

class EmergencyDeskController extends Controller
{
    use NotifiesAdmin;

    public function store(EmergencyDeskRequest $request)
    {
        $data = $request->validated();
        EmergencyDesk::create($data);
        $this->notifyAdmin('Emergency Desk', $data);
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
