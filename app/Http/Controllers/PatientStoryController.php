<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientStoryRequest;
use App\Http\Requests\UpdatePatientStoryRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\PatientStory;

class PatientStoryController extends Controller
{
    use NotifiesAdmin;

    public function store(PatientStoryRequest $request)
    {
        $data = $request->validated();
        $data['status'] = 'pending';

        PatientStory::create($data);
        $this->notifyAdmin('Patient Story', $data);
        return response()->json(['status' => 200, 'msg' => 'Thank you for sharing your story. It will appear once reviewed.']);
    }

    public function index()
    {
        $stories = PatientStory::where('status', 'approved')->latest()->get();
        if ($stories->count() > 0) {
            return response()->json(['status' => 200, 'data' => $stories]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function adminIndex()
    {
        $stories = PatientStory::latest()->get();
        if ($stories->count() > 0) {
            return response()->json(['status' => 200, 'data' => $stories]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function update(UpdatePatientStoryRequest $request, $id)
    {
        $story = PatientStory::where('id', $id)->first();
        if (!$story) {
            return response()->json(['status' => 404, 'msg' => 'Story not found.']);
        }
        $story->update($request->validated());
        return response()->json(['status' => 200, 'msg' => 'Story updated.']);
    }

    public function destroy($id)
    {
        $story = PatientStory::where('id', $id)->first();
        if (!$story) {
            return response()->json(['status' => 404, 'msg' => 'Story not found.']);
        }
        $story->delete();
        return response()->json(['status' => 200, 'msg' => 'Story deleted.']);
    }
}
