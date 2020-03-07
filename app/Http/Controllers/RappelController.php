<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Jour_prise;
use Carbon\Carbon;
use App\Medicament;
use App\Temps_prise;
use App\Rendez_vous;
use App\Frequence;

class RappelController extends Controller
{
    //getDataObservance of Users
    public function index(){
        $now = Carbon::now();
        $today =$now->format('l');
        $jours = [];
        $jours_prises = Jour_prise::select("*")
        ->join("temps_prises", "temps_prises.id_temps", "=", "jours_prises.id_temps")
        ->where([
            ["methode", 0],
            [$today, 1]
        ])
        ->Orwhere([
            ["methode", 1],
            ["date_prochaine", $now->toDateString()]
        ])
        ->get();
        $i=0;
        foreach ($jours_prises as $item){
            $jours = [];

            $getUser = User::select('*')
            ->where('users.id', $item->id_user)
            ->first();
            $item->setAttribute("User" ,$getUser);

            $item->setAttribute("jours" ,$jours+=array("methode" => $item->methode));
            $item->setAttribute("jours" ,$jours+=array("Monday" => $item->Monday));
            $item->setAttribute("jours" ,$jours+=array("Tuesday" => $item->Tuesday));
            $item->setAttribute("jours" ,$jours+=array("Wednesday" => $item->Wednesday));
            $item->setAttribute("jours" ,$jours+=array("Thursday" => $item->Thursday));
            $item->setAttribute("jours" ,$jours+=array("Friday" => $item->Friday));
            $item->setAttribute("jours" ,$jours+=array("Saturday" => $item->Saturday));
            $item->setAttribute("jours" ,$jours+=array("Sunday" => $item->Sunday));
            $item->setAttribute("jours" ,$jours+=array("Date_prochaine" => $item->date_prochaine));
            unset($item->Monday);
            unset($item->Tuesday);
            unset($item->Wednesday);
            unset($item->Thursday);
            unset($item->Friday);
            unset($item->Saturday);
            unset($item->Sunday);
            
            
            $getFrequenceMedicament = Frequence::select('*')
            ->where('frequences.id_temps', $item->id_temps)
            ->get();
            $getInfoMedicament = Medicament::select('*')
            ->where('medicament.CODE', $item->CODE)
            ->first();
            
            $item->setAttribute("Medicament" ,$getInfoMedicament);
            $item->setAttribute("frequence" ,$getFrequenceMedicament);
            
            $i++;
        }
        $test = [];
        $text="";
        foreach ($jours_prises as $item){
            foreach ($item->frequence as $key){
                $splitName = explode(':', Carbon::now()->addHour()->toTimeString());
                $timeNow = $splitName[0].":".$splitName[1];
                if($key->temps == $timeNow){
                    array_push($test, "Monsieur ".$item->User->prenom." ".$item->User->nom." vous devez prendre ".$key->dose."g de ".$item->Medicament->NOM." à ".$key->temps); 
                   if($item->jours['methode'] == 1){
                    $jours_prises2 = Jour_prise::select("id_temps", "date_prochaine", "intervale")
                    ->where("jours_prises.id_temps", $item->id_temps)
                    ->first();
                    $jours_prises2->date_prochaine = Carbon::now()->addDays($jours_prises2->intervale)->toDateString();
                    $jours_prises2->save();
                   }
                    
                }
            }
            
        }
        
///////////////////////getDataRendezVous////////////////////////

        $today = Carbon::today();
        $get = Rendez_vous::select("*")
        ->where([
            ["rendez_vous.etat", 1],
        ])
        ->get();
        foreach($get as $item){
            $getMedecin = User::select("*")
            ->where("users.id", $item->id_medecin)
            ->first();

            $getUser = User::select("*")
            ->where("users.id", $item->id_patient)
            ->first();
            $todayRndv = Carbon::today()->toDateString();
            $TimeRndv = Carbon::now()->addHour()->toTimeString();
            // $inst =  Carbon::create($item->Date_start);
            $splitName = explode(' ', $item->date_start);
            
            if($splitName[0] == $todayRndv){
                
                $item->setAttribute("Medecin" ,$getMedecin);
                $item->setAttribute("User" ,$getUser);
                $splitTime = explode(':', $TimeRndv);
                $getTime = $splitTime[0].":".$splitTime[1];
                // return $TimeRndv;
                if($splitName[1] == $getTime){
                    array_push($test, "Monsieur ".$item->User->prenom." ".$item->User->nom." vous avez un rendez-vous avec le docteur ".$item->Medecin->prenom." ".$item->Medecin->nom." à ".$item->Medecin->Adresse." aujourd'hui à ".$item->heure_rappel);   
                    
                }
            } 
            else{
                unset($item->id_rendez_vous);
                unset($item->id_medecin);
                unset($item->id_patient);
                unset($item->etat);
                unset($item->date_start);
                unset($item->date_end);
                unset($item->heure_rappel);
                unset($item->motif);
                unset($item->created_at);
                unset($item->updated_at); 
            } 
        
            // $DateRndv=$inst ->toDateTimeString();
            // if($today->diffInDays($DateRndv) == 0){
            //     $item->setAttribute("Medecin" ,$getMedecin);
            //     $item->setAttribute("User" ,$getUser);
            //     $splitName = explode(':', Carbon::now()->addHour()->toTimeString());
            //     $timeNow = $splitName[0].":".$splitName[1];
            //     $fromDataBase = explode(':', Carbon::create($item->Date_start)->toTimeString()); 
            //     $timeDataBase = $fromDataBase[0].":".$fromDataBase[1];
            //     if($timeDataBase == $timeNow){
            //         array_push($test, "Monsieur ".$item->User->prenom." ".$item->User->nom." vous avez un rendez-vous avec le Docteur".$item->Medecin->prenom." ".$item->Medecin->nom." à ".$timeDataBase);   
            //     }
            // } 
            // else{
            //     // $item->delete();
            //     return $inst;
            // } 
        }
        
        return $test;
    }
}
