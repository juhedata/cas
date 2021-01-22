<?php

namespace JuHeData\CasLogin\Services;

class Encrypt
{
    /**
     * 返回key
     * @return string
     */
    public static function getKey()
    {
        return config('juheCas.apiKey');
    }

    /**
     * 加密
     * @param $data
     * @return string
     */
    public static function encryptHash($data)
    {
        $key = substr(self::getKey(), 8, 16);
        $iv = substr(self::getKey(), -16);
        $encrypt = openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypt);
    }

    /**
     * 解密
     *
     * @param $data
     * @return string
     */
    public static function decryptHash($data)
    {
        $iv = substr(self::getKey(), -16);
        $key = substr(self::getKey(), 8, 16);
        $data = base64_decode($data);
        return openssl_decrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * 返回key
     *
     * @param $key
     * @return mixed
     */
    public static function getOpenSSLKey($key)
    {
        if (!$key) {
            $key = config('juheCas.openSSLKey');
        }
        return $key;
    }

    /**
     * 加密
     *
     * @param $encrypt
     * @param $key
     * @return bool|string
     */
    public static function encryptUid($encrypt, $key = '')
    {
        $key = static::getOpenSSLKey($key);

        if (mb_strlen($key) < 16) {
            $key = str_pad($key, 16, '0');
        }
        $key = substr($key, 0, 16);
        $encrypt = openssl_encrypt($encrypt, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $key);
        $encrypt = base64_encode($encrypt);
        $encrypt = str_replace('/', '-', $encrypt);
        $encrypt = str_replace('+', '_', $encrypt);
        return $encrypt;
    }

    /**
     * 解密
     *
     * @param $decrypt
     * @param $key
     * @return bool|string
     */
    public static function decryptUid($decrypt, $key = '')
    {
        $key = static::getOpenSSLKey($key);

        if (mb_strlen($key) < 16) {
            $key = str_pad($key, 16, '0');
        }
        $key = substr($key, 0, 16);
        $decrypt = str_replace('-', '/', $decrypt);
        $decrypt = str_replace('_', '+', $decrypt);
        $decrypt = base64_decode($decrypt);

        $decrypt = openssl_decrypt($decrypt, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $key);
        return $decrypt;
    }
}
