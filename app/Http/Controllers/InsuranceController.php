<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsuranceProviderRequest;
use App\Http\Requests\UpdateInsuranceProviderRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\InsuranceProvider;

class InsuranceController extends Controller
{
    use HandlesFileUploads;

    public function store(InsuranceProviderRequest $request)
    {
        $data = $request->safe()->except('logo');
        $data['logo'] = $this->upload_file($request, 'assets/images/insurance/', 'logo');

        InsuranceProvider::create($data);
        return response()->json(['status' => 200, 'msg' => 'Insurance provider added.']);
    }

    public function update(UpdateInsuranceProviderRequest $request, $id)
    {
        $provider = InsuranceProvider::where('id', $id)->first();
        if (!$provider) {
            return response()->json(['status' => 404, 'msg' => 'Insurance provider not found.']);
        }

        $data = $request->safe()->except('logo');
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->upload_file($request, 'assets/images/insurance/', 'logo');
        }

        $provider->update($data);
        return response()->json(['status' => 200, 'msg' => 'Insurance provider updated.']);
    }

    public function index()
    {
        $providers = InsuranceProvider::orderBy('category')->orderBy('id')->get();
        if ($providers->count() > 0) {
            return response()->json(['status' => 200, 'data' => $providers]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function destroy($id)
    {
        $provider = InsuranceProvider::where('id', $id)->first();
        if (!$provider) {
            return response()->json(['status' => 404, 'msg' => 'Insurance provider not found.']);
        }
        $provider->delete();
        return response()->json(['status' => 200, 'msg' => 'Insurance provider deleted.']);
    }
}
