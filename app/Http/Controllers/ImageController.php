<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function show(Request $request, $domain, $filepath)
    {
        $url = $domain . '/' . $filepath; // Replace with your external file URL
        // Desired file name
        $extension = pathinfo($url, PATHINFO_EXTENSION); // Extract extension
        $filename = md5($url) . '.' . $extension; // Example: "3a5b8d9fabc123.png"
        $path = $filename;
        $response = Http::get($url);
        if ($response->successful()) {
            Storage::put($path, $response->body()); // Save file in storage/app/downloads/

        }
        return $this->applyTransformations($filename, []);
    }
    private function applyTransformations($filename, $params)
    {
        $imagePath = storage_path("app/{$filename}");

        if (isset($params['h'])) {
            $width = $params['h'];
        } else {
            $width = 800;
        }
        if (isset($params['w'])) {
            $height = $params['w'];
        } else {
            $height = 800;
        }
        // Get file extension

        $image_normal = Image::make($imagePath)->resize($height, $width);
        $image_normal = $image_normal->stream();
        Storage::put($filename, $image_normal->__toString());

        return $this->returnToInternet($filename);
    }

    private function returnToInternet($filePath)
    {
        $imagePath = storage_path("app/{$filePath}");
        if (!file_exists($imagePath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        return response()->file($imagePath, [
            'Cache-Control' => 'public, max-age=31536000'
        ]);
    }
}
