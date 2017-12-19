<?php
/**
 * 管理服务器房间的基类
 * User: LiBing
 * Date: 2017/10/6
 * Time: 11:50
 */
    require_once('Room.php');
    class RoomManager{
        //保存当前房间的列表,类型为room
        private $niuNiuRoomList = array();
        private $maJongRoomList = array();

        //辅助函数，添加牛牛房间到管理列表
        public function addNiuNiuRoomToList($roomId,$room){
            $this->niuNiuRoomList["$roomId"]=$room;
        }

        //获取到一个牛牛房间
        public function getNiuNiuRoom($roomId){

            if(!empty($this->niuNiuRoomList["$roomId"])){
                return $this->niuNiuRoomList["$roomId"];
            }else{
                return false;
            }
        }

        //获取牛牛房间列表
        public function getNiuNiuRoomList(){
            return $this->niuNiuRoomList;
        }

        //辅助删除牛牛房间
        public function removeNiuNiuRoom($roomID){
            unset($this->niuNiuRoomList[$roomID]);
        }


        //辅助函数，添加麻将房间到管理列表
        public function addMaJongRoomToList($roomId,$room){
            $this->maJongRoomList["$roomId"]=$room;
        }

        //获取到一个麻将房间
        function getMaJongRoom($roomId){

            if(!empty($this->maJongRoomList["$roomId"])){
                return $this->maJongRoomList["$roomId"];
            }else{
                return false;
            }
        }

        //获取麻将房间列表
        public function getMaJongRoomList(){
            return $this->maJongRoomList;
        }

        //辅助函数，删除麻将房间
        public function removeMaJangRoom($roomID){
            unset($this->maJongRoomList[$roomID]);
        }


    }



?>