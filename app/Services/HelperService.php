<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
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
}