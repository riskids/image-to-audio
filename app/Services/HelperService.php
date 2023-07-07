<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class HelperService{
    public function uploadImages($images){
        $uploadedImages = [];

        $disk = Storage::disk('gcs');

        foreach ($images as $image) {
            
            $path = $disk->put('images', $image); // Menyimpan gambar ke penyimpanan Azure dengan folder "images"

            $uploadedImages[] = [
                'path' => $path,
                'url' => Storage::disk('gcs')->url($path),
            ];
        }
        
        return $uploadedImages;
    }

    public function detectText($url_images){
        $key = "AIzaSyC1ycTAqt4cox4cdbdqYpZ8hhC1QazjASM";
        $url = "https://vision.googleapis.com/v1/images:annotate";

        $requests = [];
        foreach ($url_images as $image) {
            $request = [
                    "image" => [
                    "source" => [
                        "imageUri" => $image['url']
                    ]
                ],
                "features" => [
                    [
                        "type" => "DOCUMENT_TEXT_DETECTION"
                    ]
                ],
                "imageContext" => [
                    "languageHints" => ["id-t-i0-handwrit"]
                ]
            ];
            $requests[] = $request;
        }
        $params = [
            "requests" => $requests
        ];

        return Http::withHeaders([
            'Content-Type: application/json',
            'Accept' => 'application/json',
            'x-goog-api-key' => $key,
        ])
        ->post($url , $params);
    }
}