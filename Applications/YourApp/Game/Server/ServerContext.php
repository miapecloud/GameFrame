<?php
/**
 * 用于管理整个服务器的玩家
 * User: zlkmu
 * Date: 2017/10/5
 * Time: 23:18
 */
    class ServerContext{

        /**
         * 所有在线的玩家，这里记录的是玩家的代理类，即PlayerAvatar
         */
        private $ALL_ONLINE_PLAYER = array();

        /**
         * 所有掉线的玩家，这里记录的是玩家的代理类，即PlayerAvatar
         */
        private $ALL_OFFLINE_PLAYER = array();



        /**
         * 增加一个上线玩家
         * @param PlayerAvatar playerAvatar
         */
        public function addOnlineCharacter($player){
            array_push($this->ALL_ONLINE_PLAYER, $player);
        }

        /**
         * 增加一个掉线玩家
         * @param 参数为PlayerAvatar playerAvatar
         */
        public function addOfflineCharacter($player){
            //先移除在线玩家，然后添加离线玩家
            for($i = 0; $i < count($this->ALL_ONLINE_PLAYER); $i++){
                if($this->ALL_ONLINE_PLAYER[$i]->getPlayerID() == $player->getPlayerID()){
                    array_splice($this->ALL_ONLINE_PLAYER, $i,1);
                    break;
                }
            }
            array_push($this->ALL_OFFLINE_PLAYER, $player);
        }

        /**
         * 获取一个在线玩家
         * @param UUID playerID
         * @return Player player
         */
        public function getPlayerFromOnLine($playerID){
            foreach($this->ALL_ONLINE_PLAYER as $player){
                if($player->getPlayerID() == $playerID){
                    return $player;
                }
            }
            return false;
        }

        /**
         * 获取一个掉线玩家
         * @param UUID playerID
         * @return Player player
         */
        public function getPlayerFromOffLine($playerID){
            if(count($this->ALL_OFFLINE_PLAYER) == 0){
                return false;
            }
            foreach($this->ALL_OFFLINE_PLAYER as $player){
                if($player->getPlayerID() == $playerID){
                    return $player;
                }
            }
            return false;
        }

        /**
         * 获取一个玩家Avatar(包括在线和掉线)
         * @param $playerID
         * @return PlayerAvatar
         */
        public function getPlayerAvatar($playerID){
            $playerAvatar=$this->getPlayerFromOnLine($playerID);
            if(!$playerAvatar){
                $playerAvatar=$this->getPlayerFromOffLine($playerID);
            }
            return $playerAvatar;
        }

        /**
         * 移除一个掉线玩家
         * @param $playerAvatar
         * @return bool
         */
        public function removeOfflinePlayer($playerAvatar){
            for($i = 0; $i < count($this->ALL_OFFLINE_PLAYER); $i++){
                if($this->ALL_OFFLINE_PLAYER[$i]->getPlayerID() == $playerAvatar->getPlayerID()){
                    array_splice($this->ALL_OFFLINE_PLAYER, $i,1);
                    return true;
                    break;
                }
            }
            return false;
        }






    }



?>