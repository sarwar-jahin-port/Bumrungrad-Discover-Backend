<?php

namespace App\Http\Controllers;

use App\Http\Requests\FreeConsultationRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\FreeConsultation;

class FreeConsultationController extends Controller
{
    use NotifiesAdmin;

    public function store(FreeConsultationRequest $request)
    {
        $data = $request->validated();
        FreeConsultation::create($data);
        $this->notifyAdmin('Free Consultation', $data);
        return response()->json(['status' => 200, 'msg' => 'Thank you. Our team will reach out to you shortly.']);
    }

    public function index()
    {
        $consultations = FreeConsultation::latest()->get();
        if ($consultations->count() > 0) {
            return response()->json(['status' => 200, 'data' => $consultations]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
