## 安装
>composer require juhedata/cas
>
>php artisan juhecas:publish

>内置默认路由
```php
// cas客户端登录：重定向至服务端登录
Route::get('/api/cas/login/{mode?}', 'CasLoginController@casLogin')->name('casLogin');

// cas 登录回调：服务端登录成功后回调前端ST-校验地址
Route::get('/api/cas/oauth/ucenter', 'CasLoginController@oauthUCenter')->middleware('cas.auth');

// cas登出：客户端登出地址
Route::get('/api/cas/logout', 'CasLoginController@casLogout')->middleware('authCheck');

```

>中间件
```php
// cas.auth登录校验中间件
JuHeData\CasLogin\Http\Middleware\JuHeCasMiddleware

// 登录状态校验中间件：
JuHeData\CasLogin\Http\Middleware\AuthCheckMiddleware

```
