<?php


namespace mywishlist\Connexion;


use mywishlist\models\compte;
use mywishlist\models\liste;

class Authentication
{
    public static function createUser( $userName, $password ){
        $compte = compte::query()->where('login', 'like', strip_tags($userName))->first();
        if(isset($compte)){
            echo "login déjà utilisé";
        }else{
            session_start();
            $c = new compte();
            $c->login = strip_tags($userName);
            $c->password = strip_tags(password_hash($password, PASSWORD_BCRYPT));
            $c->timestamps = false;
            $c->save();
            $_SESSION['active'] = true;
            $_SESSION['login'] = strip_tags($userName);
        }
    }

    public static function authenticate ( $username, $password ) {
        $compte = compte::query()->where('login', 'like', strip_tags($username))->first();
        if(isset($compte)){
            if (password_verify(strip_tags($password), $compte['password'])){
                session_start();
                $_SESSION['active'] = true;
                $_SESSION['login'] = strip_tags($username);
                $_SESSION['compte_id'] = $compte->compte_id;
            }
        }
    }

    public static function loadProfile( $uid ) {
    // charger l'utilisateur et ses droits
    unset($_SESSION);
    $_SESSION = null;
    }

    public static function modifyUser($userName, $password){
        $compte = compte::query()->where('login', 'like', strip_tags($userName))->first();
        $compte->login = strip_tags($userName);
        $compte->password = strip_tags(password_hash($password, PASSWORD_BCRYPT));
        $compte->timestamps = false;
        $compte->save();
        $_SESSION['login'] = strip_tags($userName);
    }

    public static function checkAccessRights ( $required ) {
    //si Authentication::$profil['level'] < $required
    // throw new AuthException ;
    }

    public static function deleteUser($username){
        liste::query()->where('user_id', '=', $_SESSION['compte_id'])->delete();
        compte::query()->where('login', 'like', strip_tags($username))->delete();
        unset($_SESSION);
    }
}