<?php
/**
 * Created by PhpStorm.
 * User: qgkjtech
 * Date: 2017/10/12
 * Time: 下午10:26
 * 封装的Server,由所有的进程共享
 */

require_once "NiuNiuRequestProcess.php";
require_once "MaJongRequestProcess.php";
require_once "PublicRequestProcess.php";
require_once "ServerContext.php";
require_once "ServerResponse.php";
require_once __DIR__ . '/../Object/NiuNiu/NiuNiuRoomManager.php';
require_once __DIR__ . '/../Object/MaJong/MaJongRoomManager.php';
require_once __DIR__ . '/../Context/GameType.php';
require_once __DIR__ . '/../Log/Log.php';

class GameServer{
    public $niuNiuClientRequest;         //用于处理客户端牛牛相应的处理器
    public $maJongClientRequest;         //用于处理客户端麻将相应的处理器
    public $publicClientRequest;         //用于处理客户端公有的处理器
    public $serverContext;         //用于保存服务器总环境的对象
    public $serverResponse;        //用于处理服务器返回客户端的对象
    public $niuNiuRoomManager;     //牛牛房间管理
    public $maJongRoomManager;     //麻将房间管理
    public $logManager;//日志管理器


    function __construct()
    {
        $this->niuNiuClientRequest = new NiuNiuRequestProcess();
        $this->maJongClientRequest = new MaJongRequestProcess();
        $this->publicClientRequest=new PublicRequestProcess();
        $this->serverContext = new ServerContext();
        $this->serverResponse = new ServerResponse();
        $this->niuNiuRoomManager=new NiuNiuRoomManager();
        $this->maJongRoomManager=new MaJongRoomManager();
        $this->logManager=new Log();
    }

    /**
     *二次封装的服务器处理客户端响应的函数
     * @param $connectionID 连接ID
     * @param $clientRequest 客户端请求数据
     */
    function processClientRequest($connectionID, $clientRequest)
    {
        switch ($clientRequest["game_type"]){
            //麻将游戏消息分发
            case GameType::$MAJONG:
                $this->maJongClientRequest->processClientRequest($connectionID, $clientRequest,$this);
                break;
            //牛牛游戏消息分发
            case GameType::$NIUNIU:
                $this->niuNiuClientRequest->processClientRequest($connectionID, $clientRequest,$this);
                break;
            case GameType::$PUBLIC:
                $this->publicClientRequest->processClientRequest($connectionID, $clientRequest,$this);
                break;

        }
    }

    /**
     *二次封装的服务器响应给客户端的函数
     * @param $responseType 响应的操作码
     * @param $status 状态
     * @param $gameType 游戏类型
     * @param $responseTarget 响应的人/组ID
     * @param $responseMsg 响应内容
     */
    function processServerResponse($responseType,$status,$gameType,$responseTarget,$responseMsg){
        $this->serverResponse->processServerResponse($responseType,$status,$gameType,$responseTarget, $responseMsg);
    }


    /**
     * 检测用户连接断开后触发的函数
     * @param $connectionID
     */
    function disconnect($connectionID){
        $userUid=$_SESSION["$connectionID"];//得到玩家uid
        $playerAvatar=$this->serverContext->getPlayerFromOnLine($userUid);//得到玩家对应的Avatar

        if($playerAvatar){
            $roomID=$playerAvatar->roomID;//得到房间的ID

            $resRoom=$this->niuNiuRoomManager->getNiuNiuRoom($roomID);//根据房间ID找到对应的房间对象
            if(!$resRoom){
                $resRoom=$this->maJongRoomManager->getMaJongRoom($roomID);
                $gameType=GameType::$MAJONG;
            }else{
                $gameType=GameType::$NIUNIU;
            }

            //判断当前用户是否真的掉线
            if(\GatewayWorker\Lib\Gateway::isUidOnline($userUid)==0){
                //将当前人加入到离线列表中
                $this->serverContext->addOfflineCharacter($playerAvatar);

                //判断用户是否在房间里
                if($resRoom){
                    //将此人从房间组中移除
                    \GatewayWorker\Lib\Gateway::leaveGroup($connectionID,$roomi);

                    //通知房间中此人已经离线(后面)
                    $this->processServerResponse(ConnectAPI::$PLAYER_DROPS_RESPONSE,1,$gameType,$roomId,$userUid);
                    echo "exit ok!\n";
                }
            }

        }

    }

}



?>