<?php

namespace App\Http\Controllers\Traits;

use App\Models\Media;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;

trait FileUploadTrait
{

    /**
     * File upload trait used in controllers to upload files
     */
    public function saveImage($image, $type = 'upload', $thumb = false, $max_width = null, $max_height = null)
    {
        ini_set('memory_limit', '-1');
        if (!file_exists(public_path('storage/uploads'))) {
            mkdir(public_path('storage/uploads'), 0777);
            mkdir(public_path('storage/uploads/thumb'), 0777);
        }

        if($max_width != null && $max_height != null) {
            
            // Check file width
            $extension = array_last(explode('.', $image->getClientOriginalName()));
            $name = array_first(explode('.', $image->getClientOriginalName()));
            $filename = time() . '-' . str_slug($name) . '.' . $extension;
            $file = $image;
            $image = Image::make($file);
            if (!file_exists(public_path('storage/uploads/thumb'))) {
                mkdir(public_path('storage/uploads/thumb'), 0777, true);
            }

            if($thumb) {
                Image::make($file)->resize(64, 64)->save(public_path('storage/uploads/thumb') . '/' . $filename);
            }

            $width = $image->width();
            $height = $image->height();
            if ($width > $max_width && $height > $max_height) {
                $image->resize($max_width, $max_height);
            } elseif ($width > $max_width) {
                $image->resize($max_width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } elseif ($height > $max_width) {
                $image->resize(null, $max_height, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->save(public_path('storage/uploads') . '/' . $filename);
        } else {
            $extension = array_last(explode('.', $image->getClientOriginalName()));
            $name = array_first(explode('.', $image->getClientOriginalName()));
            $filename = time() . '-' . str_slug($name) . '.' . $extension;

            if($thumb) {
                Image::make($image)->resize(64, 64)->save(public_path('storage/uploads/thumb') . '/' . $filename);
            }

            if($type == 'upload') {
                $image->move(public_path('storage/uploads'), $filename);
            } else if($type == 'avatar') {
                $image->move(public_path('storage/avatars'), $filename);
            }
        }

        return $filename;
    }

    public function saveLogos(Request $request)
    {
        if (!file_exists(public_path('storage/logos'))) {
            mkdir(public_path('storage/logos'), 0777);
        }
        $finalRequest = $request;

        foreach ($request->all() as $key => $value) {
            if ($request->hasFile($key)) {
                $extension = Arr::array_last(explode('.', $request->file($key)->getClientOriginalName()));
                $name = Arr::array_first(explode('.', $request->file($key)->getClientOriginalName()));
                $filename = time() . '-' . str_slug($name) . '.' . $extension;
                $request->file($key)->move(public_path('storage/logos'), $filename);
                $finalRequest = new Request(array_merge($finalRequest->all(), [$key => $filename]));
            }
        }

        return $finalRequest;
    }

    public function saveFile($file)
    {
        if (!file_exists(public_path('storage/attachments'))) {
            mkdir(public_path('storage/attachments'), 0777);
        }

        $extension = array_last(explode('.', $file->getClientOriginalName()));
        $name = array_first(explode('.', $file->getClientOriginalName()));
        $filename = time() . '-' . str_slug($name) . '.' . $extension;
        $file->move(public_path('storage/attachments'), $filename);

        return $filename;
    }
}