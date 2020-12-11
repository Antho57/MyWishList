<?php


namespace mywishlist\view;


class VueParticipant{

    private $data;

    public function __construct($data){
        $this->data = $data;
    }

    private function unItemHtml(\mywishlist\models\item $item):string {
        $html = <<<END
            <section class="titre">
            <h3>{$item->nom}</h3>
            <p>{$item->descr}</p>
            <h4>tarif : {$item->tarif}</h4>
</section>
END;
        return $html;
    }

    private function allListe($liste):String {
        $html = '';

        foreach($liste as $row) {
            $val =<<<END
                <h3> {$row->no} - {$row->titre}</h3>
                <p>Description : {$row->description}</p>
                <h4>Expiration : {$row->expiration}</h4><br><br>
END;
            $html = $html. $val;
        }

        $val =<<<END
            <section class="titre">
            $html
            </section>
END;

        return $val;

    }




    private function listeItem($args):String {
        $html = <<<END
            <h3> {$args[0]->no} - {$args[0]->titre}</h3><br>
END;

        foreach($args[1] as $row) {
            $val =<<<END
                <h4> - {$row->id} : {$row->nom}</h4>
                <p>Description : <br><br>{$row->descr}</p><br>
END;
            $html = $html. $val;
        }

        $val =<<<END
            <section class="titre">
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
                <link rel="stylesheet" href="{$lien['basepath']}/model.css">
                <title>MyWishListe</title>
            </head>
            <body>
                $content
            
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
                <link rel="stylesheet" href="{$lien['basepath']}/model.css">
                <title> MyWishListe </title>
            </head>
            <body>
                $content
            
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
                <link rel="stylesheet" href="{$lien['basepath']}/model.css">
                <title> MyWishListe </title>
            </head>
            <body>
                <h1 class="titre"> Description d'un item </h1>
                $content
            
            </body>
        </html>
END;
                break;
        }

        return $html;
}
//<link rel="stylesheet" href="{$vars['basepath']}/wish.css"
}