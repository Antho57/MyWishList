<?php


namespace mywishlist\Connexion;


use mywishlist\models\compte;

class Authentication
{
    public static function createUser( $userName, $password ){
    // vérifier la conformité de $password avec la police
    // si ok : hacher $password
    // créer et enregistrer l'utilisateur
    }

    public static function authenticate ( $username, $password ) {
        $compte = compte::query()->where('login', 'like', strip_tags($username))->first();
        if(isset($compte)){
            if (password_verify(strip_tags($password), $compte['password'])){
                session_start();
                $_SESSION['active'] = true;
                $_SESSION['login'] = strip_tags($username);
            }
        }
    }

    public static function loadProfile( $uid ) {
    // charger l'utilisateur et ses droits
    unset($_SESSION);
    $_SESSION = null;
    }

    public static function checkAccessRights ( $required ) {
    //si Authentication::$profil['level'] < $required
    // throw new AuthException ;
    }
}