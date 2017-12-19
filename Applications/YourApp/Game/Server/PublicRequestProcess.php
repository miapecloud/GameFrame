<?php
/**
 * 专门用于处理客户端请求的公共类
 * User: zlkmu
 * Date: 2017/10/7
 * Time: 17:41
 */

require_once __DIR__.'/../Module/User.php';
require_once __DIR__.'/../Module/System.php';
require_once __DIR__.'/../Context/GameType.php';
class PublicRequestProcess{

    /**
     * 处理公共响应分发的函数
     * @param $connectionID  连接生成的唯一ID
     * @param $clientRequest  \Protocols\GameProtocol解析的协议
     * @param $gameServer   中转服务
     * 参数为：total_length,head_request,status,game_type,client_msg
     */
    public function processClientRequest($connectionID, $clientRequest,$gameServer){
        switch($clientRequest['head_request']) {
            //如果是登录请求
            case ConnectAPI::$LOGIN_REQUEST:
                $this->userLogin($connectionID, $clientRequest,$gameServer);
                break;
        }
    }

    /**
     * 处理登录的函数
     * @param $connectionID
     * @param $clientRequest
     * @param $gameServer
     */
    private function userLogin($connectionID, $clientRequest,$gameServer){
        $serverContext=$gameServer->serverContext;

        $userModel=new User();
        $userUid=$userModel->judgeUser($clientRequest["client_msg"]);//检查用户是否注册

        if($userUid){
            //当用户已经注册
            $playerAvatar = $serverContext->getPlayerFromOffLine($userUid);//根据user_uid得到玩家的Avatar getPlayerFromOffLine

            if($playerAvatar){
                //掉线重新连接
                $serverContext->removeOfflinePlayer($playerAvatar);//删除掉线玩家
                $serverContext->addOnlineCharacter($playerAvatar);//添加一个在线玩家

                //记录当前connectionID对应的Player在全局SESSION里面
                $_SESSION[$connectionID] = $playerAvatar->getPlayerID();
            }else{
                //全新登录
                $playerAvatar = new PlayerAvatar($clientRequest['client_msg'], $_SERVER['REMOTE_ADDR'],1);
                $serverContext->addOnlineCharacter($playerAvatar);
                //记录当前connectionID对应的Player在全局SESSION里面
                $_SESSION[$connectionID] = $playerAvatar->getPlayerID();
            }

            \GatewayWorker\Lib\Gateway::bindUid($connectionID,$playerAvatar->getPlayerID());
        }else{
            //用户不存在创建玩家代理   传入玩家微信获取到的信息
            $playerAvatar = new PlayerAvatar($clientRequest['client_msg'], $_SERVER['REMOTE_ADDR'],1);
            $serverContext->addOnlineCharacter($playerAvatar);
            //记录当前connectionID对应的Player在全局SESSION里面
            $_SESSION[$connectionID] = $playerAvatar->getPlayerID();
            \GatewayWorker\Lib\Gateway::bindUid($connectionID,$playerAvatar->getPlayerID());
        }

        echo "login ok\n";
        //用户登录成功后，将信息发送给玩家
        $gameServer->processServerResponse(ConnectAPI::$LOGIN_RESPONSE,1,GameType::$PUBLIC,$playerAvatar->getPlayerID(),$playerAvatar);
    }

}



?>