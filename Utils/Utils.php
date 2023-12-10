<?php

namespace Utils;

class Utils
{
    public static function deleteSession($name):void{
        if(isset($_SESSION[$name])){
            $_SESSION[$name] = null;
            unset($_SESSION[$name]);
        }
    }
}