<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class medecin_specialite extends Model
{
    protected $table = "medecin_specialite";
    protected $primaryKey = "id_medecin";

    public function getSpecialite($id){

        // $specialite = medecin_specialite::select("*")
        // ->join('users', 'users.id', '=', 'medecin_specialite.id_medecin')
        // ->join('specialite', 'medecin_specialite.id_specialite', '=', 'specialite.id_specialite')
        // ->where("medecin_specialite.id_medecin", $id)
        // ->get();

        $getSpecialite = medecin_specialite::select("id_specialite", "id_medecin")
        ->where("medecin_specialite.id_medecin", $id)
        ->get();
        foreach ($getSpecialite as $key){
            $getSpecialiteInfo = Specialite::select("id_specialite" ,'nom_specialite', 'nom_specialite_arab')
            ->where('Specialite.id_specialite', $key->id_specialite)
            ->first();
            $key->setAttribute("specialite" ,$getSpecialiteInfo->specialite);
        }

        return $getSpecialite;
    }
}
