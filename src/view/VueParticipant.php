<?php


namespace mywishlist\view;

class VueParticipant{

    private $data;

    public function __construct($data){
        $this->data = $data;
    }


    private function unItemHtml($args, $lien, $tab):string {
        $val = '';
        $infosparticipant = '';

        $montant = 0;
        foreach ($args[2] as $row) {
            $montant += $row->montant;
            $infosparticipant .= <<<END
            <h4 class="text" style="display: inline;"> - Nom du participant : </h4> <p class ="text" style="display: inline; margin-left: 0px;"> {$row->nom_participant} </p><br>
            <h4 class="text" style="display: inline; margin-left: 43px"> Montant de la participation : </h4> <p class ="text" style="display: inline; margin-left: 0px;"> {$row->montant} </p><br>
            <h4 class="text" style="display: inline; margin-left: 43px"> Message du participant : </h4> <p class ="text" style="display: inline; margin-left: 0px;"> {$row->message} </p><br><br>
END;
        }

        if (date('Y-m-d', strtotime($args[1]->expiration)) > date('Y-m-d')){
            if ($args[0]->cagnotte === 0 && $args[0]->reserver === 0){
                $val .= <<<END
                <h4 class="titre3">Cet item n'a pas de cagnotte</h4>
END;
            }else if ($args[0]->reserver === 1){
                if (isset($_SESSION['active']) && $_SESSION['active'] === true) {
                    if ($_SESSION['compte_id'] === $args[1]->user_id) {
                        $val .= <<<END
                        <h4 class="titre3">La cagnotte pour cet item est terminée</h4>
END;
                    }else{
                        $val .= <<<END
                    <h4 class="titre3">La cagnotte pour cet item est terminée</h4>
END;
                        if(sizeof($args[2])===0){
                            $val .= <<<END
                            <p class="text">Aucun participant</p>
END;

                        }else{
                            $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Les participants : </p>
END;
                                $val .= $infosparticipant;
                        }
                    }

                }else {
                    $val .= <<<END
                    <h4 class="titre3">La cagnotte pour cet item est terminée</h4>
END;
                    if(sizeof($args[2])===0){
                        $val .= <<<END
                            <p class="text">Aucun participant</p>
END;

                    }else{
                        $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Les participants : </p>
END;

                            $val .= $infosparticipant;
                    }
                }
            }else if ($args[0]->cagnotte === 1){
                if (isset($_SESSION['active']) && $_SESSION['active'] === true) {
                    if ($_SESSION['compte_id'] === $args[1]->user_id) {
                        $val .= <<<END
                        <h4 class="titre3">Une cagnotte est ouverte pour cet item</h4>
END;
                    }else{
                        $val .= <<<END
                    <h4 class="titre3">Une cagnotte est ouverte pour cet item</h4>
END;
                        if(sizeof($args[2])===0){
                            $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Aucun participant</p>
END;

                        }else{
                            $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Les participants : </p>
END;
                                $val .= $infosparticipant;
                        }
                    }

                }else {
                    $val .= <<<END
                    <h4 class="titre3">Une cagnotte est ouverte pour cet item</h4>
END;
                    if(sizeof($args[2])===0){
                        $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Aucun participant</p>
END;

                    }else{
                        $val .= <<<END
                            <p class="text">Montant de la cagnotte : {$montant}€</p>
                            <p class="text">Les participants : </p>
END;
                            $val .= $infosparticipant;
                    }
                }
            }
        }else {
            if (sizeof($args[2])===0){
                $val .= <<<END
                    <h4 class="titre3">La cagnotte pour cet item est fermée</h4>
                    <p class="text">Cet item n'a pas eu de cagnotte</p>
END;
            }else {
                $val .= <<<END
                    <h4 class="titre3">La cagnotte pour cet item est fermée</h4>
                    <p class="text">Montant de la cagnotte : {$montant}€</p>
                    <p class="text">Les participants : </p>
END;
                    $val .= $infosparticipant;
            }
        }

        if($args[0]->img != null){
            $html = <<<END
            <div>
            <h3 class="titre3">{$args[0]->id} - {$args[0]->nom}</h3>
            <p class="text">{$args[0]->descr}</p>
            <h4 class="text" style="display: inline;">Tarif : </h4> <p class="text" style="display: inline; margin-left: 0px;"> {$args[0]->tarif}€</p><br><br>
            <h4 class="text" style="display: inline;"> URL : </h4>  <p class="text" style="display: inline; margin-left: 0px;"> {$args[0]->url}</p><br>
           
END;


            if (!str_starts_with($args[0]->img, "http")){
                $html.= <<<END
                <img src="{$lien['basepath']}/web/img/{$args[0]->img}" class="imgItem" alt="{$args[0]->descr}">
                </div><br><br><br><br><br><br>
END;
                $html.=$val;
            }else{
                $html.= <<<END
                <img src="{$args[0]->img}" class="imgItem" alt="{$args[0]->descr}">
                </div><br><br><br><br><br><br>
END;
                $html.=$val;
            }

        }else{
            $html = <<<END
            <div>
            <h3 class="titre3">{$args[0]->id} - {$args[0]->nom}</h3>
            <p class="text">{$args[0]->descr}</p>
            <h4 class="text" style="display: inline;">Tarif : </h4> <p class="text" style="display: inline; margin-left: 0px;"> {$args[0]->tarif}€</p><br><br>
            <h4 class="text" style="display: inline;"> URL : </h4>  <p class="text" style="display: inline; margin-left: 0px;"> {$args[0]->url}</p><br>
END;
            $html.=$val;

        }
        if($args[0]->cagnotte === 0 && !$args[0]->reserver && isset($_SESSION['active']) && $_SESSION['active'] === true && $_SESSION['compte_id'] === $args[1]->user_id && isset($_GET['numIt']) && date('Y-m-d', strtotime($args[1]->expiration)) > date('Y-m-d')){
            $html .= <<<END
                <br><br><br><br><br><br><br>
                <a href="{$tab['modifItem']}?numIt={$_GET['numIt']}"><input type="button" class="buttonAfficherModif" style="display:inline-block;  margin-left: 0%;" name="modifier" value="Modifier infos"></a>
                <a href="{$tab['supprimerItem']}?numIt={$_GET['numIt']}"><input type="button" class="buttonAfficherModif" style="display:inline-block; margin-left: 0%;" name="supprimer" value="Supprimer l'item"></a>
                </div>
END;
        }

        if(!isset($_SESSION['nomParticipant'])){
            $_SESSION['nomParticipant'] = '';
        }

        $dejaParticiper = false;

        if (isset($_SESSION['active']) && $_SESSION['active'] === true) {
            foreach ($args[2] as $row) {
                if ($row->id_participant === $_SESSION['compte_id']) {
                    $dejaParticiper = true;
                }
            }

            if ($args[0]->cagnotte === 1 && $args[0]->reserver === 0 && date('Y-m-d', strtotime($args[1]->expiration)) > date('Y-m-d') && !$dejaParticiper && isset($_SESSION['active']) && $_SESSION['active'] === true && $_SESSION['compte_id'] != $args[1]->user_id){
                $montant = 0;
                foreach ($args[2] as $row) {
                    $montant += $row->montant;
                }
                $max = $args[0]->tarif - $montant;

                $html .= <<<END
                    <div>
                    <h4 class="titre3" for="numLi"> Participer pour cet item </h4>
                    <form method="post">
                        <label class="text" for="nomParticipant" style="display: inline;"> Entrez votre nom </label>
                        <input type="text" class="infosModif" name="nomParticipant" style="margin-left: 0px" placeholder="Nom"  value="{$_SESSION['login']}"> <br><br>
                        <label class="text" for="messageParticipant" style="display: inline;"> Entrez votre message </label><br>
                        <textarea type="text" class="infosModif" name="messageParticipant" cols="75" rows="5" minlength="1" maxlength="1000" size="50" placeholder="Entrez votre message" ></textarea><br><br>
                        <label class="text" for="montant" style="display: inline;"> Entrez un montant </label>
                        <input type="number" class="infosModif" name="montant" min="1" max=$max value="1"> <br><br>
                        <input type="submit" name="buttonParticiperItem" class="buttonAjoutItem" value="Participer"><br><br>
                        </form>
                        </div>
                    </body>
                </html>
END;
            }else if ($args[0]->reserver && $args[0]->nom_participant === $_SESSION['nomParticipant']) {
                $html .= <<<END
                        <div>
                        <p class="participationok">Participation enregistrée</p>
                        </div>
                    </body>
                </html>
END;
            }
        }

        return $html;
    }


