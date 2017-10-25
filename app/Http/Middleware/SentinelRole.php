<?php
namespace App\Http\Middleware;

use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Closure;

/**
 * Middleware class to check user's role.
 */
class SentinelRole
{
    /**
     * Handle function
     *
     * @param Request $request The HTTP request
     * @param Closure $next The functin to execute on success.
     * @param string $role The role being evaluated
     */
    public function handle($request, Closure $next, $role)
    {
        if (substr_count($role, ',')) {
            // Comma delimited array of roles, check each
            $roles = explode(",", $role);
        } else {
            $roles = [$role];
        }

        foreach ($roles as $role) {
            if (Sentinel::inRole($role)) {
                return $next($request);
            }
        }
        abort(401, 'User not found in role');
    }
}
