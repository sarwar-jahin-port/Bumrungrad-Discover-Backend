<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicalConsultancyRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Http\Traits\NotifiesAdmin;
use App\Models\MedicalConsultancy;

class MedicalConsultancyController extends Controller
{
    use HandlesFileUploads, NotifiesAdmin;

    public function store(MedicalConsultancyRequest $request)
    {
        $data = $request->validated();
        $relPath = 'assets/docs/medical-treatment/';
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');

        MedicalConsultancy::create($data);

        $attachments = $data['passport'] ? [public_path($relPath . basename($data['passport']))] : [];
        $this->notifyAdmin('Medical Treatment', $data, $attachments);

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
