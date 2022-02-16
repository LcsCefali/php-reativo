<?php

use React\EventLoop\Loop;

require_once 'vendor/autoload.php';

$loop = Loop::get();

$loop->addPeriodicTimer(1, function () {
  echo "1" . PHP_EOL;
});

$loop->run();