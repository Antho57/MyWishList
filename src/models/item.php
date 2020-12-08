<?php

namespace mywishlist\models;

class item extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'item';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    public function list(){
        return $this->belongsTo('\mywishlist\models\liste', 'liste_id');
    }


}