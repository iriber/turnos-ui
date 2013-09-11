<?php
use Ratchet\Server\IoServer;
use Turnos\UI\observer\Subject;

include '../conf/modules.php';

    //require dirname(__DIR__) . '/vendor/autoload.php';

    $server = IoServer::factory(
        new Subject()
      , 8084
    );

    $server->run();