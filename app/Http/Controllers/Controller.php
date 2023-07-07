<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HelperService;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function imageToAudio(Request $request, HelperService $helperService)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:6000', // Validasi format dan ukuran gambar
        ]);

        $img = $helperService->uploadImages($request->file('images'));
        $helperService->detectText($img);

        return response()->json([
            'message' => 'Upload Success',
            'images' => $img,
        ]);
    }
}
