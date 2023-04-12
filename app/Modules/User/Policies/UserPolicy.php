<?php

namespace App\Modules\User\Policies;

use App\Modules\User\User;
use Laravel\Passport\Client;

class UserPolicy
{
    public function get(User $currentUser, User $user): bool
    {
        return $user->is($currentUser);
    }

    public function update(User $currentUser, User $user): bool
    {
        return $user->is($currentUser);
    }

    public function createClient(User $currentUser, User $user): bool
    {
        return $user->is($currentUser);
    }

    public function deleteClient(User $currentUser, User $user, Client $client): bool
    {
        return $user->is($currentUser) && $user->is($client->user);
    }
}
