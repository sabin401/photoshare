<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImageAuthorization
{
    public function handle(
        Request $request,
        Closure $next
    ): Response
    {
        $token = $request->session()->get('image_api_token');

        $owner = $request->session()->get('image_api_owner');

        $providedToken = $request->bearerToken();

        if (
            !$request->user() ||
            !$token ||
            !$providedToken ||
            $owner !== $request->user()->id ||
            !hash_equals($token, $providedToken)
        ) {
            return response()->json([
                'message' => 'Unauthorized request.'
            ], 401);
        }

        return $next($request);
    }
}