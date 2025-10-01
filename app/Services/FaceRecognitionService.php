<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FaceRecognitionService
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = 'http://127.0.0.1:5000/api/compare-faces';
    }

    /**
     * Compare faces using Python Flask API
     */
    public function compareFacesViaApi($imageBase64, $image2Path)
    {
        try {
            Log::info('Starting Python face recognition', [
                'profile_image_path' => $image2Path,
                'base64_length' => strlen($imageBase64)
            ]);

            $client = new \GuzzleHttp\Client();

            $response = $client->post($this->apiUrl, [
                'json' => [
                    'image1' => $imageBase64,
                    'image2_path' => $image2Path
                ],
                'timeout' => 30,
                'connect_timeout' => 10
            ]);

            $result = json_decode($response->getBody(), true);

            Log::info('Python Face Recognition response', ['result' => $result]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Python Face Recognition API error', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Check if Python service is healthy
     */
    public function healthCheck()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('http://127.0.0.1:5000/api/health', [
                'timeout' => 5,
                'connect_timeout' => 3
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage()
            ];
        }
    }
}
