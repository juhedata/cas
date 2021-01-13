<?php

namespace JuHeData\CasLogin\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Subfission\Cas\Middleware\CASAuth as BaseCasAuth;

class JuHeCasMiddleware extends BaseCasAuth
{
    protected $auth;

    protected $cas;

    public function __construct(Guard $auth)
    {
        casRedirectUrl();

        $this->auth = $auth;
        $this->cas = app('cas');
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->cas->checkAuthentication()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            }

            $this->cas->authenticate();
        }

        return $next($request);
    }
}
