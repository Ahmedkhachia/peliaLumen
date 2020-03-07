<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Mail;

class ActivationCompte extends BaseController
{
    
    public function index(){
        $data = []; // Empty array
        $user = Auth::user();
        $msgg = "Bonjour Mr ".$user->prenom." ".$user->nom.", Bienvenue sur votre platforme pelia, voilà le lien pour activer votre compte www.pelia.com";
        // Mail::raw($msgg, function($msg) { 
        //     $msg->to('ahmedkhachia17@gmail.com', 'Pelia')->subject('Activation de votre compte Pelia');
        // });
        Mail::send('index', $data, function($msgg)
        {
            $msgg->to('ahmedkhachia17@gmail.com', 'Pelia')->subject('Activation de votre compte Pelia');
        });     
    }

}
