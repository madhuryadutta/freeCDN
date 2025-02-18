<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Symfony\Component\Mime\MimeTypes;

class FileController extends Controller
{
    public function serveFile($user, $repo, $tag, $file)
    {
        // Build the URL from the provided parameters
        $fileUrl = $this->getFileUrl($user, $repo, $tag, $file);

        // Check for file size limit (30MB)
        if ($this->getFileSize($fileUrl) > 30 * 1024 * 1024) {
            return response()->json(['error' => 'File size exceeds the 30MB limit'], 413);
        }

        // Cache control logic
        $cacheTime = $this->getCacheTime($tag);

        // Set headers based on content type
        $headers = $this->getHeaders($fileUrl, $cacheTime);

        // Return the file with appropriate headers
        return response()->stream(function () use ($fileUrl) {
            echo file_get_contents($fileUrl);
        }, 200, $headers);
    }

    private function getFileUrl($user, $repo, $tag, $file)
    {
        // Depending on the service (GitHub, GitLab, Bitbucket), construct the URL
        // Here we assume that the file is hosted on raw.githubusercontent.com, gitlab.com, or bitbucket.org
        $baseUrl = $this->getBaseUrl($user, $repo, $tag);

        return $baseUrl . '/' . $file;
    }

    private function getBaseUrl($user, $repo, $tag)
    {
        // Choose the right base URL depending on the service
        if (request()->is('gh/*')) {

            return "https://raw.githubusercontent.com/{$user}/{$repo}/{$tag}";
        } elseif (request()->is('gl/*')) {
            return "https://gitlab.com/{$user}/{$repo}/-/raw/{$tag}";
        } elseif (request()->is('bb/*')) {
            return "https://bitbucket.org/{$user}/{$repo}/raw/{$tag}";
        }

        return '';
    }

    private function getFileSize($fileUrl)
    {
        // Get the file size from the URL
        $headers = get_headers($fileUrl, 1);
        return isset($headers['Content-Length']) ? (int) $headers['Content-Length'] : 0;
    }

    private function getCacheTime($tag)
    {
        // Cache for 1 day for specific branches, else 1 year
        $shortCacheBranches = ['main', 'master', 'dev', 'develop', 'gh-pages'];
        if (in_array($tag, $shortCacheBranches)) {
            return 60 * 60 * 24; // 1 day
        }
        return 60 * 60 * 24 * 365; // 1 year
    }
    private function getHeaders($fileUrl, $cacheTime)
    {
        // Use Laravel's File class to guess the MIME type based on the file extension
        $headers = [];
        $mimeType = $this->getMimeType($fileUrl);
        $headers['Content-Type'] = $mimeType;

        // Handle Cache-Control
        $headers['Cache-Control'] = 'public, max-age=' . $cacheTime;

        return $headers;
    }

    private function getMimeType($fileUrl)
    {
        // Attempt to get the MIME type based on the file extension
        $fileInfo = pathinfo($fileUrl);
        $extension = $fileInfo['extension'] ?? '';

        // Check MIME type based on extension using Symfony MimeTypeGuesser
        $mimeTypes = new MimeTypes();
        $mimeType = $mimeTypes->getMimeTypes($extension)[0] ?? null;

        // Fallback to the default MIME type if not found
        if (!$mimeType) {
            $mimeType = 'application/octet-stream'; // Generic binary stream type
        }

        return $mimeType;
    }

}
