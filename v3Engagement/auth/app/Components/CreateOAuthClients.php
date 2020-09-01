<?php

namespace App\Components;

use Laravel\Passport\ClientRepository;

class CreateOAuthClients
{
    /**
     * @param \Illuminate\Database\Eloquent\Model $user
     */
    public static function createClients($user)
    {
        $clientRepository = new ClientRepository();

        $clientRepository->create(
            $user->id,
            $user->name . ' Access Client',
            config('engagement.url.callback')
        );
        $clientRepository->createPersonalAccessClient(
            $user->id,
            $user->name . ' Personal Access Client',
            config('engagement.url.callback')
        );
        $clientRepository->createPasswordGrantClient(
            $user->id,
            $user->name . ' Password Grant Client',
            config('engagement.url.callback')
        );
    }
}