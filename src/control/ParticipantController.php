<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\Connexion\Authentication;
use mywishlist\models\compte;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use mywishlist\models\item as item;
use mywishlist\models\liste as liste;
use mywishlist\view\VueParticipant as vueParticipant;
use function Sodium\add;

class ParticipantController{
    private $c = null;

    public function __construct(\Slim\Container $c){
        $this->c = $c;
        $_SESSION['active'] = false;
    }

    public function paths($rq, $val, $rs, $render){
        $htmlvars = [
            'basepath' => $rq->getUri()->getBasePath()
        ];

        $lien1 = $this->c->router->pathFor("AllListe");
        $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
        $lien3 = $this->c->router->pathFor("Item");
        $lien4 = $this->c->router->pathFor("CréerListe");
        $lien667 = $this->c->router->pathFor("Credits");
        $inscription = $this->c->router->pathFor("Inscription");
        $accueil = $this->c->router->pathFor("Accueil");
        $compte = $this->c->router->pathFor("Compte");
        $supprcompte = $this->c->router->pathFor("supprimerCompte");
        $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription, "compte"=>$compte, "supprimerCompte"=>$supprcompte];
        if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
            $lien5 = $this->c->router->pathFor("Connexion");
        }else{
            $lien5 = $this->c->router->pathFor("Deconnexion");
        }
        $tab["lien5"] = $lien5;

        $v = new VueParticipant($val);

        $rs->write($v->render($render, $htmlvars, $tab));
    }

    public function displayItem(Request $rq, Response $rs):Response{

        try{
            session_start();

            $var = $rq->getQueryParams();
            $item = null;
            if(isset($var['numIt'])){
                $item = item::query()->where('id', '=', $var['numIt'])
                    ->firstOrFail();
            }

            $this->paths($rq, [$item], $rs, 'un item');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "item non trouvé");
            return $rs;
        }
    }





    public function allListe(Request $rq, Response $rs):Response{

        try{
            session_start();

            $item = liste::query()->where('public', '=', '1')->get();

            $this->paths($rq, $item, $rs, 'listes publiques');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Erreur lors de l'affichage des listes");
            return $rs;
        }
    }





    public function listeDetail(Request $rq, Response $rs, array$args):Response
    {
        try {
            session_start();

            $val = null;

            $liste = liste::query()->where('token', '=', $args['token'])
                ->firstOrFail();
            $items = item::query()->where('liste_id', '=', $liste->no)->get();
            $val = ([$liste, $items]);

            $this->paths($rq, $val, $rs, 2);

            return $rs;


        } catch (ModelNotFoundException $e) {
            $rs->write("La liste {$_GET['token']} n'a pas été trouvée");
            return $rs;
        }
    }

    public function displayCredits(Request $rq, Response $rs):Response{

        try{
            session_start();

            $val = null;

            $this->paths($rq, $val, $rs, 'credits');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Problème avec les crédits");
            return $rs;
        }
    }

    public function creerListe(Request $rq, Response $rs):Response{

        try{
            session_start();

            $liste = null;

            $val = null;

            if (!empty($_POST['titre']) && !empty($_POST['description']) && !empty($_POST['expiration'])) {
                $l = new liste();
                if (isset($_SESSION['active']) && $_SESSION['active']===true){
                    $l->user_id = $_SESSION['compte_id'];
                }
                $l->titre =strip_tags($_POST['titre']);
                $l->description = strip_tags($_POST['description']);
                $l->expiration = strip_tags($_POST['expiration']);
                $code = uniqid('token', true);
                $l->token = $code;
                $code2 = uniqid('token_modif', true);
                $l->token_modif = $code2;
                $l->public = isset($_POST['public']);
                $l->timestamps = false;
                $l->save();

                $liste = liste::query()->where('token', 'like', $code)->first();
                $val = $liste;
            }

            $this->paths($rq, $val, $rs, 'creer liste');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Problème avec la création de listes");
            return $rs;
        }
    }

    public function connexion(Request $rq, Response $rs):Response{
        try {

            $val = null;

            if (!empty($_POST['login']) && !empty($_POST['password']) && $_SESSION['active'] === false) {
                Authentication::authenticate($_POST['login'], $_POST['password']);
                $val = compte::query()->where('login', 'like', strip_tags($_POST['login']))->first();
            }

            $this->paths($rq, $val, $rs, 'connexion');
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec la connexion");
            return $rs;
        }
    }

    public function inscription(Request $rq, Response $rs):Response{
        try {

            $val = null;

            if (!empty($_POST['login']) && !empty($_POST['password']) && $_SESSION['active'] === false && $_POST['password'] === $_POST['password2']) {
                Authentication::createUser($_POST['login'],$_POST['password']);
                $val = compte::query()->where('login', 'like', strip_tags($_POST['login']))->first();
            }

            $this->paths($rq, $val, $rs, 'inscription');
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec l'inscription");
            return $rs;
        }
    }

    public function accueil(Request $rq, Response $rs):Response{
        try {
            session_start();

            $val = null;

            $this->paths($rq, $val, $rs, 'accueil');
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec l'accueil");
            return $rs;
        }
    }

    public function deconnexion(Request $rq, Response $rs):Response{
        session_start();
        unset($_SESSION['active']);
        $this->paths($rq, '', $rs, 'deconnexion');

        return $rs;
    }

    public function compte(Request $rq, Response $rs):Response{
        try {
            session_start();

            $val = null;
            $val = liste::query()->where('user_id', '=', $_SESSION['compte_id'])->get();

            if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['password2']) && isset($_SESSION['active']) && $_SESSION['active'] === true) {
                Authentication::modifyUser($_POST['login'],$_POST['password2']);
                unset($_SESSION['active']);
            }

            $this->paths($rq, $val, $rs, 'compte');

            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec le compte");
            return $rs;
        }
    }

    public function supprimerCompte(Request $rq, Response $rs):Response{
        try {
            session_start();

            if (isset($_POST['oui'])){
                Authentication::deleteUser($_SESSION['login']);
            }

            $this->paths($rq, '', $rs, 'supprimerCompte');

            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec la suppression");
            return $rs;
        }
    }
}