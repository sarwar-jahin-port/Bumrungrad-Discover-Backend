<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Traits\HandlesFileUploads;
use App\Models\Blogs;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use HandlesFileUploads;

    public function store(BlogRequest $request)
    {
        $data = $request->safe()->except('blogImage');
        $data['blogImage'] = $this->upload_file($request, 'assets/images/blog/', 'blogImage');

        Blogs::create($data);
        return response()->json(['status' => 200, 'msg' => 'Blogs added.']);
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blogs::where('id', $id)->first();
        if (!$blog) {
            return response()->json(['status' => 404, 'msg' => 'Blog not found.']);
        }

        $data = $request->safe()->except('blogImage');
        if ($request->hasFile('blogImage')) {
            $data['blogImage'] = $this->upload_file($request, 'assets/images/blog/', 'blogImage');
        }

        $blog->update($data);
        return response()->json(['status' => 200, 'msg' => 'Blogs updated.']);
    }

    public function index($slug = '')
    {
        if ($slug !== '') {
            // Legacy callers pass a numeric id.
            if (is_numeric($slug)) {
                $blog = Blogs::where('id', $slug)->first();
            } else {
                $blog = Blogs::where('slug', $slug)->first();
            }
            if ($blog) {
                return response()->json(['status' => 200, 'data' => $blog]);
            }
            return response()->json(['status' => 404, 'msg' => 'Data not found.']);
        }

        $blogs = Blogs::latest()->get();
        if ($blogs->count() > 0) {
            return response()->json(['status' => 200, 'data' => $blogs]);
        }
        return response()->json(['status' => 404, 'msg' => 'Data not found.']);
    }

    public function paginated(Request $request)
    {
        $query = Blogs::latest();
        if ($request->filled('search')) {
            $query->where('blogTitle', 'LIKE', '%' . $request->search . '%');
        }

        return response()->json(['status' => 200, 'data' => $query->paginate(9)->withQueryString()]);
    }

    public function destroy($id)
    {
        $blog = Blogs::where('id', $id)->first();
        if (!$blog) {
            return response()->json(['status' => 404, 'msg' => 'Blog not found.']);
        }
        $blog->delete();
        return response()->json(['status' => 200, 'msg' => 'Blog deleted.']);
    }
}
