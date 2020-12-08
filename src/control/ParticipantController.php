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

            $item = item::query()->where('id', '=', $args['id'])
                ->firstOrFail();

            //$htmlvars = [
            //    'basepath'=>$rq->getUri()->getBasePath()
            //];

            $v = new VueParticipant([$item]);

            $rs->write( $v->render(3));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "item {$item->id} non trouvé");
            return $rs;
        }
    }





    public function allListe(Request $rq, Response $rs, array$args):Response{

        try{

            $item = liste::get();

            //$htmlvars = [
            //    'basepath'=>$rq->getUri()->getBasePath()
            //];

            $v = new VueParticipant($item);

            $rs->write( $v->render(1));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "Erreur lors de l'affichage des listes");
            return $rs;
        }
    }





    public function listeItem(Request $rq, Response $rs, array$args):Response{

        try{

            $liste = liste::query()->where('no', '=', $args['num'])
                    ->firstOrFail();

            $items = item::query()->where('liste_id', '=', $liste->no)->get();

            $val =([$liste, $items]);

            $v = new VueParticipant($val);

            $rs->write( $v->render(2));
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "La liste n°{$args['num']} n'a pas été trouvé");
            return $rs;
        }
    }



}