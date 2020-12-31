<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\models\compte;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use mywishlist\models\item as item;
use mywishlist\models\liste as liste;
use mywishlist\view\VueParticipant as vueParticipant;

session_start();
class ParticipantController{
    private $c = null;

    public function __construct(\Slim\Container $c){
        $this->c = $c;
        $_SESSION['active'] = false;
    }

    public function displayItem(Request $rq, Response $rs, array$args):Response{

        try{

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
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667];

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

            $item = liste::get();

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667];

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
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667];
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

            $val = null;

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667];

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
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667];

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

            $var = $rq->getQueryParams();

            $val = null;

            if (!empty($var['login']) && !empty($var['password']) && $_SESSION['active'] === false) {
                $_SESSION['active'] = true;
                $_SESSION['login'] = strip_tags($var['login']);

                $compte = compte::query()->where('login', 'like', $_SESSION['login'])->first();
                if(isset($compte)){
                    if (password_verify(strip_tags($var['password']), $compte['password'])){
                        $val = $compte;
                    }else{
                        session_destroy();
                    }
                }else{
                    session_destroy();
                }
            }else{
                session_destroy();
            }

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667, "inscription"=>$inscription];

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
            $var = $rq->getQueryParams();

            $val = null;

            if (!empty($var['login']) && !empty($var['password'])) {
                $login = strip_tags($var['login']);
                $pass = strip_tags($var['password']);

                $compte = compte::query()->where('login', 'like', $login)->first();
                if(isset($compte)){
                    echo "login déjà utilisé";
                }else{
                    $c = new compte();
                    $c->login = strip_tags($var['login']);
                    $c->password = strip_tags(password_hash($var['password'], PASSWORD_BCRYPT));
                    $c->timestamps = false;
                    $c->save();
                    $val = $c;
                }

            }

            $htmlvars = [
                'basepath'=>$rq->getUri()->getBasePath()
            ];

            $lien1 = $this->c->router->pathFor("AllListe");
            $lien3 = $this->c->router->pathFor("Item");
            $lien4 = $this->c->router->pathFor("CréerListe");
            $lien5 = $this->c->router->pathFor("Connexion");
            $lien667 = $this->c->router->pathFor("Credits");
            $inscription = $this->c->router->pathFor("Inscription");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien5"=>$lien5, "lien667"=>$lien667, "inscription"=>$inscription];

            $v = new VueParticipant($val);

            $rs->write( $v->render(6, $htmlvars, $tab));
            return $rs;

        }catch (ModelNotFoundException $e){
            $rs->write( "Problème avec l'inscription'");
            return $rs;
        }
    }

}