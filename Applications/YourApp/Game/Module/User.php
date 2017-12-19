<?php
/**
 * 操作用户的Model类
 * User: LiBing
 * Date: 2017/12/10
 * Time: 10:12
 */
    use \GatewayWorker\Lib\Db;
    class User{
        public $db;
        function __construct()
        {
            $this->db=Db::instance('game');
        }
    }

?>