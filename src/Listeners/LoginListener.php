<?php

namespace Tenseg\Barnacle\Listeners;

use Illuminate\Auth\Events\Login;
use Tenseg\Barnacle\Controllers\BarnacleLoginController;

class LoginListener
{
    public function __construct(
        protected BarnacleLoginController $controller
    ) {}

    public function handle(Login $event): void
    {
        $cookie_name = config('barnacle.cookie');
        if ($cookie_name && $this->controller->canUseCookie()) {
            cookie()->queue($cookie_name, true, 60 * 24 * 7); // 7 days}
        }
    }
}
