<?php
/**
 * Created by PhpStorm.
 * User: owner
 * Date: 1/11/21
 * Time: 1:34 PM
 * Project Name: cas-juhe
 */
/**
 * 自定义设置cookie
 */
if (!function_exists('setLocalCookie')) {
    /**
     * @param $key
     * @param $val
     * @param int $expire
     */
    function setLocalCookie($key, $val, $expire = 3600)
    {
        @setcookie($key, $val, time() + $expire, '/');
    }
}

if (!function_exists('getOriginCookie')) {

    /**
     * 获取原始cookie数据
     *
     * @param $key
     * @param null $default
     * @return mixed|null
     */

    function getOriginCookie($key, $default = null)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }
        return $default;
    }
}

if (!function_exists('configJuHe')) {

    /**
     * @param $key
     * @param string $def
     * @return mixed
     */
    function configJuHe($key, $def = '')
    {
        return config('juheCas.' . $key, $def);
    }
}

if (!function_exists('isDevelop')) {

    /**
     * @param $key
     * @param string $def
     * @return mixed
     */
    function isDevelop()
    {
        return config('app.env') != 'production';
    }
}

if (!function_exists('casRedirectUrl')) {
    /**
     * 特殊处理下登录回调地址
     */
    function casRedirectUrl()
    {
        $redirect = config('cas.cas_redirect_path');
        config([
            'cas.cas_redirect_path' => $redirect . '?sourceChannel=' .
                preg_replace('/[^a-z\d]+/i', '_', config('app.name'))
        ]);
    }
}
