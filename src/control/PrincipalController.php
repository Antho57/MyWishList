<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\Connexion\Authentication;
use mywishlist\models\compte;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use mywishlist\models\liste as liste;
use mywishlist\view\VuePrincipale as VuePrincipale;


class PrincipalController{

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
        $lien3 = $this->c->router->pathFor("Item");
        $lien4 = $this->c->router->pathFor("CréerListe");
        $lien667 = $this->c->router->pathFor("Credits");
        $inscription = $this->c->router->pathFor("Inscription");
        $accueil = $this->c->router->pathFor("Accueil");
        $compte = $this->c->router->pathFor("Compte");
        $supprcompte = $this->c->router->pathFor("supprimerCompte");
        $createurs = $this->c->router->pathFor("Createurs");
        $modifItem = $this->c->router->pathFor("ModifierItem");
        $suppritem = $this->c->router->pathFor("SupprimerItem");
        $addListeCompte = $this->c->router->pathFor("AjouterListeCompte");

        $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>'$lien2', "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription, "compte"=>$compte, "supprimerCompte"=>$supprcompte, "createurs"=>$createurs, "modifItem"=>$modifItem, "supprimerItem"=>$suppritem, "ajouterListeCompte"=>$addListeCompte];

        if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
            $lien5 = $this->c->router->pathFor("Connexion");
        }else{
            $lien5 = $this->c->router->pathFor("Deconnexion");
        }
        $tab["lien5"] = $lien5;

        if ($render === 'compte'){
            $i =0;
            $tabLien = [];
            foreach($val as $row){
                $lien = $this->c->router->pathFor("listeDetail", ['token'=>$row->token]);
                $tabLien[$i] = $lien;
                $i++;
            }
            $tab['lienCompte'] = $tabLien;

        }

        if ($render === 'listes publiques'){
            $i =0;
            $tabLien = [];
            foreach($val as $row){
                $lien = $this->c->router->pathFor("listeDetail", ['token'=>$row->token]);
                $tabLien[$i] = $lien;
                $i++;
            }
            $tab['lienPublique'] = $tabLien;

        }

        $v = new VuePrincipale($val);

        $rs->write($v->render($render, $htmlvars, $tab));
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
        session_destroy();

        return $rs;
    }

    public function compte(Request $rq, Response $rs):Response{
        try {
            session_start();
            $val = null;

            if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['password2']) && isset($_SESSION['active']) && $_SESSION['active'] === true) {
                Authentication::modifyUser($_POST['login'],$_POST['password2']);
                unset($_SESSION['active']);
            }

            $val = liste::query()->where('user_id', '=', $_SESSION['compte_id'])->get();

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

        }catch (ModelNotFoundException $e) {
            $rs->write("Problème avec la suppression");
        }
    }



    public function listeCreateurs(Request $rq, Response $rs, array$args){
        try {
            session_start();

            $ids = compte::query()->select( 'login')->join('liste', 'compte_id', '=', 'user_id')->where('public', '=', '1')->groupBy('compte_id', 'login')->get();

            $this->paths($rq, $ids, $rs, 'createurs');

            return $rs;

        } catch (ModelNotFoundException $e) {
            $rs->write("Problème avec la liste des créateurs");
            return $rs;
        }
    }

    public function allListe(Request $rq, Response $rs):Response{

        try{
            session_start();

            $item = liste::query()->where('public', '=', '1')->orderBy('expiration')->get();

            $this->paths($rq, $item, $rs, 'listes publiques');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Erreur lors de l'affichage des listes");
            return $rs;
        }
    }

}