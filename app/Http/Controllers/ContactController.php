<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\Contact;

class ContactController extends Controller
{
    use NotifiesAdmin;

    public function store(ContactRequest $request)
    {
        $data = $request->validated();
        Contact::create($data);

        $this->notifyAdmin('Contact Us', $data);

        return response()->json(['status' => 200, 'msg' => 'Contact request created.']);
    }

    public function index()
    {
        $contacts = Contact::latest()->get();
        if ($contacts->count() > 0) {
            return response()->json(['status' => 200, 'data' => $contacts]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
