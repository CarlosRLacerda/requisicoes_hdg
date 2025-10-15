<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class RequestsPolicy
{
    use HandlesAuthorization;

    public function viewRequests(User $user): Response
    {
        $hasPermission = Response::deny();

        if ($user->hasRole(['admin', 'almo'])) {
            $hasPermission = Response::allow();
        }

        return $hasPermission;
    }

    public function avaliarRequests(User $user): Response
    {
        $hasPermission = Response::deny();

        if ($user->hasRole(['admin', 'almo'])) {
            $hasPermission = Response::allow();
        }

        return $hasPermission;
    }
}