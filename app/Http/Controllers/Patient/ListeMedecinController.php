<?php

namespace App\Http\Controllers\Patient;
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\User;
use App\Medecins;
use App\Medicament;
use App\medecin_specialite;
use App\Patients_Medecins;
use App\Mesure;
use App\Specialite;
use App\Ville;
use App\User_info;
use App\VilleUsers;
use Auth;

class ListeMedecinController extends BaseController
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $Listes = new Patients_Medecins();
        $getListe = $Listes->getListe(Auth::user()->id);
        return response()->json($getListe, 200);
       
    }

    public function create(){
        $getSpecialite = Specialite::all();
        $getVille = Ville::all();
        return response()->json([
            "specialite" => $getSpecialite,
            "ville" => $getVille,
        ], 200);
    }

    public function store(Request $request){
        $getUser = new User;
        $getVille = new VilleUsers;
        $getPatients_Medecin = new Patients_Medecins;
        $getMedecinSpecialite = new medecin_specialite;
        
        $getUser->prenom = $request->prenom;
        $getUser->nom = $request->nom;
        $getUser->id_type = 4;
        $getUser->phone = $request->phone;
        $getUser->Adresse = $request->adresse;
        $getUser->save();
        $getVille->id_ville = $request->villeSelect;
        $getVille->id_user = $getUser->id;
        $getVille->save();
        $getMedecinSpecialite->id_medecin = $getUser->id;
        $getMedecinSpecialite->id_specialite =$request->specialiteSelect;
        $getMedecinSpecialite->save();
        $getPatients_Medecin->id_patient = Auth::user()->id;
        $getPatients_Medecin->id_medecin = $getUser->id;
        $getPatients_Medecin->save();
        $getSpecialiteData = Specialite::where("id_specialite", $getMedecinSpecialite->id_specialite)->get();
        $getPatients_Medecin->setAttribute("medecin", $getUser);
        $getPatients_Medecin->setAttribute("specialite", $getSpecialiteData);
        return response()->json($getPatients_Medecin, 200);

    }

    public function edit($id){
        $getPatients_Medecin = Patients_Medecins::where("id_patients_medecins", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedecin($getPatients_Medecin)){
            $getMedecinSpecialite = medecin_specialite::where("id_medecin", $getPatients_Medecin->id_medecin)->get();
            $getSpecialiteData = Specialite::where("id_specialite", $getMedecinSpecialite->id_specialite)->get();
            $getMedecin = User::where("id", $getPatients_Medecin->id_medecin)->first();
            $getPatients_Medecin->setAttribute("medecin", $getMedecin);
            $getPatients_Medecin->setAttribute("specialite", $getSpecialiteData);
            return response()->json($getPatients_Medecin, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
       
    }

    public function update(Request $request, $id){
        $getPatients_Medecin = Patients_Medecins::where("id_patients_medecins", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedecin($getPatients_Medecin)){
            $getUser = User::where("id", $getPatients_Medecin->id_medecin)->first();
            $getVille = VilleUsers::where("id_user", $getUser->id)->first();
            
            $getMedecinSpecialite = medecin_specialite::where("id_medecin", $getUser->id)->get();
            
            $getUser->prenom = $request->prenom;
            $getUser->nom = $request->nom;
            $getUser->id_type = 4;
            $getUser->phone = $request->phone;
            $getUser->Adresse = $request->adresse;
            $getUser->save();
            $getVille->id_ville = $request->villeSelect;
            $getVille->id_user = $getUser->id;
            $getVille->save();
            $getMedecinSpecialite->id_medecin = $getUser->id;
            $getMedecinSpecialite->id_specialite =$request->specialiteSelect;
            foreach ($getMedecinSpecialite as $item){
                $item->save();
            }
            $getPatients_Medecin->id_patient = Auth::user()->id;
            $getPatients_Medecin->id_medecin = $getUser->id;
            $getPatients_Medecin->save();
            $getSpecialiteData = Specialite::where("id_specialite", $getMedecinSpecialite->id_specialite)->get();
            $getPatients_Medecin->setAttribute("medecin", $getUser);
            $getPatients_Medecin->setAttribute("specialite", $getSpecialiteData);
            return response()->json($getPatients_Medecin, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
        
    }
    
    public function destroy($id){
        $getPatients_Medecins = Patients_Medecins::find($id);
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedecin($getPatients_Medecin)){
            $getMedecin = User::where("id", $getPatients_Medecins->id_medecin)->first();
            $getVille = VilleUsers::where("id_user", $getMedecin->id)->first();
            $getMedecinSpecialite = medecin_specialite::where("id_medecin", $getMedecin->id)->get();
            
            $getPatients_Medecins->delete();
            $getVille->delete();
            foreach ($getMedecinSpecialite as $item){
                $item->delete();
            }
            $getMedecin->delete();
            return response()->json("Le médecin a été supprimer avec succès", 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
       
    }
}