    private function listeDetail($args, $lien, $tab):String {
        $html = null;

        if ($args[0]->public){
            $public = 'Oui';
        }else{
            $public = 'Non';
        }

        $html = <<<END
            <h3 class="titreli"> {$args[0]->no} - {$args[0]->titre}</h3>
END;

        if(isset($_SESSION['active']) && $_SESSION['active'] === true && $args[0]->user_id === $args[2]->compte_id) {
            $html .= <<<END
            <form method="post" enctype="multipart/form-data">
                <input type="file" id="fileToUpload" name="fileToUpload" class="inputfile" accept=".jpg,.jpeg,.png,.gif"><br><br>
                <input type="submit" name="poster" value="Poster l'image" class="text" style="font-size: 20px">
            </form>
END;
            if (isset($_FILES['fileToUpload'])) {
                $target_dir = "web/img/";
                $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // Check if image file is a actual image or fake image
                if (isset($_POST["poster"])) {
                    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                    if ($check !== false) {
                        $uploadOk = 1;
                    } else {
                        $uploadOk = 0;
                    }
                }

                if (file_exists($target_file)) {
                    $html .= <<<END
                <h4 class="text" style="color: #ff2b39; display: inline-block">Erreur : Cette image existe déjà</h4>
END;
                    $uploadOk = 0;
                }

                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        $name = htmlspecialchars(basename($_FILES["fileToUpload"]["name"]));
                        $html .= <<<END
                <h4 class="text" style="color: green; display: inline-block">L'image {$name} a été téléchargé</h4>
END;
                    }
                }
            }
        }


        $val = null;

        if (date('Y-m-d', strtotime($args[0]->expiration)) <= date('Y-m-d')){
            $val = <<<END
                <h4 class="text" style="color: #ff2b39; display: inline-block">Cette liste a expiré</h4>
END;
        }

        $html .= <<<END
            <p class="text">Description : {$args[0]->description}</p>
            <p class="text" style="display: inline-block">Expiration : {$args[0]->expiration} </p>
            <p class="text" style="display: inline">$val</p>
            <p class="text">Liste publique : {$public}</p>
            <h4 class="titre2" style="font-size: 28px; text-decoration: underline green; text-decoration-thickness: 3px"> Messages :</h4>
            
