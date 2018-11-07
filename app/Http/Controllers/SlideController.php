<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;

class SlideController extends Controller
{
    public function image(Slide $slide)
    {
        $slide_image = $slide->getFirstMedia(config('media.collections.images'));

        if (empty($slide_image)) {
            abort(404);
        }

        return response()
            ->file($slide_image->getPath());
    }
}
