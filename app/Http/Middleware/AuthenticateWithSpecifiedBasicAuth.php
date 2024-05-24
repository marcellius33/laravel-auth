<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateWithSpecifiedBasicAuth
{
    public function handle($request, Closure $next, string $client_id, string $client_password)
    {
        if ($request->getUser() !== config($client_id) || $request->getPassword() !== config($client_password)) {
            throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
        }

        return $next($request);
    }
}
