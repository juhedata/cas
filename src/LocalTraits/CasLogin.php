<?php
/**
 * 聚合平台用户体系登录
 *
 * User: owner
 * Date: 12/23/20
 * Time: 10:00 AM
 * Project Name: scan.juhe.cn
 */

namespace JuHeData\CasLogin\LocalTraits;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JuHeData\CasLogin\Events\AuthLogin;
use JuHeData\CasLogin\Services\Encrypt;

class CasLogin
{

    /**
     * 动态设置登录渠道
     *
     * @param string $from
     */
    protected static function setChannel($from = '')
    {
        casRedirectUrl();
        $redirect = config('cas.cas_redirect_path');
        $service = urlencode($redirect) . '&from=' . $from;

        config(['cas.cas_login_url' => config('cas.cas_login_url') . '?service=' . $service]);
    }

    /**
     * 聚合cas统一登录
     *
     * @return bool
     */
    public static function juHeCasLogin()
    {
        static::setChannel('pc');

        //判断是否授权认证通过
        if (cas()->isAuthenticated()) {
            //授权成功后，获取用户信息，由于服务端数据处理限制，vipInfo和superman信息，使用的是json格式，所以如果需要使用的话，要自行处理下
            //cas单点登录返回的用户信息
            return static::oauthUCenter();
        }
        return cas()->authenticate();
    }

    /**
     * 登录成功后统一回调页面
     * CAS授权登录成功后回调跳转
     *
     * @return RedirectResponse|Redirector
     */
    public static function oauthUCenter()
    {
        $authUser = cas()->getAttributes();
        static::loginSyncEvent($authUser);

        return static::loginSuccess();
    }

    /**
     * 统一处理登录账号信息
     *
     * @param $authUser
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected static function loginSyncEvent($authUser)
    {
        // 同步聚合平台用户登录时间
        static::syncLoginTime($authUser['uid']);

        // 获取或存贮用户基本信息
        $user = UserCustom::getUserInfo($authUser);

        // 登录成功后存储用户登录信息
        $_ckuj = Encrypt::encryptUid(json_encode($authUser, JSON_UNESCAPED_UNICODE));
        setLocalCookie('_skuj', $_ckuj, 60 * 24 * 15);

        // auth登录
        Auth::login($user);

        // 添加用户登录时间，如果有需要处理的额外动作可以用
        Event::dispatch(new AuthLogin($authUser));
    }

    /**
     * 同步更新聚合平台的登录时间
     *
     * @param $uid
     */
    protected static function syncLoginTime($uid)
    {
        //更新聚合平台登录时间
        if ($url = config('juheCas.syncLoginUrl')) {
            Http::timeout(3)->get($url . '/' . $uid);
        }
    }

    /**
     * 登录成功处理:生成用户token,跳转到授权成功页面
     *
     * @param bool $json
     * @return mixed
     */
    protected static function loginSuccess($json = false)
    {
        // 生成处理登录token
        $user = Auth::user();
        $login = $user->createToken($user->name);
        $token = Encrypt::encryptHash($login->plainTextToken);
        if ($json) {
            return msgExport(0, ['_tk' => $token]);
        }

        $query = http_build_query(['login' => 'success', '_tk' => $token]);

        $redirect = config('juheCas.oauthResult');
        // 测试环境兼容自定义前端域名进行登录开发
        if (isDevelop()) {
            if ($ref = getOriginCookie('_re')) {
                // 匹配登录来源站点的host,调整登录校验，可以匹配前端本地开发调试的host
                preg_match('/^(http[s]?:\/\/[a-z\.\d\-\:]+)/', $ref, $match);
                if (isset($match[1]) && $match[1]) {
                    $redirect = $match[1] . '/oauth_result';
                }
            }
        }

        return redirect($redirect . '?' . $query);
    }

    /**
     * cas 同步bind登录：用户从聚合用户中心小应用点击过来直接登录
     *
     * @return mixed
     */
    public static function casBindLogin()
    {
        if (!($_kuj = request()->input(config('juheCas.uCenterParamKey')))) {
            return msgExport([1005, '授权登录已过期或已超时']);
        }

        $response = Http::get(config('juheCas.syncUrl') . $_kuj);

        if ($response->successful() && $response->json('code') === 0) {
            if ($authUser = $response->json('result')) {
                // 用户信息解密
                if ($authUser = json_decode(Encrypt::decryptUid($authUser), true)) {
                    static::loginSyncEvent($authUser);
                    return static::loginSuccess(true);
                }
            }
        }

        return msgExport(1005);
    }
}
