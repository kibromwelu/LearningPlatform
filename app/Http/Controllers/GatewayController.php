<?php

// app/Http/Controllers/GatewayController.php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GatewayController extends Controller
{
    private $learningServiceUrl;
    private $jobSeekingServiceUrl;
    private $socialNetworkServiceUrl;

    public function __construct()
    {
        $this->learningServiceUrl = 'http://localhost:8000/api/learning/';
        $this->jobSeekingServiceUrl = 'http://localhost:3003/api/';
        $this->socialNetworkServiceUrl = 'http://localhost:3004/api/';
    }

    public function handleLearningRequest($path, Request $request)
    {
        // dd($path);
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', 'http://localhost:8000/api/gateway/courses/certificate-requests', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        return $response; //Http::send($this->learningServiceUrl . $path, $request);
        $response = Http::withHeaders($request->headers->all())
            ->send($request->method(), "{$this->learningServiceUrl}/{$path}", [
                'query' => $request->query(), // Add query parameters if any
                'json' => $request->all(), // Add JSON body if any
            ]);

        // Return the response from the learning microservice to the client
        return response()->json($response->json(), $response->status());
    }

    public function handleJobSeekingRequest($path, Request $request)
    {
        return $this->forwardRequest($this->jobSeekingServiceUrl . $path, $request);
    }

    public function handleSocialNetworkRequest($path, Request $request)
    {
        return $this->forwardRequest($this->socialNetworkServiceUrl . $path, $request);
    }

    private function forwardRequest($url, Request $request)
    {
        $client = new Client();
        $response = $client->request($request->method(), $url, [
            'json' => $request->all(),
        ]);

        return response()->json(json_decode($response->getBody()->getContents(), true), $response->getStatusCode());
    }
}
