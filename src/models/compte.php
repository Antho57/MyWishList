<?php


namespace mywishlist\models;


class compte extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'compte';
    protected $primaryKey = 'compte_id';
    //public $timestamps = false;

    public function compte(){
        return $this->hasMany('\mywishlist\models\liste', 'liste_id');
    }
}