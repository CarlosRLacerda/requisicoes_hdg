<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    use HandlesAuthorization;

    public function viewItems(User $user): Response
    {
        $hasPermission = Response::deny();

        if ($user->hasRole(['admin', 'almo'])) {
            $hasPermission = Response::allow();
        }

        return $hasPermission;
    }

    public function storeItems(User $user): Response
    {
        $hasPermission = Response::deny();

        if ($user->hasRole(['admin', 'almo'])) {
            $hasPermission = Response::allow();
        }

        return $hasPermission;
    }
}