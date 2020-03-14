<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Rendez_vous;
use Auth;
use App\Patients_Medecins;
use Carbon\Carbon;
use App\User;
use App\User_info;
use App\medecin_specialite;


class RendezVousController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('isActive');
    }
    
    public function index(){
        $get = new Rendez_vous;
        $getRendezVous = $get->index(Auth::user()->id);
        return response()->json($getRendezVous, 200);
    }

    public function create(){
        $getMedecin = new Patients_Medecins;
        $getData = $getMedecin->getMedecinProspect();
        return response()->json($getData, 200);  
    }
    
    public function store(Request $request){
        $get = new Rendez_vous;
        $get->id_medecin = $request->id_medecin;
        $get->id_patient = Auth::user()->id;
        $get->Date_start = $request->date." ".$request->time;
        $get->etat = 1;
        $get->heure_rappel = $request->heure_rappel;
        $get->motif = $request->note;
        $get->save();
        
        $getUsersInfo = User_info::where("user_id", $get->id_medecin)->first();
        $getMedecin = User::where("id", $get->id_medecin)->first();
        $getSpecialite = new medecin_specialite;
        $getSpecialiteData = $getSpecialite->getSpecialite($get->id_medecin);

        $get->setAttribute("medecin", $getMedecin);
        $get->setAttribute("specialite", $getSpecialiteData);
        $get->setAttribute("users_info", $getUsersInfo);

        return response()->json($get, 200);
    }

    public function edit($id){
        $getRendez_vous =  Rendez_vous::where("id_rendez_vous", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyRendezVous($getRendez_vous)){
            $getUsersInfo = User_info::where("user_id", $getRendez_vous->id_medecin)->first();
            $getMedecin = User::where("id", $getRendez_vous->id_medecin)->first();
            $getSpecialite = new medecin_specialite;
            $getSpecialiteData = $getSpecialite->getSpecialite($getRendez_vous->id_medecin);

            $getRendez_vous->setAttribute("medecin", $getMedecin);
            $getRendez_vous->setAttribute("specialite", $getSpecialiteData);
            $getRendez_vous->setAttribute("users_info", $getUsersInfo);

            return response()->json($getRendez_vous, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
        
    }

    public function update(Request $request, $id){
        $get =  Rendez_vous::where("id_rendez_vous", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyRendezVous($get)){
            $get->id_medecin = $request->id_medecin;
            $get->id_patient = Auth::user()->id;
            $get->Date_start = $request->date." ".$request->time;
            $get->etat = 1;
            $get->motif = $request->motif;
            $get->heure_rappel = $request->heure_rappel;
            $get->Note = $request->note;
            $get->save();
            
            $getUsersInfo = User_info::where("user_id", $get->id_medecin)->first();
            $getMedecin = User::where("id", $get->id_medecin)->first();
            $getSpecialite = new medecin_specialite;
            $getSpecialiteData = $getSpecialite->getSpecialite($get->id_medecin);
    
            $get->setAttribute("medecin", $getMedecin);
            $get->setAttribute("specialite", $getSpecialiteData);
            $get->setAttribute("users_info", $getUsersInfo);
    
            return response()->json($get, 200);
    
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
       
    }

    public function destroy(Request $request, $id){
        $get = Rendez_vous::find($id);
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyRendezVous($get)){
            $get->delete();
            return response()->json("Le rendez_vous a été supprimer avec succès", 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
        
    }

}
