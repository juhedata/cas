<?php

namespace JuHeData\CasLogin\Http\Middleware;

use CAS_AuthenticationException;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use JuHeData\CasLogin\LocalTraits\UserCustom;
use phpCAS;
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
        try {
            phpCAS::setHTMLHeader(config('juheCas.header'));
            phpCAS::setLang(UserCustom::getUserLang());

            if (!$this->cas->checkAuthentication()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response('Unauthorized.', 401);
                }

                $this->cas->authenticate();
            }
        } catch (CAS_AuthenticationException $e) {
            return response('');
        }

        return $next($request);
    }
}
