<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Temps_prise;
use App\Rendez_vous;
use App\Patients_Medecins;
use App\Mesure_users;

class VerifyPolicy extends Model
{
    public function VerifyMedicament( Temps_prise $medicament){
        return Auth::user()->id === $medicament->id_user;
    }

    public function VerifyRendezVous( Rendez_vous $rendez_vous){
        return Auth::user()->id === $rendez_vous->id_patient;
    }

    public function VerifyMedecin( Patients_Medecins $medecin){
        return Auth::user()->id === $medecin->id_patient;
    }

    public function VerifyMesures( Mesure_users $mesure){
        return Auth::user()->id === $mesure->id_user;
    }
}
