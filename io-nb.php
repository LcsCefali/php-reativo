<?php

$streamList = [
  fopen('arquivo.txt', 'r'),
  fopen('arquivo2.txt', 'r')
];

foreach ($streamList as $stream) {
  stream_set_blocking($stream, false);
}

// arquivo esta pronto para leitura?

do {
  $copyReadStream = $streamList;
  $streamsNumber = stream_select($copyReadStream, $write, $except, 0, 200000);
  
  if ($streamsNumber === 0) {
    echo "realizar outras tarefas";
    continue;
  }

  foreach ($copyReadStream as $key => $stream) {
    echo fgets($stream);
    unset($streamList[$key]);
  }
} while (!empty($streamList));

echo "Todos os arquivos foram lidos!" . PHP_EOL;