<?php

require_once __DIR__.'/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

header("Cache-Control: no-cache, must-revalidate");

$c = new \Slim\Container(['settings' => ['displayErrorDetails' =>true]]);

$app = new \Slim\App($c);

\mywishlist\bd\connexion::start(__DIR__. '/src/conf/db.conf.ini');





$app->get('/liste/all', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->allListe($rq,$rs,$args);
    }
)->setName('AllListe');





$app->get('/liste/detail/{token}', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeDetail($rq,$rs,$args);
}
)->setName('AllItem');





$app->get('/item', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->displayItem($rq,$rs,$args);
}
)->setName('Item');





$app->get('/credits', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->displayCredits($rq,$rs,$args);
}
)->setName('Credits');





$app->get('/connexion', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->connexion($rq,$rs,$args);
}
)->setName('Connexion');





$app->get('/inscription', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->inscription($rq,$rs,$args);
}
)->setName('Inscription');





$app->get('/liste/creer', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->creerListe($rq,$rs,$args);
}
)->setName('CréerListe');

$app->get('/', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->accueil($rq,$rs,$args);
}
)->setName('Accueil');




$app->run();

