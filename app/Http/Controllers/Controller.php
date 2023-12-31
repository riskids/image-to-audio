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
        $txt = $helperService->detectText($img);
        $audio = $helperService->textToSpeech($txt[0]['fullTextAnnotation']['text']);
        return response()->json([
            'message' => 'Detection Success',
            'base64Audio' => $audio->json()['audioContent'],
        ]);
    }
}
