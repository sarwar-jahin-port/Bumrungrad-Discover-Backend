<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalConsultancyRequest;
use App\Models\MedicalConsultancy;

class MedicalConsultancyController extends Controller
{
    public function store(MedicalConsultancyRequest $request)
    {
        MedicalConsultancy::create($request->validated());

        return response()->json(['status' => 200, 'msg' => 'Medical consultancy request created.']);
    }

    public function index($id = '')
    {
        if ($id !== '') {
            $consultancy = MedicalConsultancy::where('id', $id)->first();
            if ($consultancy) {
                return response()->json(['status' => 200, 'data' => $consultancy]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $consultancies = MedicalConsultancy::latest()->get();
        if ($consultancies->count() > 0) {
            return response()->json(['status' => 200, 'data' => $consultancies]);
        }

        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
