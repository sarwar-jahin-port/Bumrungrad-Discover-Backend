<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Http\Requests\SubPackageRequest;
use App\Http\Requests\UpdatePackageRequest;
use App\Http\Requests\UpdateSubPackageRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\Package;
use App\Models\SubPackage;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    use HandlesFileUploads;

    private const PACKAGE_PHOTO_PATH = 'assets/images/packages/';
    private const SUB_PACKAGE_PHOTO_PATH = 'assets/images/sub_packages/';

    public function store(PackageRequest $request)
    {
        $data = $request->safe()->except('cover_photo');
        $data['cover_photo'] = $this->upload_file($request, self::PACKAGE_PHOTO_PATH, 'cover_photo');
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);

        Package::create($data);
        return response()->json(['status' => 200, 'msg' => 'Package added.']);
    }

    public function update(UpdatePackageRequest $request, $id)
    {
        $package = Package::where('id', $id)->first();
        if (!$package) {
            return response()->json(['status' => 404, 'msg' => 'Package not found.']);
        }

        $data = $request->safe()->except('cover_photo');
        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $this->upload_file($request, self::PACKAGE_PHOTO_PATH, 'cover_photo');
        }
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);

        $package->update($data);
        return response()->json(['status' => 200, 'msg' => 'Package updated.']);
    }

    public function index($slug = '', $id = '')
    {
        if ($id !== '' && is_numeric($id)) {
            $package = Package::where('id', $id)->first();
            if ($package) {
                return response()->json(['status' => 200, 'data' => $package]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        if ($slug !== '') {
            $package = Package::where('slug', $slug)->first();
            if (!$package) {
                $package = Package::get()->first(fn ($p) => Str::slug($p->title) === $slug);
            }
            if ($package) {
                return response()->json(['status' => 200, 'data' => $package]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $packages = Package::latest()->get();
        if ($packages->count() > 0) {
            $packages->each(function ($item) {
                $item->slug = $item->slug ?: Str::slug($item->title);
            });
            return response()->json(['status' => 200, 'data' => $packages]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function search($name)
    {
        $data = ['status' => 404, 'msg' => 'Data not found.'];
        if ($name != '') {
            $result = Package::where('title', 'LIKE', "$name%")->get();
            if ($result->count() > 0) {
                $data = ['status' => 200, 'data' => $result];
            }
        }
        return response()->json($data);
    }

    public function subStore(SubPackageRequest $request)
    {
        $data = $request->safe()->except('cover_photo');
        $data['cover_photo'] = $this->upload_file($request, self::SUB_PACKAGE_PHOTO_PATH, 'cover_photo');
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);

        SubPackage::create($data);
        return response()->json(['status' => 200, 'msg' => 'Child package added.']);
    }

    public function subUpdate(UpdateSubPackageRequest $request, $id)
    {
        $subPackage = SubPackage::where('id', $id)->first();
        if (!$subPackage) {
            return response()->json(['status' => 404, 'msg' => 'Child package not found.']);
        }

        $data = $request->safe()->except('cover_photo');
        if ($request->hasFile('cover_photo')) {
            $data['cover_photo'] = $this->upload_file($request, self::SUB_PACKAGE_PHOTO_PATH, 'cover_photo');
        }
        $data['slug'] = ($data['slug'] ?? '') ?: Str::slug($data['title']);

        $subPackage->update($data);
        return response()->json(['status' => 200, 'msg' => 'Child package updated.']);
    }

    private function decodeSubPackage(SubPackage $subPackage)
    {
        $subPackage->conditions = json_decode($subPackage->conditions) ?: [];
        $subPackage->inclusions = json_decode($subPackage->inclusions) ?: [];
        $subPackage->exclusions = json_decode($subPackage->exclusions) ?: [];
        return $subPackage;
    }

    public function subIndex($slug = '', $id = '')
    {
        if ($id !== '' && is_numeric($id)) {
            $subPackage = SubPackage::where('id', $id)->first();
            if ($subPackage) {
                return response()->json(['status' => 200, 'data' => $this->decodeSubPackage($subPackage)]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        if ($slug !== '') {
            $subPackage = SubPackage::where('slug', $slug)->first();
            if (!$subPackage) {
                $subPackage = SubPackage::get()->first(fn ($p) => Str::slug($p->title) === $slug);
            }
            if ($subPackage) {
                return response()->json(['status' => 200, 'data' => $this->decodeSubPackage($subPackage)]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $subPackages = SubPackage::latest()->get();
        if ($subPackages->count() > 0) {
            $subPackages->each(function ($item) {
                $item->slug = $item->slug ?: Str::slug($item->title);
                $this->decodeSubPackage($item);
            });
            return response()->json(['status' => 200, 'data' => $subPackages]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    // Sub-packages belonging to one parent package, looked up by the PARENT's
    // slug (not the sub-package's own slug) — the route only ever carries one
    // segment in practice, so this resolves the parent first, then filters.
    public function subByParent($parentSlug)
    {
        $parent = Package::where('slug', $parentSlug)->first();
        if (!$parent) {
            $parent = Package::get()->first(fn ($p) => Str::slug($p->title) === $parentSlug);
        }
        if (!$parent) {
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $subPackages = SubPackage::where('parent_id', $parent->id)->get();
        if ($subPackages->count() > 0) {
            $subPackages->each(function ($item) {
                $item->slug = $item->slug ?: Str::slug($item->title);
                $this->decodeSubPackage($item);
            });
            return response()->json(['status' => 200, 'data' => $subPackages]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }
}
