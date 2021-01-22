<?php

namespace JuHeData\CasLogin\Http\Controllers;

use Illuminate\Http\Request;
use JuHeData\CasLogin\LocalTraits\CasLogin;
use \Illuminate\Http\RedirectResponse;
use \Illuminate\Routing\Redirector;

class CasLoginController
{
    public function casLogin(Request $request, $mode = '')
    {
        if (!($refer = $request->server('HTTP_REFERER'))) {
            $refer = config('juheCas.client');
        }

        // 记录登录跳转地址
        setLocalCookie('_re', $refer);

        switch ($mode) {
            case 'casBind':
                return CasLogin::casBindLogin();
                break;
            default:
                return CasLogin::juHeCasLogin();
                break;
        }
    }

    /**
     * 登录成功后统一回调页面
     * CAS授权登录成功后回调跳转
     *
     * @return RedirectResponse|Redirector
     */
    public static function oauthUCenter()
    {
        return CasLogin::oauthUCenter();
    }

    /**
     * cas登录
     *
     * @param Request $request
     */
    public function casLogout(Request $request)
    {
        //测试环境兼容自定义前端域名进行登录开发
        if (isDevelop()) {
            if ($ref = $request->server('HTTP_REFERER')) {
                preg_match('/^(http[s]?:\/\/[a-z\.\d\-]+)\/.*/', $ref, $match);
                if (isset($match[1]) && $match[1]) {
                    config(['cas.cas_logout_redirect' => $match[1]]);
                }
            }
        }
        //设置登出后回调地址
        cas()->logout();
    }

}
