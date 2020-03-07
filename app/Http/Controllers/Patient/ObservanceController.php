<?php

namespace App\Http\Controllers\Patient;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Medicament;
use Auth;

class ObservanceController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $medicament = new Medicament();
        $Observance = $medicament->getDataObservance(Auth::user()->id);
        return response()->json($Observance);
    }
}
