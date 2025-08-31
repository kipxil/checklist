<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FeatureAccess
{
    public function handle(Request $request, Closure $next, string $feature)
    {
        $user = $request->user();
        if (!$user) abort(401, 'Unauthorized');

        // 1) Admin selalu boleh
        if ($user->admin) {
            return $next($request);
        }

        // 2) Sanctum abilities (jika token dibuat dengan abilities)
        // if ($user->tokenCan('*')) {
        //     return $next($request);
        // }
        if ($user->tokenCan($feature)) {
            return $next($request);
        }

        // 3) Fallback ke aturan berdasarkan department (tanpa bergantung pada abilities)
        $depName = strtolower($user->department->name ?? '');
        $depCode = strtoupper($user->department->code ?? '');

        // Developer bisa akses semuanya (pakai name "Developer" atau code "DEV")
        $isDeveloper = ($depName === 'developer') || ($depCode === 'DEV');

        $allowed = match (strtolower($feature)) {
            'hotels','hotel'           => $isDeveloper || $depName === 'hotel',
            'restaurants','restaurant' => $isDeveloper || $depName === 'restaurant' || $depName === 'restoran',
            'settings'                 => $isDeveloper, // hanya Developer (dan admin sudah lolos di atas)
            default                    => false,
        };

        abort_if(!$allowed, 403, 'Forbidden');
        return $next($request);
    }
}
