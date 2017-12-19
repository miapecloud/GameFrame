<?php
/**
 * 管理牛牛的管理器
 * User: LiBing
 * Date: 2017/10/6
 * Time: 14:37
 */
    require_once('NiuNiuRoom.php');
    require_once __DIR__.'/../RoomManager.php';

    use \GatewayWorker\Lib\Db;
    class NiuNiuRoomManager extends RoomManager {

        /**
         * 辅助创建牛牛房间的函数
         * @param $playerID 创建人ID
         * @param $createRequest 房间相关参数
         * @return int 返回房间ID
         */
        public function createNiuNiuRoom($playerID,$createRequest){
            //解析创建房间的请求，创建实际的房间
            $room = new NiuNiuRoom($createRequest);
            //设置房主ID
            $room->roomCreator=$playerID;

            //在数据库中创建房间
            $roomID=$this->createRoomToDB($room);

            //将房间记录到列表中
            $this->addNiuNiuRoomToList($roomID,$room);

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
                $res=$db->select('room_id')->from('qg_niuniu_room')->where("room_id=:id and create_time like :create_time")->bindValues(array('id'=>$roomID,'create_time'=>$nowDay.'%'))->query();//查询此ID
            }while(count($res)>0);


            //创建一个房间到数据库
            $db->insert('qg_niuniu_room')->cols(array(
                'room_id'=>$roomID,
                'create_time'=>date("Y-m-d H:i:s"),
            ))->query();
            return $roomID;
        }

    }

?>