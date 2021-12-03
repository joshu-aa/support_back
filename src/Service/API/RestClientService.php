<?php

namespace App\Service\API;

use Symfony\Component\HttpClient\HttpClient;

class RestClientService
{
    private $otcUrl;
    
    public function __construct()
    {
        $this->otcUrl = $_ENV['OTC_URL'];

        $this->otcHeaders = [
            'API-KEY' => $_ENV['OTC_TOKEN'],
            'Identity' => $_ENV['OTC_ID'],
            'Content-Type' => 'application/json'
        ];
    }

    public function requestOtc($method, $endpoint, $data)
    {
        $client = HttpClient::create();
        
        try {        
            $response = $client->request($method, $this->otcUrl . $endpoint, ['headers' => $this->otcHeaders,
            'json' => $data]);
            
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 500) {
                return $response->toArray(false);
            } else if ($response->getStatusCode() === 500) {
                return ['error' => 'Server error'];
            } else {
                $data = $response->toArray();
                return $data;
            }
        } catch (\Exception $e) {
            return ['error' => $e];
        }
    }

    public function requestGateway($method, $endpoint, $data)
    {
        $client = HttpClient::create();
        
        try {        
            $response = $client->request($method, $this->gatewayUrl . $endpoint, ["body" => $data]);
            
            if ($response->getStatusCode() >= 400 && $response->getStatusCode() <= 500) {
                return $response->toArray(false);
            } else if ($response->getStatusCode() === 500) {
                return ['error' => 'Server error'];
            } else {
                $data = $response->toArray();
                return $data;
            }
        } catch (\Exception $e) {
            return ['error' => $e];
        }
    }
}