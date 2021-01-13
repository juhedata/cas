<?php

return [
    // cas登录token加密秘钥
    'apiKey' => env('CAS_LOGIN_SECRET_KEY', null),

    // 聚合平应用中心同步登录加解密秘钥
    'uCenterKey' => env('CAS_UCENTER_LOGIN_SECRET_KEY', null),

    // 联邦登录参数
    'uCenterParamKey' => env('CAS_UCENTER_LOGIN_PARAM_KEY', null),

    // 前端分离亲前端地址
    'client' => env('APP_CLIENT_URL', null),

    // cas单点同步退到url
    'logout' => env('CAS_JUHE_LOGOUT_URL', null),

    // cas登录成功跳转认证结果页面（前后端分离登录鉴权使用）
    'oauthResult' => env('APP_CLIENT_OAUTH_RESULT_URL'),

    // 同步登录url
    'syncLoginUrl' => env('CAS_SYNC_LOGIN_URL', false),
];
