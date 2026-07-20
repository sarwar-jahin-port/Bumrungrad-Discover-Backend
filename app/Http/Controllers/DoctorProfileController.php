<?php

namespace App\Http\Controllers;

use App\Http\Requests\DoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DoctorProfileController extends Controller
{
    use HandlesFileUploads;

    private const PHOTO_PATH = 'assets/images/doctors/';

    public function index()
    {
        $doctors = Doctor::get();
        if ($doctors->count() > 0) {
            $doctors->each(fn ($item) => $this->decodeDoctor($item));
            $data = ['arr' => '$arr', 'data' => $doctors, 'status' => 200];
        } else {
            $data = ['status' => 404, 'msg' => 'Data not found.'];
        }
        return response()->json(['response' => $data]);
    }

    public function store(DoctorRequest $request)
    {
        $data = $this->buildDoctorData($request);
        $data['slug'] = Str::slug($request->name, '-');
        $data['cover_photo'] = $this->upload_file($request, self::PHOTO_PATH, 'cover_photo');

        Doctor::create($data);
        return response()->json(['response' => ['status' => 200, 'msg' => 'Doctor added.']]);
    }

    public function update(UpdateDoctorRequest $request, $id)
    {
        $doctor = Doctor::where('id', $id)->first();
        if (!$doctor) {
            return response()->json(['response' => ['status' => 404, 'msg' => 'Doctor not found.']]);
        }

        $data = $this->buildDoctorData($request);
        $data['slug'] = Str::slug($request->name, '-');
        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $this->upload_file($request, self::PHOTO_PATH, 'cover_photo');
        }

        $doctor->update($data);
        return response()->json(['response' => ['status' => 200, 'msg' => 'Doctor updated.']]);
    }

    public function search(Request $request)
    {
        $search = Doctor::query();
        foreach ($request->all() as $key => $query) {
            if ($query == '') {
                continue;
            }
            if (in_array($key, ['sub_specialty', 'lang', 'day', 'shift', 'location'])) {
                $search = $this->sendJson($key, $query, $search);
            } elseif ($key == 'name') {
                $search = $search->where($key, 'LIKE', "%$query%");
            } else {
                $search = $search->where($key, $query);
            }
        }

        $doctors = $search->get();
        if ($doctors->count() > 0) {
            $doctors->each(fn ($item) => $this->decodeDoctor($item));
        }

        return response()->json(['status' => 200, 'data' => $doctors, 'query' => $request->all()]);
    }

    public function show($slug)
    {
        $doctor = Doctor::where('slug', $slug)->first();
        if (!$doctor) {
            return response()->json(['response' => ['status' => 404, 'msg' => 'Data not found.']]);
        }

        $this->decodeDoctor($doctor);
        return response()->json(['response' => ['status' => 200, 'data' => $doctor]]);
    }

    private function decodeDoctor(Doctor $doctor)
    {
        $doctor->specialty = Specialty::where('name', $doctor->specialty)->value('name');
        $doctor->schools = json_decode($doctor->schools) ?: [];
        $doctor->certificates = json_decode($doctor->certificates) ?: [];
        $doctor->fellowships = json_decode($doctor->fellowships) ?: [];
        $doctor->experiences = json_decode($doctor->experiences) ?: [];
        $doctor->researches = json_decode($doctor->researches) ?: [];
        $doctor->interests = json_decode($doctor->interests) ?: [];
        $doctor->article = json_decode($doctor->article) ?: [];
        $doctor->trainings = json_decode($doctor->trainings) ?: [];

        $doctor->sub_specialty = $doctor->sub_specialty ? array_values((array) json_decode($doctor->sub_specialty)) : [];
        $doctor->lang = array_values((array) json_decode($doctor->lang));
        $doctor->day = array_values((array) json_decode($doctor->day));
        $doctor->arrival = array_values((array) json_decode($doctor->arrival));
        $doctor->leave = array_values((array) json_decode($doctor->leave));
        $doctor->location = array_values((array) json_decode($doctor->location));
        $doctor->shift = array_values((array) json_decode($doctor->shift));

        return $doctor;
    }

    // Builds the doctor attribute array shared by store/update. The `schedule`
    // input is a 2D array (one row per day: [day, shift, arrival, leave,
    // location]) which gets decomposed into 5 parallel index-keyed JSON
    // columns — this mirrors the shape the rest of the app (get/search/show)
    // already reads, so it's kept as-is rather than introducing a new schema.
    private function buildDoctorData(Request $request): array
    {
        $schedule = json_decode($request->schedule) ?: [];
        $day = $shift = $arrival = $leave = $location = [];
        foreach ($schedule as $row) {
            $day[] = $row[0] ?? null;
            $shift[] = $row[1] ?? null;
            $arrival[] = $row[2] ?? null;
            $leave[] = $row[3] ?? null;
            $location[] = $row[4] ?? null;
        }

        $modify = [
            'day' => $day,
            'shift' => $shift,
            'arrival' => $arrival,
            'leave' => $leave,
            'location' => $location,
            'lang' => $request->lang ? explode(',', $request->lang) : [],
        ];
        if ($request->sub_specialty) {
            $modify['sub_specialty'] = explode(',', $request->sub_specialty);
        }

        $json = $this->makeJson($modify);
        if (!array_key_exists('sub_specialty', $json)) {
            $json['sub_specialty'] = null;
        }

        return [
            'name' => $request->name,
            'specialty' => $request->specialty,
            'sub_specialty' => $json['sub_specialty'],
            'lang' => $json['lang'],
            'gender' => $request->gender,
            'schools' => $request->schools,
            'certificates' => $request->certificates,
            'fellowships' => $request->fellowships,
            'interests' => $request->interests,
            'experiences' => $request->experiences,
            'researches' => $request->researches,
            'article' => $request->article,
            'trainings' => $request->trainings,
            'day' => $json['day'],
            'location' => $json['location'],
            'arrival' => $json['arrival'],
            'leave' => $json['leave'],
            'shift' => $json['shift'],
        ];
    }

    private function makeJson($array)
    {
        $collection = [];
        foreach ($array as $key => $items) {
            $arr = [];
            for ($i = 0; $i < count($items); $i++) {
                $arr[$key . $i] = $items[$i];
            }
            $collection[$key] = count($arr) > 0 ? json_encode($arr) : null;
        }
        return $collection;
    }

    private function sendJson($col, $string, $query)
    {
        $jsonFields = [];
        for ($i = 0; $i < 100; $i++) {
            $field = $col . '->' . $col . $i;
            if (Doctor::where($field, $string)->exists()) {
                $jsonFields[] = $field;
            }
        }

        if (count($jsonFields) > 0) {
            foreach ($jsonFields as $key => $field) {
                $query = $key === 0 ? $query->where($field, $string) : $query->orWhere($field, $string);
            }
        } else {
            $query = $query->where($col, $string);
        }

        return $query;
    }
}
