<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Throwable;

class ImageService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function uploadImage(UploadedFile $file)
    {
        // Store Image locally
        $path = Storage::disk('public')->put('products', $file);
        return url(Storage::url($path));
    }

    public function getPlaceholderImage($productName)
    {
        // External API to obtain an image
        try {
            $response = $this->client->get('https://picsum.photos/800/600');
            $imageName = 'products/' . md5($productName . time()) . '.jpg';

            Storage::disk('public')->put($imageName, $response->getBody());

            return url(Storage::url($imageName));
        } catch (Throwable $e) {
            report($e);
            // Fallback just in case the API fails (fake)
            return 'https://via.placeholder.co/800x600?text=' . urlencode($productName);
        }
    }
}
