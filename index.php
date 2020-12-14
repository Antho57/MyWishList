<?php

require_once __DIR__.'/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$c = new \Slim\Container(['settings' => ['displayErrorDetails' =>true]]);

$app = new \Slim\App($c);

\mywishlist\bd\connexion::start(__DIR__. '/src/conf/db.conf.ini');





$app->get('/liste/all[/]', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->allListe($rq,$rs,$args);
    }
)->setName('AllListe');





$app->get('/liste/items/{num}', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeItem($rq,$rs,$args);
}
)->setName('AllItem');





$app->get('/item/{id}[/]', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->displayItem($rq,$rs,$args);
}
)->setName('item');


$app->get('/item[/]', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->menuItem($rq,$rs,$args);
}
)->setName('item');


$app->run();

