<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Patients_Medecins extends Model
{
    protected $table = "patients_medecins";

    protected $primaryKey = "id_patients_medecins";

    protected $fillable = [
        'id_medecin', 'id_patient',
    ];

    public function getListe($id){
            $getListe = Patients_Medecins::select("id_medecin", "id_patient")
            ->where("patients_medecins.id_patient", $id)
            ->get();
            
            foreach($getListe as $item){

                    $getUser = User::select("id", "prenom", "nom", "Adresse", "id_type")
                    ->where("users.id", $item->id_medecin)
                    ->first();

                    $item->setAttribute("Medecin" ,$getUser);
                    $getSpecialite = medecin_specialite::select("medecin_specialite.id_specialite", "specialite.id_specialite", "id_medecin", "nom_specialite")
                    ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                    ->where("medecin_specialite.id_medecin", $item->id_medecin)
                    ->get();
                    $item->setAttribute("Specialite" ,$getSpecialite);
                   
            }
            return $getListe;
           
    }
    public function getMedecinProspect(){
        $get = Patients_Medecins::select("*")
            ->join("users", "users.id", "=", "patients_medecins.id_medecin")
            ->where([
                ["patients_medecins.id_patient", Auth::user()->id],
                ["users.id_type", 4],
            ])
            ->get();
        return $get;
    }

}
