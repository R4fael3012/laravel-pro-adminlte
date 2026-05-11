<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function destroy(User $user, User $model)
    {       
        return $user
        ->roles()
        ->where('name', 'Admin')
        ->exists();
    }
}
