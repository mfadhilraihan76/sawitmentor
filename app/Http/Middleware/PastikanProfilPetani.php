<?php

namespace App\Http\Middleware;

use App\Models\Petani;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PastikanProfilPetani
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {
            Petani::untukUser($request->user());
        }

        return $next($request);
    }
}