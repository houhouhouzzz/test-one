<?php

if (!function_exists('str_limit')) {
    /**
     * 截取字符串 并 添加后缀
     * @param string $string
     * @param string $limit
     * @param string $replace
     * @return [type]           [description]
     */
    function str_limit($string, $limit = 30, $replace = '')
    {
        if(strlen($string) <= $limit){
            return $string;
        }else{
            return substr($string, 0, $limit) . $replace;
        }
    }
}