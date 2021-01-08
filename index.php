<?php

require_once __DIR__.'/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

header("Cache-Control: no-cache, must-revalidate");

$c = new \Slim\Container(['settings' => ['displayErrorDetails' =>true]]);

$app = new \Slim\App($c);

\mywishlist\bd\connexion::start(__DIR__. '/src/conf/db.conf.ini');





$app->get('/liste/publiques', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->allListe($rq,$rs,$args);
    }
)->setName('AllListe');





$app->get('/liste/detail/{token}', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeDetail($rq,$rs,$args);
}
)->setName('AllItem');





$app->get('/liste/modifier/{token_modif}', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->modifierListe($rq,$rs,$args);
}
)->setName('modifListe');


$app->post('/liste/modifier/{token_modif}', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->modifierListe($rq,$rs,$args);
}
)->setName('modifListePost');





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

$app->post('/connexion', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->connexion($rq,$rs,$args);
}
)->setName('ConnexionPost');



$app->get('/inscription', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->inscription($rq,$rs,$args);
}
)->setName('Inscription');

$app->post('/inscription', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->inscription($rq,$rs,$args);
}
)->setName('InscriptionPost');




$app->get('/deconnexion', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->deconnexion($rq,$rs,$args);
}
)->setName('Deconnexion');




$app->get('/liste/creer', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->creerListe($rq,$rs,$args);
}
)->setName('CréerListe');


$app->post('/liste/creer', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->creerListe($rq,$rs,$args);
}
)->setName('CréerListePost');

$app->get('/', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->accueil($rq,$rs,$args);
}
)->setName('Accueil');


$app->get('/compte', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->compte($rq,$rs,$args);
}
)->setName('Compte');

$app->post('/compte', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->compte($rq,$rs,$args);
}
)->setName('ComptePost');



$app->get('/supprimercompte', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->supprimerCompte($rq,$rs,$args);
}
)->setName('supprimerCompte');

$app->post('/supprimercompte', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->supprimerCompte($rq,$rs,$args);
}
)->setName('supprimerComptePost');



$app->get('/createurs', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeCreateurs($rq,$rs,$args);
}
)->setName('Createurs');

$app->run();

