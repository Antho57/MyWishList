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

    public function displayItem(Request $rq, Response $rs, array$args):Response{

        try{
            session_start();

            $var = $rq->getQueryParams();
            $item = null;
            if(isset($var['numIt'])){
                $item = item::query()->where('id', '=', $var['numIt'])
                    ->firstOrFail();
            }

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
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant([$item]);

            $rs->write( $v->render(3, $htmlvars, $tab));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "item non trouvé");
            return $rs;
        }
    }





    public function allListe(Request $rq, Response $rs, array$args):Response{

        try{
            session_start();

            $item = liste::get();

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($item);

            $rs->write( $v->render(1, $htmlvars, $tab));
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
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($val);

            $rs->write($v->render(2, $htmlvars, $tab));
            return $rs;


        } catch (ModelNotFoundException $e) {
            $rs->write("La liste {$_GET['token']} n'a pas été trouvée");
            return $rs;
        }
    }

    public function displayCredits(Request $rq, Response $rs, array$args):Response{

        try{
            session_start();

            $val = null;

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($val);

            $rs->write( $v->render(667, $htmlvars, $tab));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Problème avec les crédits");
            return $rs;
        }
    }

    public function creerListe(Request $rq, Response $rs, array$args):Response{

        try{
            session_start();

            $var = $rq->getQueryParams();

            $liste = null;

            $val = null;

            if (!empty($var['titre']) && !empty($var['description']) && !empty($var['expiration'])) {
                $l = new liste();
                $l->titre =strip_tags($var['titre']);
                $l->description = strip_tags($var['description']);
                $l->expiration = strip_tags($var['expiration']);
                $code = uniqid();
                $l->token = $code;
                $l->timestamps = false;
                $l->save();

                $liste = liste::query()->where('token', 'like', $code)->first();
                $val = $liste;
            }

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($val);

            $rs->write( $v->render(4, $htmlvars, $tab));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Problème avec la création de listes");
            return $rs;
        }
    }

    public function connexion(Request $rq, Response $rs, array$args):Response{
        try {

            $val = null;

            if (!empty($_POST['login']) && !empty($_POST['password']) && $_SESSION['active'] === false) {
                Authentication::authenticate($_POST['login'], $_POST['password']);
                $val = compte::query()->where('login', 'like', strip_tags($_POST['login']))->first();
            }

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }


            $v = new VueParticipant($val);

            $rs->write( $v->render(5, $htmlvars, $tab));
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec la connexion");
            return $rs;
        }
    }

    public function inscription(Request $rq, Response $rs, array$args):Response{
        try {

            $val = null;

            if (!empty($_POST['login']) && !empty($_POST['password']) && $_SESSION['active'] === false) {

                $compte = compte::query()->where('login', 'like', strip_tags($_POST['login']))->first();
                if(isset($compte)){
                    echo "login déjà utilisé";
                }else{
                    session_start();
                    $c = new compte();
                    $c->login = strip_tags($_POST['login']);
                    $c->password = strip_tags(password_hash($_POST['password'], PASSWORD_BCRYPT));
                    $c->timestamps = false;
                    $c->save();
                    $val = $c;
                    $_SESSION['active'] = true;
                    $_SESSION['login'] = strip_tags($_POST['login']);
                }
            }

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($val);

            $rs->write( $v->render(6, $htmlvars, $tab));
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec l'inscription'");
            return $rs;
        }
    }

    public function accueil(Request $rq, Response $rs, array$args):Response{
        try {
            session_start();
            // PERMET DE SE DECONNECTER
            unset($_SESSION['active']);
            $val = null;

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien2 = $this->c->router->pathFor("AllItem", ['token'=>'']);
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");
            $accueil = $this->c->router->pathFor("Accueil");
            $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription];
            if (!isset($_SESSION['active'])){
                echo "vdjndvk";
                $lien5 = $this->c->router->pathFor("Connexion");
                $tab["lien5"] = $lien5;
            }

            $v = new VueParticipant($val);

            $rs->write( $v->render(7, $htmlvars, $tab));
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec l'accueil");
            return $rs;
        }
    }
}