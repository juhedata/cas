<?php

namespace JuHeData\CasLogin\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Auth;
use JuHeData\CasLogin\LocalTraits\UserCustom;
use Laravel\Sanctum\Sanctum;

class AuthCheckMiddleware
{
    /**
     * 处理用户登录状态
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = str_replace('Bearer ', '', $request->header('authorization'));
        try {
            if ($token) {
                if ($accessToken = ($this->personalAccessToken())::findToken($token)) {
                    $expire = config('sanctum.expiration');

                    if (!$expire || $accessToken->created_at->gte(now()->subMinutes($expire))) {
                        if ($user = UserCustom::getUserId($accessToken->tokenable_id)) {
                            Auth::login($user);
                            $request->attributes->add(['tokenId' => $accessToken->id]);
                        } else {
                            $accessToken->delete();
                            Auth::logout();
                        }
                    } else {
                        //过期删除
                        $accessToken->delete();
                        Auth::logout();
                    }
                } else {
                    Auth::logout();
                }
            } else {
                Auth::logout();
            }
        } catch (Exception $e) {
            Auth::logout();
        }

        return $next($request);
    }

    protected function personalAccessToken()
    {
        return Sanctum::personalAccessTokenModel();
    }
}
