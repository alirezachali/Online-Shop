<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyIntegrationToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = (string) config('integration.token');
        $provided = (string) $request->bearerToken();

        if ($expected === '' || ! hash_equals($expected, $provided)) {
            abort(401, 'Invalid integration token.');
        }

        return $next($request);
    }
}
