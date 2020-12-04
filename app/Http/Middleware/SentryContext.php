<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Sentry\Laravel\Tracing\Middleware;

class SentryContext
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->bound('sentry')) {
            /** @var \Raven_Client $sentry */
            $sentry = app('sentry');


            // Add user context
            $bearerToken = Str::after($request->bearerToken(), "|");
            $hashedToken = hash('sha256', $bearerToken);
            $user_id = (!$bearerToken) ? null :
                DB::table('personal_access_tokens')->where('token', $hashedToken)
                    ->select('tokenable_id')->value('tokenable_id');

            if ($user_id) {
                $user = User::find($user_id);
                $sentry->configureScope(function (\Sentry\State\Scope $scope) use ($user): void {
                    $scope->setTag(
                        'type', 'general'
                    );
                    $scope->setUser([
                        'id' => $user->id,
                        'name' => $user->name,
                        'phone' => $user->phone,
                        'role' => $user->role
                    ]);
                });
            } else {
                $sentry->configureScope(function (\Sentry\State\Scope $scope): void {
                    $scope->setTag(
                        'tag', 'general'
                    );
                    $scope->setUser([
                        'id' => null,
                        'type' => 'unauthenticated'
                    ]);
                });
            }

            // Add tags context
//            $sentry->tags_context([...]);
        }

        return $next($request);
    }
}
