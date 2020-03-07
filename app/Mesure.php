<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Mesure extends Model
{
  
    public $timestamps = false;
    protected $fillable = [
        'nom__mesure', 'unite',
    ];

    public function getMesures($id){
        $get = Mesure::select("nom_mesure", "mesures.id_mesure", "mesures_users.id_mesures","id_user", "value", "Date_debut")
        ->join("mesures_users", "mesures.id_mesure", "=", "mesures_users.id_mesures")
        ->where("id_user", $id)
        ->get();

        return $get;
    }

}
