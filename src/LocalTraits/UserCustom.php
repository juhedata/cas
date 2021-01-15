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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use JuHeData\CasLogin\Events\Auth\AuthLogin;
use JuHeData\CasLogin\Services\Encrypt;

class UserCustom
{
    protected static $userModel = "JuHeData\CasLogin\Models\User";

    // 自定义用户文件
    protected static $userLang = 'JuHeData\\CasLogin\\LocalTraits\\CAS_Languages_Lang';

    /**
     * 获取用户错误语言包处理
     *
     * @return string
     */
    public static function getUserLang()
    {
        return static::$userLang;
    }

    /**
     * 自定义用户错误语言包处理
     *
     * @param $lang
     */
    public static function setUserLang($lang)
    {
        static::$userLang = $lang;
    }

    /**
     * 获取用户model
     *
     * @return string
     */
    protected static function getUserModel()
    {
        return static::$userModel;
    }

    /**
     * 自定义用户model
     *
     * @param $userModel
     */
    public static function setUserModel($userModel)
    {
        static::$userModel = $userModel;
    }

    /**
     * 获取用户信息
     *
     * @param $authUser
     * @return mixed
     */
    public static function getUserInfo($authUser)
    {
        $userModel = static::getUserModel();

        if (!($user = $userModel::where($userModel::getUserNameFiled(), $authUser['uid'])->first())) {
            $user = static::addUser($userModel, $authUser);
        }

        return $user;
    }

    /**
     * 存储登录信息
     *
     * @param $userModel
     * @param $authUser
     * @return bool|User
     */
    protected static function addUer($userModel, $authUser)
    {
        $email = $authUser['uid'] . '@juhe.cn';

        if (isset($authUser['email']) && trim($authUser['email'])) {
            $email = $authUser['email'];
        }
        // 初始创建用户信息
        $user = (new $userModel)([
            'name' => $authUser['uid'],
            'email' => $email,
            'reg_time' => $authUser['regtime'] ?: '',
            'password' => password_hash($authUser['uid'], PASSWORD_BCRYPT)
        ]);

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    /**
     * 查询用户
     *
     * @param $id
     * @return mixed
     */
    public static function getUserId($id)
    {
        return (static::getUserModel())::whereId($id)
            ->first();
    }
}
