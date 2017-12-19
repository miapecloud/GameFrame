<?php
/**
 * 管理麻将房间的管理器
 * User: LiBing
 * Date: 2017/10/6
 * Time: 14:37
 */
    require_once ('MaJongRoom.php');
    require_once __DIR__.'/../RoomManager.php';
    use \GatewayWorker\Lib\Db;
    class MaJongRoomManager extends RoomManager {

        /**
         * 辅助创建麻将房间的函数
         * @param $playerID 创建人ID
         * @param $createRequest 房间相关参数
         * @return int 返回房间ID
         */
        public function createMaJongRoom($playerID,$createRequest){
            //解析创建房间的请求，创建实际的房间
            $room = new MaJongRoom($createRequest);
            //设置房主ID
            $room->roomCreator=$playerID;

            //在数据库中创建房间并得到它的房间ID
            $roomID=$this->createRoomToDB($room);

            //设置房间ID
            $room->roomID=$roomID;

            //将房间保存到麻将房间列表中
            $this->addMaJongRoomToList($roomID,$room);
            return $roomID;
        }

        /**
         * 辅助创建房间到数据库中的函数
         * @param $room 房间的基本对象
         * @return int 返回房间ID
         */
        public function createRoomToDB($room){
            $db = Db::instance('game');

            $nowDay=date("Y-m-d");
            //先检查数据库里当天是否已经创建了房间号
            do{
                $roomID=mt_rand(100000, 999999);
                $res=$db->select('room_id')->from('qg_majong_room')->where("room_id=:id and create_time like :create_time")->bindValues(array('id'=>$tempId,'create_time'=>$nowDay.'%'))->query();//查询此ID
            }while(count($res)>0);

            //创建一个房间到数据库
            $db->insert('qg_majong_room')->cols(array(
                'room_id'=>$roomID,
                'create_time'=>date("Y-m-d H:i:s"),
            ))->query();
            return $roomID;
        }
    }



?>