<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageController extends Controller
{
    public function show($domain, $filepath, Request $request)
    {
        $url = "{$domain}/{$filepath}";
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $filename = md5($url).'.'.$extension;

        if (Storage::exists($filename)) {
            return $this->returnImage($filename, ['freeCDN-Cache' => 'HIT']);
        }

        $response = Http::get($url);
        if (! $response->successful()) {
            return response()->json(['error' => 'Image could not be fetched'], 400);
        }

        Storage::put($filename, $response->body());

        return $this->applyTransformations($filename, $request->all());
    }

    private function applyTransformations($filename, array $params)
    {
        $imagePath = storage_path("app/{$filename}");

        $width = $params['w'] ?? 800;
        $height = $params['h'] ?? 800;

        $image = Image::make($imagePath)->resize($width, $height)->stream();
        Storage::put($filename, $image->__toString());

        return $this->returnImage($filename, ['freeCDN-Cache' => 'MISS']);
    }

    private function returnImage($filename, $headersArray = [])
    {
        $imagePath = storage_path("app/{$filename}");

        if (! file_exists($imagePath)) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        $header = ['Cache-Control' => 'public, max-age=31536000'];
        $headers = array_merge(['Cache-Control' => 'public, max-age=31536000'], $headersArray);

        return response()->file($imagePath, $headers);
    }
}
