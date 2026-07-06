<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class SetPermissionsTeam
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            app(PermissionRegistrar::class)
                ->setPermissionsTeamId(auth()->user()->empresa_id);
        }

        return $next($request);
    }
}