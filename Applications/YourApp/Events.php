<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

use \GatewayWorker\Lib\Gateway;
require_once __DIR__.'/GlobalData/Client.php';
require_once __DIR__.'/GlobalData/Server.php';
require_once __DIR__.'/Game/Server/GameServer.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    private static $globalClient;
    /**
     * WORKER端启动的时候调用,用于初始化全局数据
     * @param $worker
     */
    public static function onWorkerStart($worker){
        //连接进程共享数据服务器
        self::$globalClient = new GlobalData\Client("127.0.0.1:2207");
        //创建当前服务器
        $server = new GameServer();

        //保存共享数据，本函数只执行一次
        self::$globalClient->add('server', $server);
    }
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        //检查是否有更新，返回更新结果
        //echo $client_id."\n";
        echo "connect:".Gateway::getAllClientCount()."\n";
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {

       do{
           //获取服务器,当前服务器和保存的服务器环境
           $server = self::$globalClient->server;
           $oldServer = self::$globalClient->server;

           //开始处理客户端请求,内部调用响应函数返回客户端
           $server->processClientRequest($client_id, $message);
       }while(!self::$globalClient->cas('server', $oldServer, $server));

   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {

       echo "exit!\n";
       //当用户断开连接时
       //获取服务器,当前服务器和保存的服务器环境
       do{
           //获取服务器,当前服务器和保存的服务器环境
           $server = self::$globalClient->server;
           $oldServer = self::$globalClient->server;

           $server->disconnect($client_id);
       }while(!self::$globalClient->cas('server', $oldServer, $server));
   }
}
