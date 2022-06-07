<?php

declare(strict_types=1);

namespace FireflyIII\Http\Middleware;

class UserGroupsPermission
{
  public function handle($request, \Closure $next)
  {
    if (!empty(auth()->user())) {
      // session value set on login
      setPermissionsTeamId(auth()->user()->user_group_id);
    }

    return $next($request);
  }
}
