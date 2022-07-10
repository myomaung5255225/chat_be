<?php

namespace App\Repositories;

use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Illuminate\Support\Str;
/**
 * Class UploadRepo.
 */
class UploadRepo
{
    /**
     * @return string
     *  Return the model
     */
    public static function upload($requestImage)
    {

        if (Str::startsWith($requestImage, 'data:image')) {
            $image = $requestImage;

            $name = UuidV4::fromDateTime(now()) . '.jpg';

            $path = 'public/photos/' . $name;

            $img = Image::make($image)->resize(320, 320);

            Storage::disk('local')->put($path, $img->encode());

            $url = asset('storage/photos/' . $name);

            return $url;
        } else {
            return $requestImage;
        }
    }
}
