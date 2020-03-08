<?php

use Illuminate\Http\Request;


    //Route of Register
    Route::post('register', 'Auth\RegisterController@create');

    //Route of Register after the test
    Route::post('register/{id}', 'Auth\RegisterController@store');

    //Route of Login
    Route::post('login', 'Auth\LoginController@login');

    //Route of logout
    Route::post('logout', 'Auth\LoginController@logout');

    //Route of RefreshToken
    Route::post('refresh', 'Auth\LoginController@refresh');

    //Route of Profil
    Route::get('profil', 'Patient\ProfilPatientController@index');

    //Routes of Medicament
    Route::get("medicament/{id}", "Patient\MedicamentController@index");
    Route::post("medicament", "Patient\MedicamentController@store");
    Route::get("medicament/edit/{id}", "Patient\MedicamentController@edit");
    Route::put("medicament/{id}", "Patient\MedicamentController@update");
    Route::delete("medicament/{id}", "Patient\MedicamentController@destroy");

    //Routes of Profile Patient
    Route::get("profile/{id}", "Medecin\ProfilePatientController@GetInfoPatient");
    Route::post("medicamentPatient/{id}", "Medecin\ProfilePatientController@store");
    Route::get("medicamentPatient/edit/{id}", "Medecin\ProfilePatientController@edit");
    Route::put("medicamentPatient/{id}/{id_user}", "Medecin\ProfilePatientController@update");
    Route::delete("medicamentPatient/{id}/{id_patient}", "Medecin\ProfilePatientController@destroy");
    Route::post('/mesuresPatient{id}', 'Medecin\ProfilePatientController@storeM');
    Route::get('/mesuresPatient/edit/{id}', 'Medecin\ProfilePatientController@editM');
    Route::put('/mesuresPatient/{id}/{id_patient}', 'Medecin\ProfilePatientController@updateM');
    Route::delete('/mesuresPatient/{id}/{id_patient}', 'Medecin\ProfilePatientController@destroyM');


      //Routes of Proche Patient
      Route::get("profileProche/{id}", "Proche\ProchePatientController@GetInfoPatient");
      Route::post("medicamentProche/{id}", "Proche\ProchePatientController@store");
      Route::get("medicamentProche/edit/{id}", "Proche\ProchePatientController@edit");
      Route::put("medicamentProche/{id}/{id_user}", "Proche\ProchePatientController@update");
      Route::delete("medicamentProche/{id}/{id_patient}", "Proche\ProchePatientController@destroy");
      Route::post('/mesuresProche{id}', 'Proche\ProchePatientController@storeM');
      Route::get('/mesuresProche/edit/{id}', 'Proche\ProchePatientController@editM');
      Route::put('/mesuresProche/{id}/{id_patient}', 'Proche\ProchePatientController@updateM');
      Route::delete('/mesuresProche/{id}/{id_patient}', 'Proche\ProchePatientController@destroyM');

    
    //Routes of ListePatient
    Route::get("liste", "Medecin\ListePatientController@index");
    Route::delete("medicament/{id}", "Patient\MedicamentController@destroy");

    //Route of Activation
    Route::get('/Activation', 'ActivationCompte@index');

    //Route of Activation
    Route::get('/rappel', 'RappelController@index');

    //Routes of Dashboard
     Route::get("programme", "Patient\DashboardController@index");
     Route::get("observance", "Patient\ObservanceController@index");

    //Route of activation account
     Route::get("activation", "ActivationCompte@index");

        
    //Routes of Listes Patients and Medecins
    Route::get("medecins", "Patient\ListeMedecinController@index");
    Route::post("medecins", "Patient\ListeMedecinController@store");
    Route::get("medecins/edit/{id}", "Patient\ListeMedecinController@edit");
    Route::put("medecins/{id}", "Patient\ListeMedecinController@update");
    Route::delete("medecins/{id}", "Patient\ListeMedecinController@destroy");

    //Routes of login Google
    Route::get('/redirect', 'SocialAuthGoogleController@redirect');
    Route::get('/callback', 'SocialAuthGoogleController@callback');

    //Routes of login Facebook
    Route::get('/redirect/facebook', 'SocialAuthFacebookController@redirect');
    Route::get('/callback/facebook', 'SocialAuthFacebookController@callback');

    //Routes of Rendez-vous
    // Route::get("rendez-vous/{id}", "Patient\RendezVousController@index");
    Route::post("rendez-vous", "Patient\RendezVousController@store");
    Route::get("rendez-vous/edit", "Patient\RendezVousController@edit");
    Route::put("rendez-vous/{id}", "Patient\RendezVousController@update");
    Route::delete("rendez-vous/{id}", "Patient\RendezVousController@destroy");

    //Routes of Mesures
    Route::get('/mesures', 'Patient\MesureController@index');
    Route::post('/mesures', 'Patient\MesureController@store');
    Route::get('/mesures/edit/{id}', 'Patient\MesureController@edit');
    Route::put('/mesures', 'Patient\MesureController@update');
    Route::delete('/mesures/{id}', 'Patient\MesureController@destroy');


// Auth::routes();


