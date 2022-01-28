<?php

namespace RWC\TwitterStream;

use GuzzleHttp\Client as GuzzleClient;
use Psr\Http\Message\ResponseInterface;

class Connection
{
    protected GuzzleClient $httpClient;

    public function __construct(string $bearerToken)
    {
        $this->httpClient = new GuzzleClient([
            'headers' => [
                'Authorization' => "Bearer {$bearerToken}",
            ],
        ]);
    }

    public function request(string $method, string $uri = '', array $options = []): ResponseInterface
    {
        return $this->httpClient->request($method, $uri, $options);
    }
}
