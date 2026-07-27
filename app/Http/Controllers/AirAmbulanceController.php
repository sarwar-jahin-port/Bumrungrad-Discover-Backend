<?php

namespace App\Http\Controllers;

use App\Http\Requests\AirAmbulanceHubRequest;
use App\Http\Requests\AirAmbulanceRequest;
use App\Http\Requests\UpdateAirAmbulanceHubRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\AirAmbulance;
use App\Models\AirAmbulanceHub;

class AirAmbulanceController extends Controller
{
    use HandlesFileUploads;

    public function hubIndex()
    {
        $hubs = AirAmbulanceHub::get();
        if ($hubs->count() > 0) {
            return response()->json(['status' => 200, 'data' => $hubs]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function hubStore(AirAmbulanceHubRequest $request)
    {
        AirAmbulanceHub::create($request->validated());
        return response()->json(['status' => 200, 'msg' => 'Hub added.']);
    }

    public function hubUpdate(UpdateAirAmbulanceHubRequest $request, $id)
    {
        $hub = AirAmbulanceHub::where('id', $id)->first();
        if (!$hub) {
            return response()->json(['status' => 404, 'msg' => 'Hub not found.']);
        }
        $hub->update($request->validated());
        return response()->json(['status' => 200, 'msg' => 'Hub updated.']);
    }

    public function hubDestroy($id)
    {
        $hub = AirAmbulanceHub::where('id', $id)->first();
        if (!$hub) {
            return response()->json(['status' => 404, 'msg' => 'Hub not found.']);
        }
        $hub->delete();
        return response()->json(['status' => 200, 'msg' => 'Hub deleted.']);
    }

    public function store(AirAmbulanceRequest $request)
    {
        $data = $request->safe()->except('passport_copy');
        $data['passport_copy'] = $this->upload_file($request, 'assets/docs/air_ambulance/', 'passport_copy');

        AirAmbulance::create($data);
        return response()->json(['status' => 200, 'msg' => 'Air ambulance created.']);
    }

    public function index($id = '')
    {
        if ($id != '') {
            $air_ambulance = AirAmbulance::where('id', $id)->first();
            if ($air_ambulance) {
                return response()->json(['status' => 200, 'data' => $air_ambulance]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $air_ambulance = AirAmbulance::get();
        if ($air_ambulance->count() > 0) {
            return response()->json(['status' => 200, 'data' => $air_ambulance]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function destroy($id)
    {
        $air_ambulance = AirAmbulance::where('id', $id)->first();
        if (!$air_ambulance) {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }
        $air_ambulance->delete();
        return response()->json(['status' => 200, 'msg' => 'Air ambulance request deleted.']);
    }
}
