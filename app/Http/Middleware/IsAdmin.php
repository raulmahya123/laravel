<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (!auth()->check()) {
            abort(401, 'Silakan login.');
        }
    
        if (!auth()->user()->is_admin) {
            abort(403, 'Anda bukan admin.');
        }
    
        return $next($request);
    }
    
}