END;
        if (isset($_SESSION['active']) && $_SESSION['active'] === true) {
            if (isset($args[3])){
                if (sizeof($args[3]) === 0){
                    $html .= <<<END
                        <div>
                            <p class="text"> Aucun message </p>
                        </div><br><br>
END;
                }else {
                    foreach ($args[3] as $row) {
                        if ($row->id_liste === $args[0]->no) {
                            $html .= <<<END
                        <div>
                            <p class="text" style="max-width: 200px; overflow-wrap: break-word; font-weight: bold; margin-top: 1px; margin-bottom: 1px"> {$row->nom} : </p>
                            <p class="text" style="max-width: 500px; overflow-wrap: break-word; margin-top: 1px; margin-bottom: 1px"> {$row->message} </p>
                        </div><br><br>
END;
                        }
                    }
                }
            }
        }else{
            if (isset($args[2])){
                if (sizeof($args[2]) === 0){
                    $html .= <<<END
                        <div>
                            <p class="text"> Aucun message </p>
                        </div><br><br>
END;
                }else {
                    foreach ($args[2] as $row) {
                        if ($row->id_liste === $args[0]->no) {
                            $html .= <<<END
                        <div style="max-width: 500px; overflow-wrap: break-word;">
                            <p class="text" style="font-weight: bold; display: inline-block; margin-top: 1px; margin-bottom: 1px"> {$row->nom} : </p>
                            <p class="text" style="display: inline-block; margin-top: 1px; margin-bottom: 1px"> {$row->message} </p>
                        </div>
END;
                        }
                    }
                }
            }
        }

        if (date('Y-m-d', strtotime($args[0]->expiration)) > date('Y-m-d')) {
            $html .= <<<END
        <form method="post">
            <label class="text" style="text-decoration: underline black" for="numLi"> Nouveau message </label><br><br>
            <label class="text" for="numLi"> Nom : </label><br>
            <input type="text" class="infosModif" name="nom" minlength="1" maxlength="250" size="30" placeholder="Entrez votre nom" ><br>
            <label class="text" for="numLi"> Message : </label><br>
            <textarea type="text" class="infosModif" name="message" cols="75" rows="5" minlength="1" maxlength="1000" size="50" placeholder="Entrez votre message" ></textarea><br><br>
            <input type="submit" name="publier" class="buttonAjoutItem" value="Publier le message">
        </form>
END;
        }


        if (isset($_SESSION['active']) && $_SESSION['active']===true){
            if ($_SESSION['compte_id'] === $args[0]->user_id ) {
                if (date('Y-m-d', strtotime($args[0]->expiration)) > date('Y-m-d')) {
                    $html .= <<<END
                <p class="text">URL DE CONSULTATION :  {$tab['lien2']}</p>
                <p class="textImportant" >URL DE MODIFICATION : </p> <p class="text" style="display: inline; margin-left: 0px;">{$tab['lienModif']}</p><br><br>
                <a href="{$tab['lienModif']}"><input type="button" class="buttonAfficherModif" name="modifier" value="Modifier infos"></a>
                
END;
                }else {
                    $html .= <<<END
                <p class="text">URL DE CONSULTATION :  {$tab['lien2']}</p>
END;
                }
            }
        }

        $html .= <<<END
        <br><h3 class="titreli2"> Les items de la liste </h3><br>
