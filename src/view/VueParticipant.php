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




    private function listeDetail($args, $lien, $tab):String {
        $html = <<<END
            <h3 class="titreli"> {$args[0]->no} - {$args[0]->titre}</h3>
            <p class="text">Description : {$args[0]->description}</p>
            <p class="text">Expiration : {$args[0]->expiration}</p>
            <p class="text">Token unique : {$args[0]->token}</p>
            <p class="importante"> Il faut bien garder ce lien pour accéder à la liste en mode modification</p>
            <p class="text">URL :  {$tab['lien2']}{$args[0]->token}</p>
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


    private function creerListe($args, $tab):String {
        $html = <<<END
            <h3 class="titreli"> {$args->no} - {$args->titre}</h3>
            <p class="text">Description : {$args->description}</p>
            <p class="text">Expiration : {$args->expiration}</p>
            <p class="text">Token unique : {$args->token}</p>
            <p class="importante"> Il faut bien garder ce lien pour accéder à la liste en mode modification</p>
            <p class="text">URL :  {$tab['lien2']}{$args->token}</p>
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
            </head>
            <body>
            <div id="header">
                <div><a href={$tab['accueil']}><img src="{$lien['basepath']}/web/img/mwl2.png" class="centrage"/></a></div>
                <nav>
                    <ul>
                        <li class="liste"><a href="#">Liste</a>
                            <ul>
                                <li><a href={$tab['lien1']}>All</a></li>
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
                        <li class="liste2"><a href="{$tab['lien5']}">Deconnexion</a></li>
                            <ul>
                                <li class="liste"><a href={$tab['lien667']}>Credits</a>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
END;

        }

        switch ($vars){
            case 1:
                $content = $this->allListe($this->data);
                $html .= <<<END
                    <div><h1 class="centrage2">Toutes les listes</h1></div>
                    <div>
                        <div class="info">$content</div>
                    </div>
            </body>
        </html>
END;
                break;

            case 2:
                $content = $this->listeDetail($this->data, $lien, $tab);

                $html .= <<<END
            <div><h1 class="centrage2">Les items d'une liste donnée</h1></div>
                <div>
                    <div class="info">$content</div>
                </div>
            </body>
        </html>
END;
                break;
            case 3:
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
            case 4:
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
                                <form>
                                        <label class="infosL" for="numLi"> Entrez le titre de votre liste </label>
                                        <input type="text" class="infosL2" name="titre" minlength="1" maxlength="100" size="15" placeholder="Entrez le titre" ><br>
                                        <label class="infosL" for="numLi"> Entrez la description de votre liste </label>
                                        <input type="text" class="infosL2" name="description" minlength="1" maxlength="300" size="15" placeholder="Entrez la description" ><br>
                                        <label class="infosL" for="numLi"> Entrez la date d'expiration de votre liste </label>
                                        <input type="date" class="infosL2" name="expiration" min=$date <br>
                                        <input type="submit" class="buttonCreer" value="Créer">
                                </form>
                            </div>
END;
                }else{
                    $html .=<<<END
                        <div>
                        <p class="annonce">La liste est créée</p>
                        <div class="info">$content</div>
                        </div>
END;
                }
$html .=<<<END
            </body>
        </html>
END;
                break;
            case 5:

                $html .= <<<END
            <div><h1 class="centrage2">Connexion</h1></div>
            
END;
                if($_SESSION['active'] === false){
                    $html .= <<<END
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

                break;
            case 6:

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
            case 7:
                //session_destroy();
                $content = null;
                if( $this->data !== null) {
                    $content =<<<END
                        <section>
                        $html
                        </section>
END;
                }
//                $html .= <<<END
//                    <form action="{$lien['basepath']}/src/view/logout.php">
//                        <input type="submit" value="Deconnexion" >
//                    </form>
//END;
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
                break;

            case 667:
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
            case 8:
                $html .= <<<END
            <div><h1 class="centrage2">Deconnexion</h1></div>
            
END;
                if(!isset($_SESSION['active'])){
                    $html .=<<<END
                        <div>
                        <p class="connexionok">Deconnecté avec succès !</p>
                        </div>
END;
                }

                break;
        }

        return $html;
    }
}