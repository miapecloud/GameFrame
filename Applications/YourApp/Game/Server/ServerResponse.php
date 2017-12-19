<?php
/**
 * 服务器返回信息类
 * User: LiBing
 * Date: 2017/10/4
 * Time: 14:07
 */

    class ServerResponse{

        function processServerResponse($responseType,$status,$gameType,$responseTarget, $responseMsg){
            switch($responseType) {
                //服务器对用户进行推送
                case ConnectAPI::$LOGIN_RESPONSE:
                    $this->responseUserBasic($responseType,$status,$gameType,$responseTarget,$responseMsg);
                    break;

                //服务器组推送
                case ConnectAPI::$JOIN_ROOM_NOTICE:
                    $this->responseGroupBasic($responseType,$status,$gameType,$responseTarget,$responseMsg);
                    break;

            }
        }

        /**
         * 用于处理响应给一个用户的函数
         * @param 响应的类型
         * @param 响应状态
         * @param 游戏类型
         * @param 响应用户UID
         * @param 响应的消息体
         */
        public function responseUserBasic($responseType,$status,$gameType,$userUid,$responseMsg){
            $msg["head_code"]=$responseType;
            $msg["status"]=$status;
            $msg["game_type"]=$gameType;
            $msg["client_msg"]=json_decode(json_encode($responseMsg));

            \GatewayWorker\Lib\Gateway::sendToUid($userUid,$msg);
        }


        /**
         * 用于处理响应给一个组的函数
         * @param $responseType 响应的类型
         * @param $status 响应状态
         * @param $gameType 游戏类型
         * @param $groupId 组ID，房间ID
         * @param $responseMsg 响应的消息体
         */
        function responseGroupBasic($responseType,$status,$gameType,$groupId,$responseMsg){
            $msg["head_code"]=$responseType;
            $msg["status"]=$status;
            $msg["game_type"]=$gameType;
            $msg["client_msg"]=json_decode(json_encode($responseMsg));

            \GatewayWorker\Lib\Gateway::sendToGroup($groupId,$msg);
        }

    }



?>