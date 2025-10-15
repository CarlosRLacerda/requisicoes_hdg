<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    public function crud(User $user): Response
    {
        $hasPermission = Response::deny();

        if ($user->hasRole('admin')) {
            $hasPermission = Response::allow();
        }

        return $hasPermission;
    }
}