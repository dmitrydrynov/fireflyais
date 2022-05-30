<?php

declare(strict_types=1);

namespace FireflyIII\Http\Middleware;

use Closure;
use FireflyIII\Repositories\User\UserRepositoryInterface;
use FireflyIII\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class Permissions.
 */
class Permissions
{
    /**
     * Handle an incoming request. Must be admin.
     *
     * @param Request     $request
     * @param Closure     $next
     * @param string|null $guard
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!empty($permissions)) {
            if ($permissions[0] === 'any') {
                return $next($request);
            }

            if ($permissions[0] === 'deny') {
                return response()->redirectTo(route('home'));
            }

            foreach ($permissions as $permission) {
                if ($request->user()->hasUserGroupRole(trim($permission))) {
                    return $next($request);
                }
            }
        }

        return response()->redirectTo(route('home'));
    }
}
