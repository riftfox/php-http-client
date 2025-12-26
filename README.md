# Riftfox PHP HTTP Client

A lightweight PSR-18 HTTP client wrapper allowing custom response transformation via result factories.

## Requirements

* PHP >= 7.4
* A PSR-18 HTTP Client implementation (e.g., Guzzle, Symfony HttpClient)
* PSR-7 HTTP Message implementation

## Installation

```bash
composer require riftfox/php-http-client
```

## Usage

This library separates the HTTP transport (PSR-18) from the response processing logic (ResultFactory).

### 1. Create a Result and a Factory

Implement `ResultInterface` for your data object and `ResultFactoryInterface` to transform the PSR-7 response into that object.

```php
namespace App\Http;

use Riftfox\HttpClient\ResultInterface;
use Riftfox\HttpClient\ResultFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class UserResult implements ResultInterface
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}

class UserResultFactory implements ResultFactoryInterface
{
    public function create(ResponseInterface $response): ResultInterface
    {
        $json = (string) $response->getBody();
        $data = json_decode($json, true);
        
        return new UserResult($data ?? []);
    }
}
```

### 2. Send a Request

```php
use Riftfox\HttpClient\HttpClient;
use GuzzleHttp\Client as GuzzleClient; // Example PSR-18 Client
use GuzzleHttp\Psr7\Request;

// 1. Initialize the PSR-18 client and your wrapper
$psrClient = new GuzzleClient();
$client = new HttpClient($psrClient);

// 2. Prepare the request and the factory
$request = new Request('GET', 'https://api.example.com/users/1');
$factory = new UserResultFactory();

// 3. Send request and get the transformed result directly
/** @var UserResult $result */
$result = $client->send($request, $factory);

print_r($result->data);
```

## Testing

Run the test suite with Codeception:

```bash
vendor/bin/codecept run unit
```