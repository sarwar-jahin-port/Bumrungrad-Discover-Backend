<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecialtyRequest;
use App\Http\Requests\SubSpecialtyRequest;
use App\Models\Specialty;
use App\Models\SubSpecialty;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialty = Specialty::orderBy('name', 'ASC')->get();
        if ($specialty->count() > 0) {
            $data = ['data' => $specialty, 'status' => 200];
        } else {
            $data = ['status' => 404, 'msg' => 'Data not found.'];
        }
        return response()->json(['response' => $data]);
    }

    public function store(SpecialtyRequest $request)
    {
        Specialty::create($request->validated());
        return response()->json(['response' => ['status' => 200, 'msg' => 'Specialty added.']]);
    }

    public function subIndex()
    {
        $sub = SubSpecialty::orderBy('sub_specialty', 'ASC')->get();
        if ($sub->count() > 0) {
            $data = ['data' => $sub, 'status' => 200];
        } else {
            $data = ['status' => 404, 'msg' => 'Data not found.'];
        }
        return response()->json(['response' => $data]);
    }

    public function subStore(SubSpecialtyRequest $request)
    {
        $exists = SubSpecialty::where('specialty', $request->specialty)
            ->where('sub_specialty', $request->sub_specialty)
            ->exists();
        if ($exists) {
            return response()->json(['response' => ['status' => 404, 'msg' => $request->sub_specialty . ' already exists.']]);
        }

        SubSpecialty::create($request->validated());
        return response()->json(['response' => ['status' => 200, 'msg' => 'Sub specialty added.']]);
    }

    public function selected($specialty)
    {
        $selected = SubSpecialty::where('specialty', $specialty)->get();
        if ($selected->count() > 0) {
            $data = ['data' => $selected, 'status' => 200];
        } else {
            $data = ['status' => 404, 'msg' => 'Data not found.'];
        }
        return response()->json(['response' => $data]);
    }
}
