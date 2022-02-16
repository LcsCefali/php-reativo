<?php

$streamList = [
  stream_socket_client('tcp://localhost:8080'),
  stream_socket_client('tcp://localhost:8001'),
  fopen('arquivo.txt', 'r'),
  fopen('arquivo2.txt', 'r')
];

fwrite($streamList[0], 'GET /http-server.php HTTP/1.1'. PHP_EOL . PHP_EOL);

foreach ($streamList as $stream) {
  stream_set_blocking($stream, false);
}

do {
  $copyReadStream = $streamList;
  $streamsNumber = stream_select($copyReadStream, $write, $except, 0, 200000);
  
  if ($streamsNumber === 0) {
    // echo "realizar outras tarefas". PHP_EOL;
    continue;
  }

  foreach ($copyReadStream as $key => $stream) {
    $conteudo = stream_get_contents($stream);
    $posicaoFimHttp = strpos($conteudo, PHP_EOL . PHP_EOL);

    if ($posicaoFimHttp !== false) {
      echo substr($conteudo, $posicaoFimHttp + 4);
    } else {
      echo $conteudo;
    }
    
    unset($streamList[$key]);
  }
} while (!empty($streamList));

echo "Todos as streams foram lidas!" . PHP_EOL;