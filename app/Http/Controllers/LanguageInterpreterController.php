<?php

namespace App\Http\Controllers;

use App\Http\Requests\LanguageInterpreterRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\LanguageInterpreter;

class LanguageInterpreterController extends Controller
{
    use NotifiesAdmin;

    public function store(LanguageInterpreterRequest $request)
    {
        $data = $request->validated();
        LanguageInterpreter::create($data);
        $this->notifyAdmin('Language Interpreter', $data);
        return response()->json(['status' => 200, 'msg' => 'Language interpreter request created.']);
    }

    public function index()
    {
        $requests = LanguageInterpreter::latest()->get();
        if ($requests->count() > 0) {
            return response()->json(['status' => 200, 'data' => $requests]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
