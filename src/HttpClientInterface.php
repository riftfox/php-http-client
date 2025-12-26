<?php

namespace Riftfox\HttpClient;

use Psr\Http\Message\RequestInterface;

interface HttpClientInterface
{
    public function send(RequestInterface $request, ResultFactoryInterface $resultFactory): ResultInterface;
}