<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\medecin_specialite;

class Medecins extends Model
{

    public function getInfoMedecin($id){
        $Medecin = User::select("*")
        ->join('users_info', 'users_info.user_id', '=', 'users.id')
        ->join('user_cursus', 'user_cursus.id_user', '=', 'users.id')
        ->where("users.id", $id)
        ->get();

        $table = [];
        foreach($Medecin as $item){
                $getSpecialite = medecin_specialite::select("*")
                ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                ->where("medecin_specialite.id_medecin", $item->id)
                ->get();
                $arr = $item->toArray();
                $arr += array("specialite" => $getSpecialite->toArray());
                array_push($table, $arr); 
        }

        return $table;
    }
    
    /********************************************************************************************************************* */

    public function getSearchMedecin($nom ,$id_specialite, $id_ville){
        $table = [];
        if($nom != NULL && $id_specialite == NULL && $id_ville == NULL){
            $Medecin = User::select("*")
                ->join('users_info', 'users_info.user_id', '=', 'users.id')
                ->join('ville_users', 'users.id', '=', 'ville_users.id_user')
                ->join('ville', 'ville.id_ville', '=', 'ville_users.id_ville')
                ->where([
                    ["users.id_type", 2],
                    ['users.prenom','like', $nom.'%']
                ])
                ->orwhere([
                    ["users.id_type", 2],
                    ['users.prenom','like', '%'.$nom.'%']
                ])
                ->get();
  
                foreach($Medecin as $item){
                $getSpecialite = medecin_specialite::select("*")
                ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                ->where([
                    ["medecin_specialite.id_medecin", $item->id],
                ])->get();
               
                        $arr = $item->toArray();
                        $arr += array("specialite" => $getSpecialite->toArray());
                        array_push($table, $arr); 
                   
            }
        }
        else{ 
        if($id_specialite == NULL){
            $Medecin = User::select("*")
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            // ->join('user_cursus', 'user_cursus.id_user', '=', 'users.id')
            ->join('ville_users', 'users.id', '=', 'ville_users.id_user')
            ->join('ville', 'ville.id_ville', '=', 'ville_users.id_ville')
            ->where([
                ["ville_users.id_ville", $id_ville],
                ["users.id_type", 2],
            ])
            ->get();
        }
        elseif($id_specialite != NULL){
            $Medecin = User::select("*")
            ->join('users_info', 'users_info.user_id', '=', 'users.id')
            // ->join('user_cursus', 'user_cursus.id_user', '=', 'users.id')
            ->where([
                ["users.id_type", 2],
            ])
            ->get();
        }
       
            

        
        $i=0;
        foreach($Medecin as $item){
            if($id_specialite != NULL && $id_ville != NULL){
                $getSpecialite = medecin_specialite::select("*")
                ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                ->join('ville_users', 'medecin_specialite.id_medecin', '=', 'ville_users.id_user')
                ->join('ville', 'ville.id_ville', '=', 'ville_users.id_ville')
                ->where([
                    ["medecin_specialite.id_specialite", $id_specialite],
                    ["ville_users.id_ville", $id_ville],
                ])->get();
                foreach($getSpecialite as $key){
                    if($item->id == $key->id_medecin){
                        $arr = $item->toArray();
                        $arr += array("specialite" => $getSpecialite->toArray());
                        array_push($table, $arr); 
                    }
                }
            }
            elseif($id_ville == NULL){
                $getSpecialite = medecin_specialite::select("*")
                ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                ->where([
                    ["medecin_specialite.id_specialite", $id_specialite],
                ])->get();
                foreach($getSpecialite as $key){
                    if($item->id == $key->id_medecin){
                        $arr = $item->toArray();
                        $arr += array("specialite" => $getSpecialite->toArray());
                        array_push($table, $arr); 
                    }
                }
            }
            elseif($id_specialite == NULL){
                $getSpecialite = medecin_specialite::select("*")
                ->join("specialite", "specialite.id_specialite", "=", "medecin_specialite.id_specialite")
                ->where([
                    ["medecin_specialite.id_medecin", $item->id],
                ])
                ->get();
                
                $arr = $item->toArray();
                $arr += array("specialite" => $getSpecialite->toArray());
                array_push($table, $arr); 
                    
            }
             
                
                
        }
        }
        return $table;
        
    }

    

}
