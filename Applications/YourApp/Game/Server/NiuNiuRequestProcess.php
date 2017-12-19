<?php
/**
 * 专门用于处理牛牛房间客户端请求的类
 * User: LiBing
 * Date: 2017/10/7
 * Time: 17:41
 */

require_once __DIR__.'/../Context/ConnectAPI.php';
require_once __DIR__.'/../Context/GameType.php';

class NiuNiuRequestProcess{

    /**
     * 处理响应分发的函数
     * @param $connectionID  连接生成的唯一ID
     * @param $clientRequest  \Protocols\GameProtocol解析的协议
     * @param $gameServer   中转服务
     * 参数为：total_length,head_request,status,game_type, client_msg
     */
    public function processClientRequest($connectionID, $clientRequest,$gameServer){
        switch($clientRequest['head_request']) {
            //如果是创建房间请求
            case ConnectAPI::$CREATEROOM_REQUEST:
                $this->createRoom($connectionID,$clientRequest,$gameServer);
                break;

        }
    }

    /**
     * 处理创建房间的函数 响应一个房间对象给创建人【房费不够返回的包状态为2】
     * @param $connectionID 客户端连接生成的唯一ID
     * @param $clientRequest 创建房间的数据，对房间进行初始化
     * @param $gameServer 用于数据中转的服务
     */
    private function createRoom($connectionID, $clientRequest,$gameServer) {
        $serverContext=$gameServer->serverContext;
        $niuNiuRoomManager=$gameServer->niuNiuRoomManager;

        //查找到当前创建房间人的UID
        $userUid=$_SESSION["$connectionID"];

        if($userUid){
            $playerAvatar=$serverContext->getPlayerFromOnLine($userUid);//得到用户Avatar

            //判断创建人房费是否够
            if($clientRequest["client_msg"]["roomRatePayment"][0]==1){
                //房主支付
                $payNum=$clientRequest["client_msg"]["totalCount"]*0.3;
            }else if($clientRequest["client_msg"]["roomRatePayment"][0]==2){
                //AA支付
                $payNum=$clientRequest["client_msg"]["totalCount"]*0.1;
            }

            if($playerAvatar->getRoomCard()>=$payNum){
                $roomID=$niuNiuRoomManager->createNiuNiuRoom($playerAvatar,$clientRequest['client_msg']);//得到牛牛房间ID(参数是一个avatar 和房间相关信息)

                //将房主添加到一个组里，组名是房间id
                \GatewayWorker\Lib\Gateway::joinGroup($connectionID,$roomID);

                $playerAvatar->roomID=$roomID;//设置当前玩家的roomId

                $resRoom=$niuNiuRoomManager->getMaJongRoom($roomID);
                //将创建人添加到房间里
                $resRoom->addPlayerToList($userUid);

                $resRoom=$niuNiuRoomManager->getNiuNiuRoom($roomID);
                //房间创建成功后，将信息发送给玩家
                $gameServer->processServerResponse(ConnectAPI::$CREATEROOM_RESPONSE,1,GameType::$NIUNIU,$userUid,$resRoom);
            }else{
                //房费不够返回状态为2
                $gameServer->processServerResponse(ConnectAPI::$CREATEROOM_RESPONSE,2,GameType::$NIUNIU,$userUid,null);
            }
        }

    }

}



?>