<?php


namespace mywishlist\bd;

use Illuminate\Database\Capsule\Manager as DB;

class connexion{

    public static function start(String $file){

        $db = new DB();
        $db->addConnection(parse_ini_file($file));

        $db->setAsGlobal();
        $db->bootEloquent();
    }
}