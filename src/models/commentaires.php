<?php


namespace mywishlist\models;


class commentaires extends \Illuminate\Database\Eloquent\Model{

    protected $table = 'commentaires';
    protected $primaryKey = 'id';
    //public $timestamps = false;

    public function commentaires(){
        return $this->belongsTo('\mywishlist\models\liste', 'user_id');
    }
}