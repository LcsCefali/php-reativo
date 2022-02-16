<?php
use React\EventLoop\Loop;
use React\Stream\ReadableResourceStream;
use React\Stream\DuplexResourceStream;

require_once 'vendor/autoload.php';

$streamList = [
  // stream_socket_client('tcp://localhost:8080'),
  stream_socket_client('tcp://localhost:8001'),
  fopen('arquivo.txt', 'r'),
  fopen('arquivo2.txt', 'r')
];

$httpList = [
  stream_socket_client('tcp://localhost:8080')
];

$loop = Loop::get();

$readableStreamList = array_map(fn ($stream) => new ReadableResourceStream($stream, $loop), $streamList);
$duplexStreamList = array_map(fn ($stream) => new DuplexResourceStream($stream, $loop), $httpList);

foreach ($duplexStreamList as $duplexStream) {
  $duplexStream->write('GET /http-server.php HTTP/1.1'. "\r\n\r\n");
  $duplexStream->on('data', function ($data) {
    $posicaoFimHttp = strpos($data, "\r\n\r\n");
    echo substr($data, $posicaoFimHttp + 4);
  });
}

foreach ($readableStreamList as $readableStream) {
  $readableStream->on('data', function ($data) {
    echo $data . PHP_EOL;
  });
}

$loop->run();