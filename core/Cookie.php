<?php


namespace app\core;


class Cookie
{
    private function __construct()
    {
    }



    public static function set($key, $value){
        $expired = time() + (1 + 365 * 24 * 60 * 60);
        setcookie($key, $value, $expired, '/', '', false, true);
        return $value;
    }

    public static function has($key)
    {
        return isset($_COOKIE[$key]);
    }

    public static function get($key)
    {
        return self::has($key) ? $_COOKIE[$key] : null;
    }


    public static function remove($key){
        unset($_COOKIE[$key]);
    }

    public static function all(){
        return $_COOKIE;
    }

    public static function destroy(){
        foreach(self::all() as $key => $value){
            self::remove($key);
        }
    }



}