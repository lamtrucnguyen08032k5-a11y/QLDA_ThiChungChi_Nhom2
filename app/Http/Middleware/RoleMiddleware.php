<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Chặn truy cập theo vai trò. Dùng: ->middleware('role:admin,khoa')
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, 'Bạn không có quyền truy cập chức năng này.');
        }

        if (! $user->active) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Tài khoản của bạn đã bị khóa.']);
        }

        return $next($request);
    }
}
