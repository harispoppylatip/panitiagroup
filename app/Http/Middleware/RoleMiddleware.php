<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $currentRole = $this->normalizeRole($user->role);
        $allowedRoles = array_map([$this, 'normalizeRole'], $roles);

        if (!in_array($currentRole, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }

    private function normalizeRole(?string $role): ?string
    {
        return $role === 'scanabsen' ? 'anggota' : $role;
    }
}