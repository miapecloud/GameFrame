<?php
/**
 * Created by PhpStorm.
 * User: zlkmu
 * Date: 2017/10/7
 * Time: 20:42
 * 数据库配置类
 */
    namespace Config;

    class Db{
        public static $game = array(
            'host'=>'127.0.0.1',
            'port'=>3306,
            'user'=>'root',
            'password'=>'qgkj',
            'dbname'=>'game_majong',
            'charset'=>'utf8'
        );
    }


?>