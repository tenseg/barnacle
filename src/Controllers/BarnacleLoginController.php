<?php

namespace Tenseg\Barnacle\Controllers;

use Illuminate\Routing\Controller;
use Statamic\Auth\User;
use Statamic\Facades\Preference;

class BarnacleLoginController extends Controller
{
    public function canUseCookie(): bool
    {
        if (! config('statamic.cp.enabled')) {
            return false;
        }

        if (! $user = User::current()) {
            return false;
        }

        if (! $user->can('access cp') || ! $user->can('use barncale cookie')) {
            return false;
        }

        return ! Preference::get('barnacle_disabled', false);
    }
}
