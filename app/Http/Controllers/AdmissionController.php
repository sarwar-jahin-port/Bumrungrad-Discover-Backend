<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdmissionRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Http\Traits\NotifiesAdmin;
use App\Models\Admission;

class AdmissionController extends Controller
{
    use HandlesFileUploads, NotifiesAdmin;

    public function store(AdmissionRequest $request)
    {
        $data = $request->validated();
        $relPath = 'assets/docs/direct-admission/';
        $data['passport'] = $this->upload_file($request, $relPath, 'passport');

        Admission::create($data);

        $attachments = $data['passport'] ? [public_path($relPath . basename($data['passport']))] : [];
        $this->notifyAdmin('Direct Admission', $data, $attachments);

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
