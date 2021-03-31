<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use MyApp\Fechas;
    require dirname(__DIR__) . '/vendor/autoload.php';
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new Fechas()
            )
        ),
        8080
    );
    $server->run();