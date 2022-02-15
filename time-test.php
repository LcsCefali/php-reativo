<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;

require_once 'vendor/autoload.php';

$client = new Client();

$promise1 = $client->getAsync('http://localhost:8080/http-server.php');
$promise2 = $client->getAsync('http://localhost:8000/http-server.php');

$responses = Utils::unwrap([
  $promise1, $promise2
]);

echo "Resposta 1: " . $responses[0]->getBody()->getContents();
echo "Resposta 2: " . $responses[1]->getBody()->getContents();