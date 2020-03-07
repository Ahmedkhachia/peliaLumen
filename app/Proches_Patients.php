<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class Proches_Patients extends Model
{
    protected $table = "proches_patients";

    protected $primaryKey = "id";
}
