<?php


namespace mywishlist\view;

class VueParticipant{

    private $data;

    public function __construct($data){
        $this->data = $data;
    }


    private function unItemHtml(\mywishlist\models\item $item, $lien):string {
        $html = <<<END
            <section>
            <h3 class="titre3">{$item->id} - {$item->nom}</h3>
            <p class="text">{$item->descr}</p>
            <h4 class="text">Tarif : {$item->tarif}</h4>
            <img src="{$lien['basepath']}/web/img/{$item->img}" class="imgItem">
</section>
END;
        return $html;
    }

    private function allListe($liste):String {
        $html = '';

        foreach($liste as $row) {
            $val =<<<END
                <h3 class="titre3"> {$row->no} - {$row->titre}</h3>
                <p class="text">Description : {$row->description}</p>
                <h4 class="text">Expiration : {$row->expiration}</h4><br>
END;
            $html = $html. $val;
        }

        $val =<<<END
            <section>
            $html
            </section>
END;

        return $val;

    }


    private function listeCreateurs($liste):String{
        $val = '';


        $val = <<<END
            <h3 class="titre3"> ID : {$liste->compte_id} &nbsp&nbsp&nbsp&nbsp - &nbsp&nbsp&nbsp&nbsp Login : {$liste->login}</h3>
END;

        return $val;
    }


    private function listeDetail($args, $lien, $tab):String {
        if ($args[0]->public){
            $public = 'Oui';
        }else{
            $public = 'Non';
        }
        $html = <<<END
            <h3 class="titreli"> {$args[0]->no} - {$args[0]->titre}</h3>
            <p class="text">Description : {$args[0]->description}</p>
            <p class="text">Expiration : {$args[0]->expiration}</p>
            <p class="text">Liste publique : {$public}</p>
            <p class="importante"> Il faut bien garder ce lien pour consulter la liste</p>
            <p class="text">URL DE CONSULTATION :  {$tab['lien2']}</p>
            <a href="{$tab['lienModif']}"><input type="button" class="buttonAfficherModif" name="modifier" value="Modifier infos"></a>
            <br>
END;

        foreach($args[1] as $row) {
            $val =<<<END
                <h4><a class="titre3" href="{$tab['lien3']}?numIt={$row->id}}"> {$row->nom}</a></h4>
                <img src="{$lien['basepath']}/web/img/{$row->img}" class="imgItem">
                <p class="text">Reservation : </p>
                <br>
END;
            $html = $html. $val;
        }

        $val =<<<END
            <section>
            $html
            </section>
END;

        return $val;

    }

    private function detailListeCompte($args, $tab):String {
        $html = '';
        $i =0;

        if ($args != null){
            foreach($args as $row) {
                $val =<<<END
            <a href="{$tab['lienCompte'][$i]}"><h3 class="titre3"> {$row->no} - {$row->titre}</h3></a>
            <p class="text">Description : {$row->description}</p>
            <h4 class="text">Expiration : {$row->expiration}</h4><br>
END;
                $html = $html. $val;
                $i++;
            }
        }



        $rep =<<<END
            <section>
            $html
            </section>
END;

        return $rep;

    }


    private function creerListe($args, $tab):String {
        if ($args->public){
            $public = 'Oui';
        }else{
            $public = 'Non';
        }
        $html = <<<END
            <h3 class="titreli"> {$args->no} - {$args->titre}</h3>
            <p class="text">Description : {$args->description}</p>
            <p class="text">Expiration : {$args->expiration}</p>
            <p class="text">Liste publique : {$public}</p>
            <p class="importante"> Il faut bien garder ce lien pour consulter la liste</p>
            <p class="text">URL DE CONSULTATION :  {$tab['lien2']}{$args->token}</p>
            <p class="importante"> Il faut bien garder ce lien pour modifier la liste</p>
            <p class="text">URL DE MODIFICATION :  {$tab['lien2']}{$args->token_modif}</p>
            <br>
END;

        $val =<<<END
            <section>
            $html
            </section>
END;

        return $val;

    }


