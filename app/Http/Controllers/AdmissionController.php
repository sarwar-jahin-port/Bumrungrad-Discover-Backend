<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmissionRequest;
use App\Models\Admission;

class AdmissionController extends Controller
{
    public function store(AdmissionRequest $request)
    {
        Admission::create($request->validated());

        return response()->json(['status' => 200, 'msg' => 'Admission request created.']);
    }

    public function index($id = '')
    {
        if ($id !== '') {
            $admission = Admission::where('id', $id)->first();
            if ($admission) {
                return response()->json(['status' => 200, 'data' => $admission]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $admissions = Admission::latest()->get();
        if ($admissions->count() > 0) {
            return response()->json(['status' => 200, 'data' => $admissions]);
        }

        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
