<?php
/*
 * 处理和server相关的功能
 */

class ServerUtil {

    /**
     * getClientIp 
     * get client http request ip
     * 
     * @static
     * @access public
     * @return void
     */
    public static function getClientIp()
    {
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else if(!empty($_SERVER["REMOTE_ADDR"])){
            $ip = $_SERVER["REMOTE_ADDR"];
        }
        else{
            $ip="";
        }
        return $ip;
    }
}
