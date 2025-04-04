<?php

namespace Tenseg\Barnacle\Controllers;

use Illuminate\Routing\Controller;
use Statamic\Facades\Preference;

class BarnacleLoginController extends Controller
{
    public function canUseCookie(): bool
    {
        if (! config('statamic.cp.enabled')) {
            return false;
        }

        if (! auth()->check()) {
            return false;
        }

        if (! auth()->user()->can('access cp') || ! auth()->user()->can('use barncale cookie')) {
            return false;
        }

        return ! Preference::get('barnacle_disabled', false);
    }
}
