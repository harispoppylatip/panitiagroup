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
        $botkey = (string) config('api.whatsapp_key');

        // key kosong di .env = tolak semua, jangan sampai API terbuka tanpa auth
        if ($botkey === '' || ! hash_equals($botkey, (string) $request->header('botkey'))) {
            return response()->json([
                'message' => 'botkey anda tidak sama'
                ], 401);
        }
        return $next($request);
    }
}
