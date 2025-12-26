<?php

namespace Riftfox\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface ResultFactoryInterface
{
    public function create(ResponseInterface $response): ResultInterface;
}