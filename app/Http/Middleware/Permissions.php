<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Dev\Infrastructure\Models\Permission;
use Dev\Infrastructure\Models\RoleHasPermission;
use Dev\Infrastructure\Models\UserHasPermission;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Permissions
{
    private $exceptNames = [
        'debugbar*'
    ];

    private $exceptControllers = [
        'LoginController',
        'ForgotPasswordController',
        'ResetPasswordController',
        'RegisterController'
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $permission = $request->route()->getName();
//        $user_id = auth()->user()->national_id;
        $user_id = '11111111111111';
        if ($this->match($request->route()) && $this->hasPermission($user_id, $permission)) {
            return response()->json(['msg' => trans('messages.permission') . $permission]);
          //  throw new UnauthorizedHttpException(trans('messages.permission') . ' <b>' . $permission . '</b>');
        }
        return $next($request);
    }

    private function hasPermission(string $user_id, string $permission)
    {
        $perm = Permission::where('name', $permission)->first();
        $userRoles = User::where('national_id', $user_id)->first()->roles()->pluck('id')->toArray();
        $userRolesPermissions = RoleHasPermission::whereIn('role_id', $userRoles)->pluck('permission_id')->toArray();
        $usersPermissions = UserHasPermission::where('user_id', $user_id)->pluck('permission_id')->toArray();
        if (in_array($perm->id, $userRolesPermissions) || in_array($perm->id, $usersPermissions)) {
            return false;
        }
        return true;
    }

    private function match(Route $route)
    {
        if ($route->getName() == '' || $route->getName() === null) {
            return false;
        } else {
            if (in_array(class_basename($route->getController()), $this->exceptControllers)) {
                return false;
            }
            foreach ($this->exceptNames as $except) {
                if (Str::is($except, $route->getName())) {
                    return false;
                }
            }
        }
        return true;
    }

}
