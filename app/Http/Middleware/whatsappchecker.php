<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class whatsappchecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $botkey = config('api.whatsapp_key');
        if ($request->header('botkey') !== $botkey) {
            return response()->json([
                'massage' => 'botkey anda tidak sama'
                ], 401);
        }
        return $next($request);
    }
}
