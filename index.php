<?php
require_once 'vendor/autoload.php';
$config = require_once 'config.php';

if (empty($config['accessToken'])) {
    echo "Access token should be filled!!!\n";
    exit(1);
}

use Laminas\Diactoros\RequestFactory;
use Laminas\Diactoros\ResponseFactory;
use Kdabek\HttpClient\Transport\Curl;
use Kdabek\HttpClient\Auth\Factory\AuthorizationFactory;
use Kdabek\HttpClient\Client;
use Kdabek\HttpClientExample\Logger\Formatter\CustomFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;


$url = $config['url'];
$accessToken = $config['accessToken'];
$requestFactory = new RequestFactory();
$responseFactory = new ResponseFactory();
$transport = new Curl($responseFactory);
$authorizationFactory = new AuthorizationFactory();
$client = new Client($requestFactory, $transport, $authorizationFactory);
$logger = new Logger('Http-Client');
$stream = new StreamHandler('php://stdout', Logger::DEBUG);
$stream->setFormatter(new CustomFormatter());
$logger->pushHandler($stream);
$client->setLogger($logger);

$user = [
    'name' => 'John',
    'email' => 'JohnDoe@example.com',
    'gender' => 'male',
    'status' => 'inactive'
];

$updatedUser = [
    'name' => 'John',
    'email' => 'John.Doe@gmail.com',
    'gender' => 'male',
    'status' => 'active'
];

try {
    //POST
    $response = $client->withToken($accessToken)->post($url . '/users', $user);
    $userId = $response->json()['data']['id'];

    // GET
    $response = $client->get($url . '/users/' . $userId);

    //PUT
    $response = $client->put($url . '/users/' . $userId, $updatedUser);

    //DELETE
    $response = $client->delete($url . '/users/' . $userId);

    //BAD REQUEST
    $response = $client->delete($url . '/users/' . $userId);
}
catch (\Exception $e) {
    echo $e->getMessage();
    exit(1);
}
