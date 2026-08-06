<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundTransferRequest;
use App\Http\Traits\NotifiesAdmin;
use App\Models\FundTransfer;

class FundTransferController extends Controller
{
    use NotifiesAdmin;

    public function store(FundTransferRequest $request)
    {
        $data = $request->validated();
        FundTransfer::create($data);
        $this->notifyAdmin('Fund Transfer', $data);
        return response()->json(['status' => 200, 'msg' => 'Fund transfer request created.']);
    }

    public function index()
    {
        $requests = FundTransfer::latest()->get();
        if ($requests->count() > 0) {
            return response()->json(['status' => 200, 'data' => $requests]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
