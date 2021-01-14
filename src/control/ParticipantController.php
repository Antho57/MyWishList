<?php


namespace mywishlist\control;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use mywishlist\Connexion\Authentication;
use mywishlist\models\commentaires;
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

        if ($render === 'liste detail'){
            $lien6 = $this->c->router->pathFor("modifListe", ['token_modif'=>$val[0]->token_modif]);
            $tab["lienModif"] = $lien6;
            $lien2 = $this->c->router->pathFor("listeDetail", ['token'=>$val[0]->token]);
            $tab["lien2"] = $lien2;
        }
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

        if ($render === 'creer liste' && $val!=null){
            $lien6 = $this->c->router->pathFor("modifListe", ['token_modif'=>$val->token_modif]);
            $tab["lienModif"] = $lien6;
            $lien2 = $this->c->router->pathFor("listeDetail", ['token'=>$val->token]);
            $tab["lien2"] = $lien2;
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

        if ($render === 'modifier liste'){
            $lien = $this->c->router->pathFor("ajoutItemListe", ['token_modif'=>$val->token_modif]);
            $tab['lienAjoutItem'] = $lien;
        }

        $v = new VueParticipant($val);

        $rs->write($v->render($render, $htmlvars, $tab));
    }

    public function displayItem(Request $rq, Response $rs):Response{

        try{
            session_start();

            $var = $rq->getQueryParams();
            $item = null;
            $liste = null;
            if(isset($var['numIt'])){
                $item = item::query()->where('id', '=', $var['numIt'])
                    ->firstOrFail();
                $liste = liste::query()->where('no', '=', $item->liste_id)->first();
            }
            $rep = ([$item, $liste]);

            if(isset($_POST['buttonParticiperItem']) && !empty($_POST['nomParticipant']) && !empty($_POST['messageParticipant'])){
                $_SESSION['nomParticipant'] = $_POST['nomParticipant'];
                $item->nom_participant = $_POST['nomParticipant'];
                $item->message = $_POST['messageParticipant'];
                $item->reserver = true;
                if(isset($_SESSION['compte_id'])){
                    $item->id_participant = $_SESSION['compte_id'];
                }
                $item->timestamps = false;
                $item->save();
            }

            $this->paths($rq, $rep, $rs, 'un item');
            return $rs;


        }catch(ModelNotFoundException $e){
            $rs->write( "item non trouvé");
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





    public function listeDetail(Request $rq, Response $rs, array$args):Response
    {
        try {
            session_start();

            $val = null;
            $items = null;

            $liste = liste::query()->where('token', '=', $args['token'])
                ->firstOrFail();
            $items = item::query()->where('liste_id', '=', $liste->no)->get();

            if(isset($_POST['publier']) && !empty($_POST['message']) && !empty($_POST['nom'])){
                $com = new commentaires();
                $com->message = $_POST['message'];
                $com->nom = $_POST['nom'];
                $com->id_liste = $liste->no;
                $com->timestamps = false;
                $com->save();
            }
            $commentaires = commentaires::query()->get();

            if (isset($_SESSION['active']) && $_SESSION['active'] === true){
                $utilisateur = compte::query()->where('login', 'like', $_SESSION['login'])->firstOrFail();
                $val = ([$liste, $items, $utilisateur, $commentaires]);
            }else {
                $val = ([$liste, $items, $commentaires]);
            }



            $this->paths($rq, $val, $rs, 'liste detail');

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



    public function ajouterItemListe(Request $rq, Response $rs, array$args){
        try {
            session_start();

            $liste = null;
            $itemCree = null;

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

                $item->reserver = 0;

                $item->timestamps = false;
                $item->save();

                $itemCree = item::query()->where( 'descr', 'like', $_POST['DescriptionItem'])->firstOrFail();
            }

            $val = ([$itemCree, $liste]);

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
                if (isset($_POST["buttonSuppImg"]))
                {
                    $item->img = "";
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