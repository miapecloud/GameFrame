<?php
/**
 * 玩家Avatar类
 * User: LiBing
 * Date: 2017/10/6
 * Time: 20:43
 */

    require_once ('Player.php');
    require_once ('MaJongPlayer.php');
    require_once ('NiuNiuPlayer.php');

    class PlayerAvatar{
        public $player;                        //代表玩家的基本信息

        public $niuNiuPlayer;                   //代表玩家的牛牛对象
        public $maJongPlayer;                   //代表玩家的麻将对象

        public $playerIp;                     //玩家的IP地址
        public $roomID;                        //玩家的房间号
        public $isOnLine;                       //记录玩家是否在线


        function __construct($playerInfo, $ip)
        {
            $this->player = new Player($playerInfo);
            $this->niuNiuPlayer=new NiuNiuPlayer();
            $this->maJongPlayer=new MaJongPlayer();
            $this->playerIp = $ip;
            $this->roomID=0;
            $this->isOnLine=true;
        }

        function getPlayerID(){
            return $this->player->userUid;
        }

        function __clone(){}

    }

?>