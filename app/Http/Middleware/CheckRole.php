<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        $levelName = optional($user->level)->level_name;

        if (! $levelName || ! in_array($levelName, $roles)) {
            abort(403, 'Anda tidak memiliki hak akses.');
        }

        return $next($request);
    }
}