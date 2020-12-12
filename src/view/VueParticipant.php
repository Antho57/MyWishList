<?php


namespace mywishlist\view;


class VueParticipant{

    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    private function unItemHtml(\mywishlist\models\item $item):string {
        $html = <<<END
            <section>
            <h3 class="titre3">{$item->id} - {$item->nom}</h3>
            <p class="text">{$item->descr}</p>
            <h4 class="text">Tarif : {$item->tarif}</h4>
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




    private function listeItem($args):String {
        $html = <<<END
            <h3 class="titreli"> {$args[0]->no} - {$args[0]->titre}</h3><br>
END;

        foreach($args[1] as $row) {
            $val =<<<END
                <h4 class="titre3"> {$row->id} : {$row->nom}</h4>
                <p class="text">Description : </p>
                <p class="text2">{$row->descr}</p>
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


    public function render($vars, $lien){

        $html ='';

        switch ($vars){
            case 1:
                $content = $this->allListe($this->data);
                $html = <<<END
        <!DOCTYPE html>
        <html>
            <head> 
                <link rel="stylesheet" href="{$lien['basepath']}/web/css/model.css">
                <title>MyWishListe</title>
            </head>
            <body>
            <div id="header"><h1 class="centrage">MyWishListe</h1>
                <nav>
                    <ul>
                        <li class="liste"><a href="#">Liste</a>
                            <ul>
                            <li><a href="../liste/all">All</a></li>
                            <li><a href="../liste/items/2">Items</a></li>
                            </ul>
                        </li>
                        <li class="liste"><a href="#">Item</a>
                            <ul>
                            <li><a href="../item/1">Item 1</a></li>
                            <li><a href="../item/2">Item 2</a></li>
                            </ul>
                        </li
                    </ul>
                </nav>
            </div>
                    <div><h1 class="centrage2">Toutes les listes</h1></div>
                    <div class="content">
                        <div class="info">$content</div>
                    </div>
            </body>
        </html>
END;
                break;

            case 2:
                $content = $this->listeItem($this->data);
                $html = <<<END
        <!DOCTYPE html>
        <html>
            <head> 
                <link rel="stylesheet" href="{$lien['basepath']}/web/css/model.css">
                <title> MyWishListe </title>
            </head>
            <body>
            <div id="header"><h1 class="centrage">MyWishListe</h1>
                <nav>
                    <ul>
                        <li class="liste"><a href="#">Liste</a>
                            <ul>
                            <li><a href="../liste/all">All</a></li>
                            <li><a href="../liste/items/2">Items</a></li>
                            </ul>
                        </li>
                        <li class="liste"><a href="#">Item</a>
                            <ul>
                            <li><a href="../../item/1">Item 1</a></li>
                            <li><a href="../../item/2">Item 2</a></li>
                            </ul>
                        </li
                    </ul>
                </nav>
            </div>
            <div><h1 class="centrage3">Les items d'une liste donnée</h1></div>
                    <div class="content">
                        <div class="info">$content</div>
                    </div>
            </body>
        </html>
END;
                break;
            case 3:
                $content = $this->unItemHtml($this->data[0]);
                $html = <<<END
        <!DOCTYPE html>
        <html>
            <head> 
                <link rel="stylesheet" href="{$lien['basepath']}/web/css/model.css">
                <title> MyWishListe </title>
            </head>
            <body>
            <div id="header"><h1 class="centrage">MyWishListe</h1>
                <nav>
                    <ul>
                        <li class="liste"><a href="#">Liste</a>
                            <ul>
                            <li><a href="../liste/all">All</a></li>
                            <li><a href="../liste/items/2">Items</a></li>
                            </ul>
                        </li>
                        <li class="liste"><a href="#">Item</a>
                            <ul>
                            <li><a href="../item/1">Item 1</a></li>
                            <li><a href="../item/2">Item 2</a></li>
                            </ul>
                        </li
                    </ul>
                </nav>
            </div>
            <div><h1 class="centrage2">Description d'un item</h1></div>
                    <div class="content">
                        <div class="info">$content</div>
                    </div>
            </body>
        </html>
END;
                break;
        }

        return $html;
}
//<link rel="stylesheet" href="{$vars['basepath']}/wish.css"
}