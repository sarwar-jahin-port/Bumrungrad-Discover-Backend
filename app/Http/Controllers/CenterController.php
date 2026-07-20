<?php

namespace App\Http\Controllers;

use App\Http\Requests\CenterRequest;
use App\Http\Requests\UpdateCenterRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\Center;
use Illuminate\Support\Str;

class CenterController extends Controller
{
    use HandlesFileUploads;

    public function store(CenterRequest $request)
    {
        $data = $request->safe()->except(['cover_photo', 'floor_map']);
        $data['cover_photo'] = $this->upload_file($request, 'assets/images/centers/', 'cover_photo');
        $data['floor_map'] = $this->upload_file($request, 'assets/images/centers/', 'floor_map');
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['name']);

        Center::create($data);
        return response()->json(['status' => 200, 'msg' => 'Clinic added.']);
    }

    public function update(UpdateCenterRequest $request, $id)
    {
        $data = $request->safe()->except(['cover_photo', 'floor_map']);
        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $this->upload_file($request, 'assets/images/centers/', 'cover_photo');
        }
        if ($request->hasFile('floor_map')) {
            $data['floor_map'] = $this->upload_file($request, 'assets/images/centers/', 'floor_map');
        }
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['name']);

        $center = Center::where('id', $id)->first();
        if (!$center) {
            return response()->json(['status' => 404, 'msg' => 'Center not found.']);
        }
        $center->update($data);
        return response()->json(['status' => 200, 'msg' => 'Clinic updated.']);
    }

    private function decodeJsonFields(Center $center)
    {
        $center->informations = json_decode($center->informations);
        $center->conditions = json_decode($center->conditions);
        $center->treatments = json_decode($center->treatments);
        return $center;
    }

    public function index($slug = '', $id = '')
    {
        // Legacy callers pass a numeric id in the second segment.
        if ($id !== '' && is_numeric($id)) {
            $center = Center::where('id', $id)->first();
            if ($center) {
                return response()->json(['response' => ['status' => 200, 'data' => $this->decodeJsonFields($center)]]);
            }
            return response()->json(['response' => ['status' => 404, 'msg' => 'Data not found.']]);
        }

        // Single segment provided: treat it as the slug identifier.
        if ($slug !== '') {
            $center = Center::where('slug', $slug)->first();
            if (!$center) {
                // Fall back to a name-derived slug for legacy rows saved before
                // the slug field existed / was populated.
                $center = Center::get()->first(fn ($c) => Str::slug($c->name) === $slug);
            }
            if ($center) {
                return response()->json(['response' => ['status' => 200, 'data' => $this->decodeJsonFields($center)]]);
            }
            return response()->json(['response' => ['status' => 404, 'msg' => 'Data not found.']]);
        }

        // No identifier: return the full list.
        $centers = Center::latest()->get();
        if ($centers->count() > 0) {
            $centers->each(function ($item) {
                $item->slug = $item->slug ?: Str::slug($item->name);
                $item->informations = json_decode($item->informations);
                $item->conditions = json_decode($item->conditions);
                $item->treatments = json_decode($item->treatments);
            });
            return response()->json(['response' => ['status' => 200, 'data' => $centers]]);
        }
        return response()->json(['response' => ['status' => 404, 'msg' => 'Data not found.']]);
    }

    public function search($name)
    {
        $data = ['status' => 404, 'msg' => 'Data not found.'];
        if ($name != '') {
            $result = Center::where('name', 'LIKE', "$name%")->get();
            if ($result->count() > 0) {
                $data = ['status' => 200, 'data' => $result];
            }
        }
        return response()->json($data);
    }
}
