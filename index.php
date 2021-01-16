<?php

require_once __DIR__.'/vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

header("Cache-Control: no-cache, must-revalidate");

$c = new \Slim\Container(['settings' => ['displayErrorDetails' =>true]]);

$app = new \Slim\App($c);

\mywishlist\bd\connexion::start(__DIR__. '/src/conf/db.conf.ini');


$app->get('/deconnecte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->decoInactivite($rq,$rs);
}
)->setName('inactivité');


$app->get('/liste/publiques', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->allListe($rq,$rs);
}
)->setName('AllListe');





$app->get('/liste/detail/{token}', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeDetail($rq,$rs,$args);
}
)->setName('listeDetail');

$app->post('/liste/detail/{token}', function (Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->listeDetail($rq,$rs,$args);
}
)->setName('listeDetailPost');





$app->get('/liste/modifier/{token_modif}', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->modifierListe($rq,$rs,$args);
}
)->setName('modifListe');


$app->post('/liste/modifier/{token_modif}', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->modifierListe($rq,$rs,$args);
}
)->setName('modifListePost');




$app->get('/liste/modifier/{token_modif}/ajoutItem', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->ajouterItemListe($rq,$rs,$args);
}
)->setName('ajoutItemListe');


$app->post('/liste/modifier/{token_modif}/ajoutItem', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->ajouterItemListe($rq,$rs,$args);
}
)->setName('ajoutItemListePost');





$app->get('/item', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->displayItem($rq,$rs);
}
)->setName('Item');

$app->post('/item', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\ParticipantController($this);
    return $c->displayItem($rq,$rs);
}
)->setName('ItemPost');





$app->get('/item/modifier', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->modifierItem($rq,$rs,$args);
}
)->setName('ModifierItem');


$app->post('/item/modifier', function(Request $rq, Response $rs, array $args): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->modifierItem($rq,$rs,$args);
}
)->setName('ModifierItemPost');



$app->get('/item/supprimer', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->supprimerItem($rq,$rs);
}
)->setName('SupprimerItem');

$app->post('/item/supprimer', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->supprimerItem($rq,$rs);
}
)->setName('SupprimerItemPost');



$app->get('/credits', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->displayCredits($rq,$rs);
}
)->setName('Credits');





$app->get('/connexion', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->connexion($rq,$rs);
}
)->setName('Connexion');

$app->post('/connexion', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->connexion($rq,$rs);
}
)->setName('ConnexionPost');




$app->get('/inscription', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->inscription($rq,$rs);
}
)->setName('Inscription');

$app->post('/inscription', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->inscription($rq,$rs);
}
)->setName('InscriptionPost');




$app->get('/deconnexion', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->deconnexion($rq,$rs);
}
)->setName('Deconnexion');




$app->get('/liste/creer', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->creerListe($rq,$rs);
}
)->setName('CréerListe');


$app->post('/liste/creer', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->creerListe($rq,$rs);
}
)->setName('CréerListePost');



$app->get('/', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->accueil($rq,$rs);
}
)->setName('Accueil');




$app->get('/compte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->compte($rq,$rs);
}
)->setName('Compte');

$app->post('/compte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->compte($rq,$rs);
}
)->setName('ComptePost');



$app->get('/supprimercompte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->supprimerCompte($rq,$rs);
}
)->setName('supprimerCompte');

$app->post('/supprimercompte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->supprimerCompte($rq,$rs);
}
)->setName('supprimerComptePost');



$app->get('/createurs', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\PrincipalController($this);
    return $c->listeCreateurs($rq,$rs);
}
)->setName('Createurs');




$app->get('/ajouterListeCompte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->ajouterListeCompte($rq,$rs);
}
)->setName('AjouterListeCompte');

$app->post('/ajouterListeCompte', function (Request $rq, Response $rs): Response {

    $c = new mywishlist\control\CreateurController($this);
    return $c->ajouterListeCompte($rq,$rs);
}
)->setName('AjouterListeComptePost');

$app->run();

