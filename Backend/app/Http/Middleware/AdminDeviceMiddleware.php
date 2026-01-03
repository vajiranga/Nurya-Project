<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminDeviceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::user();
        $deviceId = $request->header('X-Device-Identifier');

        if (!$deviceId) {
            return response()->json(['message' => 'Device identifier missing.'], 403);
        }

        $exists = \App\Models\AdminDevice::where('user_id', $user->id)
                             ->where('device_identifier', $deviceId)
                             ->exists();

        if (!$exists) {
            return response()->json(['message' => 'Unauthorized device.'], 403);
        }

        return $next($request);
    }
}
