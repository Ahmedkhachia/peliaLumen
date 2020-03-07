<?php

namespace App\Http\Controllers\Patient;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Patients_Medecins;
use App\FeedBack;
use App\Medicament; 
use App\Mesure;
use App\Rendez_vous;
use Auth;

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
            $Medicament = new Medicament();
            $Mesures = new Mesure;
            $RendezVous = new Rendez_vous;
            $Medecins = new Patients_Medecins;
            $getRendezVousToday = $RendezVous->getRndvToday();
            $getMedicamentToday = $Medicament->getMedicamentToday();
            return response()->json([
                'medicament' =>$getMedicamentToday,
                'rendez-vous' =>$getRendezVousToday,
            ]); 
    }
}
