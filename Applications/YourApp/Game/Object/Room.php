<?php
/**
 * 表示房间的类，是所有各种房间的基类
 * User: LiBing
 * Date: 2017/12/19
 * Time: 17:02
 */
    class Room{
        public $roomID;        //房间ID
        public $roomType;      //房间类型 牛牛房间还是麻将房间
        public $playerList=array();    //玩家队列，存储的为用户的UID

        public $isBegin;            //是否开始
        public $totalCount;       //房间总盘数
        public $currentRound;     //当前盘数


        private $dissolveCount = 0; //同意解散房间人数数量
        private $refuseDissolve = 0; //拒绝解散房间人数数量
        private $isDissolve=false;       //是否解散房间


        /**
         * 获取房间人数
         */
        public function getPlayerNum(){
            return count($this->playerList);
        }

        /**
         * 添加玩家到房间
         * @param $playerID
         */
        public function addPlayerToList($playerID)
        {
            foreach($this->playerList as $p){
                if($p==$playerID){
                    return;
                }
            }
            array_push($this->playerList,$playerID);
        }

        /**
         * 从房间移除玩家
         * @param $userUid
         */
        public function delPlayerToList($userUid)
        {
            $pos=array_search($userUid,$this->playerList);
            array_splice($this->playerList,$pos,1);
        }
    }
?>