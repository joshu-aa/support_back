<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpClient\HttpClient;

class HTTPClientService
{
    const HTTP_METHOD_GET = "GET";
    const HTTP_METHOD_POST = "POST";

    public function getDataFromEndPoint(string $httpMethod, string $endPoint, array $options = []): array
    {
        $client = HttpClient::create();
        $response = $client->request($httpMethod, $endPoint, $options);
        $headers = array_change_key_case($response->getHeaders(), CASE_LOWER);
        if (!($response->getStatusCode() == 200 || $response->getStatusCode() == 201) ) {
            throw new Exception("Endpoint \"$endPoint\" returned a " . $response->getStatusCode() . " status code.");
        }
        if (!in_array("application/json", $headers["content-type"])) {
            throw new Exception("Response content type only supported is application/json.");
        }

        return $response->toArray();
    }
}
