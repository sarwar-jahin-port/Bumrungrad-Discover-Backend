<?php

namespace App\Http\Traits;

use Illuminate\Support\Str;

trait HandlesFileUploads
{
    public function upload_file($file, $path, $type)
    {
        $url = NULL;
        if ($file->hasFile($type)) {
            $doc = $file->$type;
            $extension = $file->file($type)->getClientOriginalExtension();
            $doc_name = $type . '-' . time() . Str::random(10) . '.' . $extension;
            $doc->move(public_path($path), $doc_name);
            $url = asset($path . $doc_name);
        }
        return $url;
    }
}
