<?php

namespace App\Http\Controllers\Medecin;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use App\Patients_Medecins;
use App\VerifyPolicy;
use Auth;

class ListePatientController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }  
    //////////////////Get Informations of patients

    public function index(){
        $getPatient = Patients_Medecins::where("id_medecin", Auth::user()->id)->get();
        foreach($getPatient as $item){
            $table = [];
            $User = User::where("id", $item->id_patient)->first();
            array_push($table, $User);
            $item->setAttribute("patient", $table[0]);
        } 
        return response()->json($getPatient, 200);
    }
   
////////////////Delete a mesures 
    public function destroy($id){
        $getPatients_Medecins = Patients_Medecins::find($id);
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedecin($getPatients_Medecins)){
            $getPatients_Medecins->delete();
            return response()->json("La mesure a été supprimé avec succès", 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
    }   
}
