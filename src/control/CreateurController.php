<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\Connexion\Authentication;
use mywishlist\models\commentaires;
use mywishlist\models\compte;
use mywishlist\models\participation;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use mywishlist\models\item as item;
use mywishlist\models\liste as liste;
use mywishlist\view\VueCreateur as VueCreateur;


class CreateurController{
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
        $deco = $this->c->router->pathFor("inactivité");

        $tab = ["accueil"=>$accueil, "lien1"=>$lien1, "lien2"=>'$lien2', "lien3"=>$lien3, "lien4"=>$lien4, "lien667"=>$lien667, "inscription"=>$inscription, "compte"=>$compte, "supprimerCompte"=>$supprcompte, "createurs"=>$createurs, "modifItem"=>$modifItem, "supprimerItem"=>$suppritem, "ajouterListeCompte"=>$addListeCompte, "deco"=>$deco];

        if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
            $lien5 = $this->c->router->pathFor("Connexion");
        }else{
            $lien5 = $this->c->router->pathFor("Deconnexion");
        }
        $tab["lien5"] = $lien5;

        if ($render === 'creer liste' && $val!=null){
            $lien6 = $this->c->router->pathFor("modifListe", ['token_modif'=>$val->token_modif]);
            $tab["lienModif"] = $lien6;
            $lien2 = $this->c->router->pathFor("listeDetail", ['token'=>$val->token]);
            $tab["lien2"] = $lien2;
        }

        if ($render === 'modifier liste'){
            $lien = $this->c->router->pathFor("ajoutItemListe", ['token_modif'=>$val->token_modif]);
            $tab['lienAjoutItem'] = $lien;
        }

        $v = new VueCreateur($val);

        $rs->write($v->render($render, $htmlvars, $tab));
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


    public function modifierListe(Request $rq, Response $rs, array$args){
        try {
            session_start();
            $l = liste::query()->where('token_modif', '=', $args['token_modif'])->first();
            if (!empty($_POST['NewTitre'])){
                $l->titre = $_POST['NewTitre'];
            }
            if (!empty($_POST['NewDescription'])){
                $l->description = $_POST['NewDescription'];
            }
            if (!empty($_POST['NewExpiration'])){
                $l->expiration = $_POST['NewExpiration'];
            }
            if ($l->public === 1){
                if (isset($_POST['public'])){
                    $l->public = 0;
                }
            }else {
                if (isset($_POST['public'])){
                    $l->public = 1;
                }
            }
            $l->timestamps = false;

            $l->save();

            $val = null;

            $val = liste::query()->where('token_modif', '=', $args['token_modif'])
                ->firstOrFail();

            $this->paths($rq, $val, $rs, 'modifier liste');

            return $rs;


        } catch (ModelNotFoundException $e) {
            $rs->write("La liste {$_GET['token_modif']} n'a pas été trouvée");
            return $rs;
        }
    }





    public function ajouterItemListe(Request $rq, Response $rs, array$args){
        try {
            session_start();

            $liste = null;
            $itemCree = null;
            $participation = null;

            $liste = liste::query()->where('token_modif', '=', $args['token_modif'])
                ->firstOrFail();

            if (!empty($_POST['NomItem']) && !empty($_POST['DescriptionItem']) && !empty($_POST['PrixItem'])){
                $item = new item();

                $item->liste_id = $liste->no;

                $item->nom = $_POST['NomItem'];

                $item->descr= $_POST['DescriptionItem'];

                $item->tarif = $_POST['PrixItem'];

                if (!empty($_POST['url'])) {
                    $item->url = $_POST['url'];
                }
                if (!empty($_POST['LienImg'])) {
                    $item->img = $_POST['LienImg'];
                }
                if (isset($_POST['cagnotte'])){
                    $item->cagnotte = 1;
                }else {
                    $item->cagnotte = 0;
                }

                $item->reserver = 0;

                $item->timestamps = false;
                $item->save();

                $itemCree = item::query()->where( 'descr', 'like', $_POST['DescriptionItem'])->firstOrFail();
                $participation = participation::query()->where ('id_item', '=', $itemCree->id)->get();
                $liste = liste::query()->where('no', '=', $itemCree->liste_id)
                    ->firstOrFail();
            }

            $val = ([$itemCree, $liste, $participation]);

            $this->paths($rq, $val, $rs, 'ajouter Item');

            return $rs;

        } catch (ModelNotFoundException $e) {
            $rs->write("Problème avec l'ajout de l'item");
            return $rs;
        }
    }




    public function modifierItem(Request $rq, Response $rs, array$args){
        try {
            session_start();

            $item = item::query()->where('id', '=', $_GET['numIt'])->first();
            $liste = liste::query()->where('no', '=', $item->liste_id)->first();
            $compte = compte::query()->where('compte_id', '=', $liste->user_id)->first();
            if(isset($_SESSION['active']) && $_SESSION['active'] === true && $compte->login === $_SESSION['login']){
                if (!empty($_POST['NewNom'])){
                    $item->nom = $_POST['NewNom'];
                }
                if (!empty($_POST['NewDescription'])){
                    $item->descr = $_POST['NewDescription'];
                }
                if (!empty($_POST['NewImg'])){
                    $item->img = $_POST['NewImg'];
                }
                if (!empty($_POST['NewURL'])){
                    $item->url = $_POST['NewURL'];
                }
                if (!empty($_POST['NewTarif'])){
                    $item->tarif = $_POST['NewTarif'];
                }
                if (isset($_POST["buttonSuppImg"])) {
                    $item->img = "";
                }
                if (isset($_POST['cagnotte'])){
                    $item->cagnotte = 1;
                }
                $item->timestamps = false;
                $item->save();
            }

            $this->paths($rq, $item, $rs, 'modifier item');
            return $rs;


        } catch (ModelNotFoundException $e) {
            $rs->write("La liste {$_GET['token_modif']} n'a pas été trouvée");
            return $rs;
        }
    }


    public function supprimerItem(Request $rq, Response $rs):Response{
        try {
            session_start();

            $item = item::query()->where('id', '=', $_GET['numIt'])->first();

            if (isset($_POST['oui'])){
                item::query()->where('id', '=', $_GET['numIt'])->delete();
                $item = null;
            }

            $this->paths($rq, $item, $rs, 'supprimerItem');

            return $rs;

        }catch (ModelNotFoundException $e) {
            $rs->write("Problème avec la suppression");
        }
    }




    public function ajouterListeCompte(Request $rq, Response $rs):Response{
        try {
            session_start();

            $liste = null;
            if (isset($_POST['ajoutListe']) && !empty($_POST['tokenModif'])){
                $liste = liste::query()->where('token_modif','=',$_POST['tokenModif'])->first();
                if ($liste != null){
                    $liste->user_id = $_SESSION['compte_id'];
                    $liste->timestamps = false;
                    $liste->save();
                }
            }

            $this->paths($rq, $liste, $rs, 'ajouterListeCompte');

            return $rs;

        }catch (ModelNotFoundException $e) {
            $rs->write("Problème avec l'ajout");
        }
    }

}