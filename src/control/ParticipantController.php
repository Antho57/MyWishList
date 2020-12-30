<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use mywishlist\models\item as item;
use mywishlist\models\liste as liste;
use mywishlist\view\VueParticipant as vueParticipant;

class ParticipantController{

    private $c = null;

    public function __construct(\Slim\Container $c){
        $this->c = $c;
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
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667];

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
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667];

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
            $lien667 = $this->c->router->pathFor("Credits");
            $tab = ["lien1"=>$lien1,"lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667];
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
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667];

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
            $lien667 = $this->c->router->pathFor("Credits");

            $tab = ["lien1"=>$lien1, "lien2"=>$lien2, "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667];

            $v = new VueParticipant($val);

            $rs->write( $v->render(4, $htmlvars, $tab));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Problème avec la création de listes");
            return $rs;
        }
    }

}