<?php

namespace App\Policies;

use App\User;
use App\Temps_prise;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicamentPolicy
{
    use HandlesAuthorization;
    public function update(User $user, Temps_prise $getTemps_prise) {
        return $user->id === $getTemps_prise->user_id;
    }

}