<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Auth;
use Carbon\Carbon;
// use App\Survey;

class Medicament extends Model
{
    protected $table = "medicament";

    protected $primaryKey = "CODE";
   
//getDataObservance of Users
    public function getMedicamentToday(){
        $now = Carbon::now();
        $today =$now->format('l');
        $jours = [];
        $jours_prises = Jour_prise::select("*")
        ->join("temps_prises", "temps_prises.id_temps", "=", "jours_prises.id_temps")
        ->where([
            [$today, 1],
            ["temps_prises.id_user", 6],
        ])
        ->get();
        foreach ($jours_prises as $item){
            $item->setAttribute("jours" ,$jours+=array("Monday" => $item->Monday));
            $item->setAttribute("jours" ,$jours+=array("Tuesday" => $item->Tuesday));
            $item->setAttribute("jours" ,$jours+=array("Wednesday" => $item->Wednesday));
            $item->setAttribute("jours" ,$jours+=array("Thursday" => $item->Thursday));
            $item->setAttribute("jours" ,$jours+=array("Friday" => $item->Friday));
            $item->setAttribute("jours" ,$jours+=array("Saturday" => $item->Saturday));
            $item->setAttribute("jours" ,$jours+=array("Sunday" => $item->Sunday));
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
        }
        return $jours_prises;
    }

//AllMedicament of Users

    public function get_medicament($id){
        $gett = Temps_prise::select('*')
        ->where('temps_prises.id_user', $id)
        ->get();
        $tab = [];
        $jours = [];
        foreach ($gett as $item){

            $getInfoMedicament = Medicament::select('*')
            ->where('medicament.CODE', $item->CODE)
            ->first();
            $getFrequenceMedicament = Frequence::select('*')
            ->where('frequences.id_temps', $item->id_temps)
            ->get();

            $getJoursPrise = Jour_prise::select('*')
            ->where('jours_prises.id_temps', $item->id_temps)
            ->first();
          
            
                
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
            
            $item->setAttribute("medicament" ,$getInfoMedicament);
            $item->setAttribute("frequence" ,$getFrequenceMedicament);
            $item->setAttribute("jours_prise" ,$getJoursPrise);
          
        }
        return $gett;
}

//getDataObservance of Users

    public function getDataObservance($id){
        $MedicPatient = Temps_prise::select("*")
        ->where("temps_prises.id_user", $id)
        ->get();
        $table = [];
        
        $jours = [];
        $valeur_moyenne=0;
        $total =0;
        foreach($MedicPatient as $item){
            $i=0;
            $getInfoMedicament = Medicament::select('*')
            ->where('medicament.CODE', $item->CODE)
            ->first();
            $getFrequenceMedicament = Frequence::select('*')
            ->where('frequences.id_temps', $item->id_temps)
            ->get();

            $getJoursPrise = Jour_prise::select('*')
            ->where('jours_prises.id_temps', $item->id_temps)
            ->get();
            

            foreach ($getJoursPrise as $key){
                
                $key->setAttribute("jours" ,$jours+=array("Monday" => $key->Monday));
                $key->setAttribute("jours" ,$jours+=array("Tuesday" => $key->Tuesday));
                $key->setAttribute("jours" ,$jours+=array("Wednesday" => $key->Wednesday));
                $key->setAttribute("jours" ,$jours+=array("Thursday" => $key->Thursday));
                $key->setAttribute("jours" ,$jours+=array("Friday" => $key->Friday));
                $key->setAttribute("jours" ,$jours+=array("Saturday" => $key->Saturday));
                $key->setAttribute("jours" ,$jours+=array("Sunday" => $key->Sunday));
                unset($key->Monday);
                unset($key->Tuesday);
                unset($key->Wednesday);
                unset($key->Thursday);
                unset($key->Friday);
                unset($key->Saturday);
                unset($key->Sunday);
            }

            $item->setAttribute("Medicament" ,$getInfoMedicament);  
            $item->setAttribute("frequence" ,$getFrequenceMedicament);
            $item->setAttribute("jours_prise" ,$getJoursPrise);

            
            foreach ($getFrequenceMedicament as $key){
                $data=[];
                $getSurvey = Survey::select('*')
                ->where('questionnaire.id_frequence', $key->id_frequence)
                ->get();
                // array_push($data, $getSurvey);
                $data+=array($getSurvey);
                $key->setAttribute("observance" ,$data[0]);
            }    
        }
        return $MedicPatient;

}
}
