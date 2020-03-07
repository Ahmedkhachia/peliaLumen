<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_info extends Model
{
    
    protected $table = "users_info";
    protected $primaryKey = "id_users_info";

    // protected $fillable = [
    //     'nom', 'prenom', 'Type', 'email', 'phone',  'age', 'ville', 'password',
    // ];

    public function create( array $data){
        return User_info::create([
            "cabinet" => $data["cabinet"],
            "phone" => $data["phone"],
            "centres" => $data["centre"],
            "telcabinet" => $data["phone"],
            "langue" => $data["langue"],
            "appt" => $data["Appt"],
            "code_postal" => $data["postal"],
            "cartier" => $data["cartier"],
        ]);
    }
}
