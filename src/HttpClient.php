<?php

namespace Riftfox\HttpClient;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class HttpClient implements HttpClientInterface
{
    private ClientInterface $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function send(RequestInterface $request, ResultFactoryInterface $resultFactory): ResultInterface
    {
        return $resultFactory->create($this->client->sendRequest($request));
    }
}