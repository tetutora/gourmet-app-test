<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role->name !== $role) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

    private function getRoleIdByName(string $roleName): int|null
    {
        return match ($roleName) {
            '管理者' => 1,
            '店舗代表者' => 2,
            '利用者' => 3,
            default => null,
        };
    }
}