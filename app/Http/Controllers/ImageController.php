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
        // echo '<pre>';
        // var_dump($request->all());
        // die();
        $disk = Storage::disk('public'); // Change 's3' to your actual disk name
        $url = "{$domain}/{$filepath}";
        $extension = pathinfo($url, PATHINFO_EXTENSION);
        $filename = 'image/'.md5($url).'.'.$extension;

        if ($disk->exists($filename)) {
            return $this->returnImage($disk, $filename, ['freeCDN-Cache' => 'HIT']);
        } else {
            $response = Http::get($url);

            if (! $response->successful()) {
                return response()->json(['error' => 'Image could not be fetched'], 400);
            }

            $disk->put($filename, $response->body());

            return $this->applyTransformations($disk, $filename, $request->all());
        }
    }

    private function applyTransformations($disk, $filename, array $params)
    {
        $imageData = $disk->get($filename);
        $image = Image::make($imageData);

        $width = $params['w'] ?? 800;
        $height = $params['h'] ?? 800;
        $image->resize($width, $height);

        $disk->put($filename, (string) $image->encode());

        return $this->returnImage($disk, $filename, ['freeCDN-Cache' => 'MISS']);
    }

    private function returnImage($disk, $filename, $headersArray = [])
    {
        if (! $disk->exists($filename)) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        $mimeType = $disk->mimeType($filename);
        $header = [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ];
        $headers = array_merge($header, $headersArray);

        return response($disk->get($filename), 200)->withHeaders($headers);
    }
}
