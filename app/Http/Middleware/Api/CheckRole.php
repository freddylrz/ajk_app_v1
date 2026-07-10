<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $userRoles = DB::table('public.roles')
            ->join('public.role_user', 'public.roles.id', '=', 'public.role_user.role_id')
            ->where('public.role_user.user_id', Auth::id())
            ->pluck('public.roles.name')
            ->toArray();

        // return $userRoles;

        $allowedRoles = explode('|', $role);

        if (!array_intersect($userRoles, $allowedRoles)) 
        {
            return response()->json([
                'status'  => 403,
                'message' => 'Access denied.',
            ], 403);
        }

        return $next($request);
    }
}