END;

        if (empty($args[1][0]->id)){
            $html .= <<<END
                <p class="text"> !! Cette liste est vide !! </p>
END;
        }else {
            foreach ($args[1] as $row) {
                $html .= <<<END
                <div>
                <h4><a class="titre3" href="{$tab['lien3']}?numIt={$row->id}"> {$row->nom}</a></h4>
                
END;

                if ($row->reserver === 0 || $row->reserver === null){
                    $html .= <<<END
                    <p class="text">Cet item n'est pas réservé</p>
END;
                }else if ($row->reserver === 1){
                    $html .= <<<END
                    <p class="text">Cet item est réservé</p>
END;
                }

                if ($row->img != null){
                    if (!str_starts_with($row->img, "http")){
                        $html.= <<<END
                        <img src="{$lien['basepath']}/web/img/{$row->img}" alt="{$row->descr}" style="max-width: 200px; max-height: 200px"><br><br><br>
END;
                    }else{
                        $html.= <<<END
                        <img src="{$row->img}" alt="{$row->descr}" style="max-width: 200px; max-height: 200px"><br><br><br>
END;
                    }
                }else{
                    $html.=<<<END
                    <p class="text">Aucune image disponible</p>
END;
                }
                $html.= <<<END
                </div>
END;
            }
        }
        $val =<<<END
            <section>
            $html
            </section>
END;

        return $val;

    }



    public function render($vars, $lien, $tab){

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
            session_unset();
            session_destroy();
        }else{
            $_SESSION['LAST_ACTIVITY'] = time();
        }


        $html = <<<END
        <!DOCTYPE html>
        <html>
            <head> 
                <link rel="stylesheet" href="{$lien['basepath']}/web/css/model.css">
                <title>MyWishList</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
            </head>
            <body>
            <div id="header">
                <div><a href={$tab['accueil']}><img src="{$lien['basepath']}/web/img/mwl2.png" class="centrage"/></a></div>
                <nav>
                    <ul>
                        <li class="liste"><a href="#">Liste</a>
                            <ul>
                                <li><a href={$tab['lien1']}>Publiques</a></li>
                                <li><a href={$tab['lien4']}>Créer</a></li>
                            </ul>
                        </li>
                            <ul>
                                <li class="liste"><a href={$tab['createurs']}>Créateurs</a>
                            </ul>
                        </li>
                    </ul>
                </nav>
END;
        if (!isset($_SESSION['active']) || $_SESSION['active'] === false){
            $html .= <<<END
                <nav>
                    <ul>
                        <li class="liste2"><a href={$tab['lien5']}>Connexion</a></li>
                            <ul>
                                <li class="liste"><a href={$tab['lien667']}>Credits</a>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
END;
        }else if ($_SESSION['active'] === true){
            $html .= <<<END
                <nav>
                <ul>
                <li class="liste3"><a href={$tab['compte']}>Compte</a></li>
                    <ul>
                        <li class="liste"><a href="{$tab['lien5']}">Deconnexion</a></li>
                            <ul>
                                <li class="liste"><a href={$tab['lien667']}>Credits</a>
                            </ul>
                        </li>
                    </ul>
                </ul>
                </nav>
            </div>
END;

        }

        switch ($vars){

            case 'liste detail':
                $content = $this->listeDetail($this->data, $lien, $tab);

                $html .= <<<END
            <div><h1 class="centrage2">Liste détaillée</h1></div>
                <div>
                    <div class="info">$content</div>
                </div>
            </body>
        </html>
END;
                break;
            case 'un item':
                $content = null;
                if( $this->data[0] !== null && $this->data[1] !== null) {
                    $content = $this->unItemHtml($this->data, $lien, $tab);
                }
                $html .= <<<END
            <div><h1 class="centrage2">Description d'un item</h1></div>
END;
                if ($content !== null) {
                    $html .=<<<END
                            <div>
                                <div class="info">$content</div>
                            </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;
                break;
        }

        return $html;
    }
}