<?php

namespace Tenseg\Barnacle\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Tenseg\Barnacle\Controllers\BarnacleInjectController;

class BarnacleInject
{
    public function __construct(
        protected BarnacleInjectController $barnacle,
    ) {}

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (! $this->barnacle->isEnabled() || $response->status() !== 200 || $request->query->has('live-preview')) {
            return $response;
        }

        try {
            $this->barnacle->inject($response);
        } catch (\Exception $e) {
            Log::debug($e->getMessage());
        }

        return $response;
    }
}
