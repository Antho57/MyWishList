<?php


namespace mywishlist\view;


class VuePrincipale{


    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    private function allListe($liste, $tab):String {
        $html = '';
        $i =0;

        if ($liste != null){
            foreach($liste as $row) {
                $val =<<<END
            <a href="{$tab['lienPublique'][$i]}"><h3 class="titre3"> {$row->no} - {$row->titre}</h3></a><br><br>
END;
                $html = $html. $val;
                $i++;
            }
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

        foreach ($liste as $l){
            $val .= <<<END
             <h3 class="titre3">Login : {$l->login}</h3>
END;
        }
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
                <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8">
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
            case 'listes publiques':
                $content = $this->allListe($this->data, $tab);
                $html .= <<<END
                    <div><h1 class="centrage2">Listes publiques</h1></div>
                    <div>
                        <div class="info">$content</div>
                    </div>
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
                        <p class="text" style="margin-left: auto; margin-right: auto; text-align: center; color: darkcyan; font-weight: bold; text-decoration: underline;">Bienvenue sur le site My Wish List</p>
                        <p class="text" style="text-align: left; color: darkcyan; text-decoration: underline">Liste des fonctionnalités réalisées :</p>
                        <ol class="text" style="color: darkcyan;">
                            <li>&#9989 Afficher une liste de souhaits</li>
                            <li>&#9989 Afficher un item d'une liste</li>
                            <li>&#9989 Réserver un item</li>
                            <li>&#9989 Ajouter un message avec sa réservation</li>
                            <li>&#9989 Ajouter un message sur une liste</li>
                            <li>&#9989 Créer une liste</li>
                            <li>&#9989 Modifier les informations générales d'une de ses listes</li>
                            <li>&#9989 Ajouter des items</li>
                            <li>&#9989 Modifier un item</li>
                            <li>&#9989 Supprimer un item</li>
                            <li>&#9989 Rajouter une image à un item</li>
                            <li>&#9989 Modifier une image d'un item</li>
                            <li>&#9989 Supprimer une image d'un item</li>
                            <li>&#9989 Partager une liste</li>
                            <li>&#9989 Consulter les réservations d'une de ses listes avant échéance</li>
                            <li>&#9989 Consulter les réservations et messages d'une de ses listes après échéance</li>
                            <li>&#9989 Créer un compte</li>
                            <li>&#9989 S'authentifier</li>
                            <li>&#9989 Modifier son compte</li>
                            <li>&#9989 Rendre une liste publique</li>
                            <li>&#9989 Afficher les listes de souhaits publiques</li>
                            <li>&#9989 Créer une cagnotte sur un item</li>
                            <li>&#9989 Participer à une cagnotte</li>
                            <li>&#9989 Uploader une image</li>
                            <li>&#9989 Créer un compte participant</li>
                            <li>&#9989 Afficher la liste des créateurs</li>
                            <li>&#9989 Supprimer son compte</li>
                            <li>&#9989 Joindre des listes à son compte</li>
                        </ol>
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
                    <h4 class="titre6">Vos listes :</h4>
                        $info
                        <a href="{$tab['ajouterListeCompte']}"><input type="button" class="buttonDelete" name="addListeCompte" value="Ajouter une liste à mon compte"></a><br><br>
                    </div>
END;
                    if (isset($_POST['ajouterListe'])){
                    }
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
                    $info = $this->detailListeCompte($this->data, $tab);
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
            case 'createurs':
                $content =$this->listeCreateurs($this->data);

                $html .= <<<END
                <div><h1 class="centrage2">Liste des créateurs</h1></div>
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