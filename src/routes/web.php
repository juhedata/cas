<?php

use Illuminate\Support\Facades\Route;

Route::group(
    ['prefix' => 'api/cas', 'namespace' => 'JuHeData\CasLogin\Http\Controllers', 'middleware' => ['web']],
    function () {
        // cas登录
        Route::get('login/{mode?}', 'CasLoginController@casLogin')->name('casLogin');

        // cas 登录回调
        Route::get('oauth/ucenter', 'CasLoginController@oauthUCenter')->middleware('cas.auth');

        // cas登出
        Route::get('logout', 'CasLoginController@casLogout')->middleware('authCheck');

    }
);
