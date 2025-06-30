<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * The callback that should be used to generate the authentication redirect path.
     *
     * @var callable|null
     */
    protected static $redirectToCallback;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                       $request
     * @param  \Closure(\Illuminate\Http\Request): Response  $next
     * @param  string[]                                       ...$guards
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Lấy URL redirect dựa vào guard và role (nếu là web)
                $url = $this->redirectTo($request, $guard);
                return redirect($url);
            }
        }

        return $next($request);
    }

    /**
     * Get the path the user should be redirected to when they are authenticated.
     */
    protected function redirectTo(Request $request, ?string $guard): string
    {
        if (static::$redirectToCallback) {
            return call_user_func(static::$redirectToCallback, $request);
        }

        return $this->defaultRedirectUri($request, $guard);
    }

    /**
     * Get the default URI the user should be redirected to when they are authenticated.
     */
    protected function defaultRedirectUri(Request $request, ?string $guard): string
    {
        // Admin guard
        if ($guard === 'admin' && Route::has('admin.dashboard')) {
            return route('admin.dashboard');
        }

        // Web guard: phân theo role student/instructor
        if ($guard === 'web' && $user = $request->user()) {
            if ($user->role === 'student' && Route::has('student.dashboard')) {
                return route('student.dashboard');
            }
            if ($user->role === 'instructor' && Route::has('instructor.dashboard')) {
                return route('instructor.dashboard');
            }
        }

        // Fallback
        return '/';
    }

    /**
     * Specify the callback that should be used to generate the redirect path.
     */
    public static function redirectUsing(callable $redirectToCallback): void
    {
        static::$redirectToCallback = $redirectToCallback;
    }
}
