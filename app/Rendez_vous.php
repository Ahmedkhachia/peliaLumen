<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class Rendez_vous extends Model
{
    
    protected $table = "rendez_vous";
    protected $primaryKey = "id_rendez_vous";
/********************************************Get Rendez_vous********************************************** */
    public function index($id){
        if (Auth::user()->id_type == 1){
            $getRendez_vous = Rendez_vous::select("*")
            ->where([
                ["rendez_vous.id_patient", $id],
                ["rendez_vous.etat", 1],
            ])->get();
            
            foreach($getRendez_vous as $item){
                    $getUser = User::select("id" ,"nom", "prenom", "email", "phone","Adresse")
                    ->where("users.id", $item->id_medecin)
                    ->first();
                    $item->setAttribute("medecin" ,$getUser);
                    $getSpecialite = medecin_specialite::select("id_specialite", "id_medecin")
                    ->where("medecin_specialite.id_medecin", $item->id_medecin)
                    ->get();
                    foreach ($getSpecialite as $key){
                        $getSpecialiteInfo = Specialite::select("id_specialite" ,'nom_specialite', 'nom_specialite_arab')
                        ->where('Specialite.id_specialite', $key->id_specialite)
                        ->first();
                        $key->setAttribute("specialite" ,$getSpecialiteInfo->specialite);
                    }
                    $getUsersInfo = User_info::select('*')
                    ->where('users_info.user_id', $item->id_medecin)
                    ->first();

                    $item->setAttribute("specialite" ,$getSpecialite);
                    $item->setAttribute("UsersInfo" ,$getUsersInfo);   
                    
            }
            return $getRendez_vous;
        }
        elseif(Auth::user()->id_type == 2){
            $getRendez_vous = Rendez_vous::select("*")
            ->where([
                ["rendez_vous.id_medecin", $id],
                ["rendez_vous.etat", 1],
            ])->get();
            
            foreach($getRendez_vous as $item){
                    $getUser = User::select("id" ,"nom", "prenom", "email", "phone","Adresse")
                    ->where("users.id", $item->id_patient)
                    ->first();
                    $item->setAttribute("patient" ,$getUser);
                
                    
            }
            return $getRendez_vous;
        }
        
    }
/*************************************************Get Rendez-vous Today*************************************************** */
    public function getRndvToday(){
        // if($type == "patient"){
        //     $type = "medecin";
        // }
        // elseif($type == "medecin"){
        //     $type = "patient";
        // }
        $today = Carbon::today()->toDateString();
        $get = Rendez_vous::select("*")
        ->where([
            ["rendez_vous.id_patient", Auth::user()->id],
            ["rendez_vous.etat", 1],
        ])
        ->get();
        $table = [];
        foreach($get as $item){
            $getUser = User::select("id", "prenom", "nom", "Adresse", "photo_utilisateur")
            ->where("users.id", $item->id_medecin)
            ->first();

            $splitName = explode(' ', $item->date_start);
            
            if($splitName[0] == $today){
                $item->setAttribute("user" ,$getUser);
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
        }
        
        return $get;
    }

/*************************************************Get Rendez-vous Non Activer************************************************** */

    public function getInactiveRndv(){
        $get = Rendez_vous::select("*")
        ->join("users", "users.id", "=", "rendez_vous.id_patient")
        ->where([
            ["rendez_vous.id_medecin", Auth::user()->id],
            ["rendez_vous.etat", 0],
        ])
        ->get();
        return $get;
        
    }

}