    public function render($vars, $lien, $tab){

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
                                <li class="liste"><a href={$tab['lien3']}>Item</a>
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
            case 'listes publiques':
                $content = $this->allListe($this->data);
                $html .= <<<END
                    <div><h1 class="centrage2">Listes publiques</h1></div>
                    <div>
                        <div class="info">$content</div>
                    </div>
            </body>
        </html>
END;
                break;

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
                if( $this->data[0] !== null) {
                    $content = $this->unItemHtml($this->data[0], $lien);
                }
                $html .= <<<END
            <div><h1 class="centrage2">Description d'un item</h1></div>
            <div class="formulaire1">
                <form>
                    <br>
                    <label class="entrezNum" for="numIt"> Entrez le numéro de l'item que vous cherchez</label>
                    <input type="number" class="numI" name="numIt" minlength="1" maxlength="2" size="10" placeholder="Entrez un numéro" >
                    <input type="submit" class="numI" value="Rechercher">
                </form>
            </div>
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
            case 'creer liste':
                $content = null;
                if( $this->data !== null) {
                    $content = $this->creerListe($this->data, $tab);
                }

                $html .= <<<END
            <div><h1 class="centrage2">Création de liste</h1></div>
            
END;
                if ($content == null){
                    $date = date('Y-m-d');
                    $html .=<<<END
                            <div class="formulaire1">
                                <form method="post">
                                        <label class="infosL" for="numLi"> Entrez le titre de votre liste </label>
                                        <input type="text" class="infosL2" name="titre" minlength="1" maxlength="100" size="15" placeholder="Entrez le titre" ><br>
                                        <label class="infosL" for="numLi"> Entrez la description de votre liste </label>
                                        <input type="text" class="infosL2" name="description" minlength="1" maxlength="300" size="15" placeholder="Entrez la description" ><br>
                                        <label class="infosL" for="numLi"> Entrez la date d'expiration de votre liste </label>
                                        <input type="date" class="infosL2" name="expiration" min=$date> <br>
                                        <label class="infosL" for="numLi"> Rendre la liste publique </label>
                                        <input type="checkbox" class="infosL2" name="public"><br>
                                        <input type="submit" class="buttonCreer" value="Créer">
                                </form>
                            </div>
END;
                }else{
                    $html .=<<<END
                        <div>
                        <p class="connexionok">La liste est créée</p>
                        <div class="info">$content</div>
                        </div>
END;
                }
$html .=<<<END
            </body>
        </html>
END;
                break;
            case 'connexion':

                if($_SESSION['active'] === false){
                    $html .= <<<END
                    <div><h1 class="centrage2">Connexion</h1></div>
                    <div class="formulaire1">
                        <form method="post">
                            <label class="infosL" for="numLi"> Saisissez votre login </label>
                            <input type="text" class="infosL2" name="login" minlength="5" maxlength="20" size="15" placeholder="Login" ><br>
                            <label class="infosL" for="numLi"> Saisissez votre mot de passe </label>
                            <input type="password" class="infosL2" name="password" minlength="5" maxlength="60" size="15" placeholder="Password" ><br>
                            <input type="submit" class="buttonCreer" value="Connexion">
                            <p class="infosL">Inscrivez-vous <a class="ici" href="{$tab['inscription']}">ici</a></p> 
                        </form>
                    </div>
END;
                }else{
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Connecté !</p>
                        </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;

                break;
            case 'inscription':

                $html .= <<<END
            <div><h1 class="centrage2">Inscription</h1></div>
            
END;
                if ($_SESSION['active'] === false){
                    $html .= <<<END
                    <div class="formulaire1">
                        <form method="post">
                            <label class="infosL" for="numLi"> Saisissez votre login </label>
                            <input type="text" class="infosL2" name="login" minlength="5" maxlength="20" size="15" placeholder="Login" ><br>
                            <label class="infosL" for="numLi"> Saisissez votre mot de passe </label>
                            <input type="password" class="infosL2" name="password" minlength="5" maxlength="60" size="15" placeholder="Password" ><br>
                            <label class="infosL" for="numLi"> Resaisissez votre mot de passe </label>
                            <input type="password" class="infosL2" name="password2" minlength="5" maxlength="60" size="15" placeholder="Password" ><br>
                            <input type="submit" class="buttonCreer" value="Inscription">
                        </form>
                    </div>
END;
                }else{
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Incription réussie !</p>
                        </div>
END;
                }
                break;
            case 'accueil':

                $content = null;
                if( $this->data !== null) {
                    $content =<<<END
                        <section>
                        $html
                        </section>
END;
                }

                $html .= <<<END
            <div><h1 class="centrage2">Accueil</h1></div>
            
END;
                if ($content == null){
                    $html .= <<<END
                    <div class="formulaire1">
                        <p class="text">Voilà l'accueil !</p>
                    </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;
                break;

            case 'credits':
                $html .= <<<END
            <div><h1 class="centrage2">CREDITS DU SITE</h1></div><br><br>
            <div class="credit">
            <p>LOGO : BRANCATI SILVIO</p>
            <p>CREATEURS :</p>
            <p>BRIOT ANTHONY</p>
            <p>SPILMONT FRANCOIS</p>
            <p>SAKER LUCAS</p>
            <p>PISAN THOMAS</p>
            </div>
            
            </body>
        </html>
END;
                break;
            case 'deconnexion':

                if(!isset($_SESSION['active'])){
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Deconnecté avec succès !</p>
                        </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;

                break;
            case 'compte':

                if(isset($_SESSION['active']) && $_SESSION['active'] === true){
                    $info = $this->detailListeCompte($this->data, $tab);
                    $html .= <<<END
                    <div><h1 class="centrage2">Compte</h1></div>
                    <div class="formulaire1">
                        <form method="post">
                            <label class="infosL" for="numLi"> Login actuel </label>
                            <input type="text" class="infosL2" name="login" minlength="5" maxlength="20" size="15" value="{$_SESSION['login']}" readonly><br>
                            <label class="infosL" for="numLi"> Saisissez votre nouveau mot de passe </label>
                            <input type="password" class="infosL2" name="password" minlength="5" maxlength="60" size="15" placeholder="New Password" ><br>
                            <label class="infosL" for="numLi"> Resaisissez votre nouveau mot de passe </label>
                            <input type="password" class="infosL2" name="password2" minlength="5" maxlength="60" size="15" placeholder="New Password" ><br>
                            <input type="submit" class="buttonCreer" value="Valider">
                        </form>
                    </div>
                    <div class="formulaire1">
                            <a href="{$tab['supprimerCompte']}"><input type="button" class="text3" name="supprimerCompte" value="Supprimer mon compte"></a>
                    </div>
                    <div class="info">
                    $info
                    </div>
END;
                }else{
                    $_SESSION['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Mot de passe modifié !</p>
                        </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;

                break;

            case 'supprimerCompte':

                if(isset($_SESSION['active']) && $_SESSION['active'] === true){
                    $info = $this->detailListeCompte($this->data);
                    $html .= <<<END
                    <div><h1 class="centrage2">Supprimer mon compte</h1></div>
                    <div class="formulaire1">
                        <form method="post">
                            <p class="textRed">Etes vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.</p>
                            <input type="submit" class="buttonCreer" name="oui" value="Oui">
                            <a href="{$tab['compte']}"><input type="button" class="buttonCreer2" name="non" value="Non"></a>
                        </form>
                    </div>
                    <div class="info">
                    $info
                    </div>
END;
                }else{
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Compte supprimé !</p>
                        </div>
END;
                }
                $html .=<<<END
            </body>
        </html>
END;
                break;
            case 'modifier liste':

                $date = date('Y-m-d');
                if(!isset($_POST['buttonModifier'])){
                    $html .=<<<END
                <div><h1 class="centrage2">Modifier une liste</h1></div>
                    <div>
                    <form method="post">
                        <label class="textModif" for="numLi"> - Titre actuel : {$this->data->titre} </label> <br><br>
                        <label class="text" for="numLi"> Nouveau titre </label>
                        <input type="text" class="infosL2" name="NewTitre" minlength="1" maxlength="250" size="30" placeholder="Entrez le nouveau titre" ><br><br>
                        <label class="textModif" for="numLi"> - Descritpion actuelle : {$this->data->description} </label><br><br>
                        <label class="text" for="numLi"> Nouvelle description </label><br>
                        <textarea type="text" class="infosModif" name="NewDescription" cols="75" rows="5" minlength="1" maxlength="1000" size="50" placeholder="Entrez la nouvelle description" ></textarea><br><br>
                        <label class="textModif" for="numLi"> - Date d'expiration actuelle : {$this->data->expiration}  </label><br><br>
                        <label class="text" for="numLi"> Nouvelle date d'expiration</label><br>
                        <input type="date" class="infosModif" name="NewExpiration" min=$date> <br><br>
END;
                    if ($this->data->public === 1){
                        $html .=<<<END
                        <label class="textModif" for="numLi"> - La liste est publique  </label><br><br>
                        <label class="text" for="numLi"> Rendre la liste privée </label>
                        <input type="checkbox" class="infosL2" name="public"><br>
                        <input type="submit" class="buttonModifier" value="Valider les modifications">
                    </form>
                    </div>
                </body>
            </html>
END;
                    }else {
                        $html .=<<<END
                        <label class="textModif" for="numLi"> - La liste est privée </label><br><br>
                        <label class="text" for="numLi"> Rendre la liste publique </label>
                        <input type="checkbox" class="infosL2" name="public"><br>
                        <input type="submit" class="buttonModifier" value="Valider les modifications">
                    </form>-
                    </div>
                </body>
            </html>
END;
                    }

                }else {
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Modifications effectuées</p>
                        </div>
                    </body>
                </html>
END;
                }
                break;
            case 'createurs':
                $content = $this->listeCreateurs($this->data);

                $html .= <<<END
                    <div><h1 class="centrage2">Listes des créateurs </h1></div>
                    <div>
                        <div class="info">
                            $content
                        </div>
                    </div>
            </body>
        </html>
END;
        break;
        }

        return $html;
    }
}