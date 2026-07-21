<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\News;

class NewsController extends Controller
{
    use HandlesFileUploads;

    public function store(NewsRequest $request)
    {
        $data = $request->safe()->except('newsImage');
        $data['newsImage'] = $this->upload_file($request, 'assets/images/news/', 'newsImage');

        News::create($data);
        return response()->json(['status' => 200, 'msg' => 'News added.']);
    }

    public function update(UpdateNewsRequest $request, $id)
    {
        $news = News::where('id', $id)->first();
        if (!$news) {
            return response()->json(['status' => 404, 'msg' => 'News not found.']);
        }

        $data = $request->safe()->except('newsImage');
        if ($request->hasFile('newsImage')) {
            $data['newsImage'] = $this->upload_file($request, 'assets/images/news/', 'newsImage');
        }

        $news->update($data);
        return response()->json(['status' => 200, 'msg' => 'News updated.']);
    }

    public function index($id = '')
    {
        if ($id != '' && is_numeric($id)) {
            $news = News::where('id', $id)->first();
            if ($news) {
                return response()->json(['status' => 200, 'data' => $news]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $news = News::latest()->get();
        if ($news->count() > 0) {
            return response()->json(['status' => 200, 'data' => $news]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function destroy($id)
    {
        $news = News::where('id', $id)->first();
        if (!$news) {
            return response()->json(['status' => 404, 'msg' => 'News not found.']);
        }
        $news->delete();
        return response()->json(['status' => 200, 'msg' => 'News deleted.']);
    }
}
