<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VilleUsers extends Model
{
    protected $table = "ville_users";
    protected $primaryKey = "id_ville";

    public function getVilles($id){

        $villes = VilleUsers::select("*")
        ->join('users', 'users.id', '=', 'ville_users.id_user')
        ->join('ville', 'ville.id_ville', '=', 'ville_users.id_ville')
        ->where("ville_users.id_user", $id)
        ->first();

        return $villes;
    }
}
