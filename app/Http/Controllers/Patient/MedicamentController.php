<?php

namespace App\Http\Controllers\Patient;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Temps_prise;
use App\Medicament;
use App\User;
use App\Jour_prise;
use App\Frequence;
use App\VerifyPolicy;
use Auth;

class MedicamentController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //////////////////Get Informations of médicaments

    public function index($id){
        $User = new User();
        $User_medicament = new Medicament();
        $medicament = $User_medicament->get_medicament($id);
        return response()->json($medicament, 200);
    }

    //////////////////Ajout d'un médicament

    public function store(Request $request){
        $getTemps_prise = new Temps_prise;
        $getFrequence = new Frequence;
        $getJoursPrise = new Jour_prise;
        $jours = [];
        $getTemps_prise->CODE = $request->CODE;
        $getTemps_prise->id_user = Auth::user()->id;
        $getTemps_prise->id_medecin = $request->id_medecin;
        $getTemps_prise->stock_medicament = $request->stock;
        $getTemps_prise->save();

        foreach ($request->frequence as $item){
            $getFrequence->temps = $item['temps'];
            $getFrequence->dose = $item['dose'];
            $getFrequence->id_temps = $getTemps_prise->id_temps;
        }
        
        $getFrequence->save();
        $getJoursPrise->id_temps = $getTemps_prise->id_temps;
        $getJoursPrise->duree_court = $request->jours_prise['duree_court'];
        $getJoursPrise->periode = $request->jours_prise['periode'];

        if($request->input("methode") == 0){
            $getJoursPrise->methode = $request->jours_prise['methode'];
            $getJoursPrise->Monday = $request->jours_prise["jours"]['Monday'];
            $getJoursPrise->Tuesday = $request->jours_prise["jours"]['Tuesday'];
            $getJoursPrise->Wednesday = $request->jours_prise["jours"]['Wednesday'];
            $getJoursPrise->Thursday = $request->jours_prise["jours"]['Thursday'];
            $getJoursPrise->Friday = $request->jours_prise["jours"]['Friday'];
            $getJoursPrise->Saturday = $request->jours_prise["jours"]['Saturday'];
            $getJoursPrise->Sunday = $request->jours_prise["jours"]['Sunday']; 
            $getJoursPrise->intervale = 0;
        }else{
            $getJoursPrise->methode = 0;
            $getJoursPrise->Monday = 0;
            $getJoursPrise->Tuesday = 0;
            $getJoursPrise->Wednesday = 0;
            $getJoursPrise->Thursday = 0;
            $getJoursPrise->Friday = 0;
            $getJoursPrise->Saturday = 0;
            $getJoursPrise->Sunday = 0;
            $getJoursPrise->intervale = $request->jours->intervale;
            $getJoursPrise->date_prochaine = $request->jours->date_prochaine;
        }
        $getJoursPrise->save();
        $getMedicaments = Medicament::where("CODE", $request->CODE)->first();
        $getTemps_prise->setAttribute("medicament", $getMedicaments);
        $getTemps_prise->setAttribute("frequence", $request->frequence);
        $getJoursPrise->setAttribute("jours" ,$jours+=array("Monday" => $getJoursPrise->Monday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Tuesday" => $getJoursPrise->Tuesday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Wednesday" => $getJoursPrise->Wednesday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Thursday" => $getJoursPrise->Thursday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Friday" => $getJoursPrise->Friday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Saturday" => $getJoursPrise->Saturday));
                $getJoursPrise->setAttribute("jours" ,$jours+=array("Sunday" => $getJoursPrise->Sunday));
                unset($getJoursPrise->Monday);
                unset($getJoursPrise->Tuesday);
                unset($getJoursPrise->Wednesday);
                unset($getJoursPrise->Thursday);
                unset($getJoursPrise->Friday);
                unset($getJoursPrise->Saturday);
                unset($getJoursPrise->Sunday);
        $getTemps_prise->setAttribute("jours_prise", $getJoursPrise);
        return response()->json($getTemps_prise, 200);
    }

    //////////////Return Data to view Update

    public function edit($id){
        $getTemps_prise =  Temps_prise::where("id_temps", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedicament($getTemps_prise)){
            $getFrequence =  Frequence::where("id_temps", $id)->get();
            $getJoursPrise =  Jour_prise::where("id_temps", $id)->first();

            $getTemps_prise->setAttribute("frequence", $getFrequence);
            $getTemps_prise->setAttribute("jour_prise", $getJoursPrise);
            return response()->json($getTemps_prise, 200);
        }
        else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
        
    }

    ///////////////Modification d'un médicament

    public function update(Request $request, $id){
        $getTemps_prise =  Temps_prise::where("id_temps", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedicament($getTemps_prise)){
            $getFrequence =  Frequence::where("id_temps", $id)->get();
            $getJoursPrise =  Jour_prise::where("id_temps", $id)->first();
            $jours = [];
            $getTemps_prise->CODE = $request->CODE;
            $getTemps_prise->id_user = Auth::user()->id;
            $getTemps_prise->id_medecin = $request->id_medecin;
            $getTemps_prise->stock_medicament = $request->stock;
            $getTemps_prise->save();
            $i = 0;
            foreach ($request->frequence as $key){
                $getFrequence[$i]->temps = $key['temps'];
                $getFrequence[$i]->dose = $key['dose'];
                $getFrequence[$i]->id_temps = $getTemps_prise->id_temps;
                $i++;
            }
            $getFrequence->each->save();
            $getJoursPrise->id_temps = $getTemps_prise->id_temps;
            $getJoursPrise->duree_court = $request->jours_prise['duree_court'];
            $getJoursPrise->periode = $request->jours_prise['periode'];

            if($request->methode == 0){
                $getJoursPrise->methode = $request->jours_prise['methode'];
                $getJoursPrise->Monday = $request->jours_prise["jours"]['Monday'];
                $getJoursPrise->Tuesday = $request->jours_prise["jours"]['Tuesday'];
                $getJoursPrise->Wednesday = $request->jours_prise["jours"]['Wednesday'];
                $getJoursPrise->Thursday = $request->jours_prise["jours"]['Thursday'];
                $getJoursPrise->Friday = $request->jours_prise["jours"]['Friday'];
                $getJoursPrise->Saturday = $request->jours_prise["jours"]['Saturday'];
                $getJoursPrise->Sunday = $request->jours_prise["jours"]['Sunday']; 
                $getJoursPrise->intervale = 0;
            }else{
                $getJoursPrise->methode = 0;
                $getJoursPrise->Monday = 0;
                $getJoursPrise->Tuesday = 0;
                $getJoursPrise->Wednesday = 0;
                $getJoursPrise->Thursday = 0;
                $getJoursPrise->Friday = 0;
                $getJoursPrise->Saturday = 0;
                $getJoursPrise->Sunday = 0;
                $getJoursPrise->intervale = $request->jours->intervale;
                $getJoursPrise->date_prochaine = $request->jours->date_prochaine;
            }
            $getJoursPrise->save();
            $getMedicaments = Medicament::where("CODE", $request->CODE)->first();
            $getTemps_prise->setAttribute("medicament", $getMedicaments);
            $getTemps_prise->setAttribute("frequence", $request->frequence);
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Monday" => $getJoursPrise->Monday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Tuesday" => $getJoursPrise->Tuesday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Wednesday" => $getJoursPrise->Wednesday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Thursday" => $getJoursPrise->Thursday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Friday" => $getJoursPrise->Friday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Saturday" => $getJoursPrise->Saturday));
            $getJoursPrise->setAttribute("jours" ,$jours+=array("Sunday" => $getJoursPrise->Sunday));
            unset($getJoursPrise->Monday);
            unset($getJoursPrise->Tuesday);
            unset($getJoursPrise->Wednesday);
            unset($getJoursPrise->Thursday);
            unset($getJoursPrise->Friday);
            unset($getJoursPrise->Saturday);
            unset($getJoursPrise->Sunday);
            $getTemps_prise->setAttribute("jours_prise", $getJoursPrise);
            return response()->json($getTemps_prise, 200);
        }else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
        
    }

    ///////////////Suppression d'un médicament

    public function destroy($id){
        $getTemps_prise =  Temps_prise::where("id_temps", $id)->first();
        $getVerify = new VerifyPolicy;
        if($getVerify->VerifyMedicament($getTemps_prise)){
            $getFrequence =  Frequence::where("id_temps", $id)->get();
            $getJoursPrise =  Jour_prise::where("id_temps", $id)->get();
           
            $getFrequence->each->delete();
            $getJoursPrise->each->delete();
            $getTemps_prise->delete();
            return response()->json("Le Médicament a été supprimer avec succès", 200);
        }
        else{
            return response()->json("Cette action n'est pas autorisée", 403);
        }
       
    }
    
    
}
