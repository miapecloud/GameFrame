<?php
/**
 * 用于麻将的房间类 继承自Room
 * User: LiBing
 * Date: 2017/12/18
 * Time: 22:30
 */
    require_once __DIR__.'/../../Object/Room.php';
    require_once('MaJongManager.php');
    class MaJongRoom extends Room {
        //麻将牌堆
        private $maJongManager;
        //庄ID
        public $currentZhuang;
        //创建者ID
        public $roomCreator;

        //麻将房间其它数据
        public $endPoints;//底分
        public $fanNum;//最高番数
        //..............


        /**
         * 构造函数，传入房间参数
         * @param $roomRequest 前台传入的房间参数，房间创建者是谁
         */
        function __construct($roomRequest)
        {
            //初始化所有参数
            $this->maJongManager = new MaJongManager();

            //.................所有的房间参数
        }

        /**
         * 获取majongManager函数  因为maJongManager是私有的  不能直接访问
         * @return MaJongManager
         */
        function getMaJongManager(){
            return $this->maJongManager;
        }

        /**
         * 获取玩家在房间位置的函数，默认庄为1，其它玩家按照以下排列
         *    3
         * 4     2
         *    1
         *
         * @return int
         */
        private function getPlayerPos(){
            $tempPos = 0;
            for($i = 0; $i < count($this->playerList); $i++){
                if($this->playerList[$i] == $this->currentZhuang){
                    $tempPos = $i + 1;
                    break;
                }
            }
            if(($tempPos == count($this->playerList)) && (count($this->playerList) == 3)){
                $tempPos++;
            }
            return $tempPos;
        }

        /**
         * 开始某一局游戏，返回骰子和发的牌
         * @return array
         */
        function beginGame(){
            //返回结果
            $returnResult = array();
            if($this->currentRound == 0){
                //当第一局的时候,随机一个庄
                $this->randFirstZhuang();
            }else{
                ////////////////////////////////////////////////////////
                //否则，应该是上一局最先胡牌的人或流局的时候就维持原始庄
                //从MajangRoomRecorder获取数据，目前还没有处理
                ////////////////////////////////////////////////////////
            }
            $this->currentRound++;
            $this->maJongManager->createMaJong();
            $touZi = $this->maJongManager->startTouZi($this->getPlayerPos());

            $tempPlayerList = array();
            array_push($tempPlayerList, $this->currentZhuang);//将庄做为第一个添加到当局队列中
            for($i = 0; $i < count($this->playerList); $i++){
                //检查此房间队列中、如果不是庄添加到当前队列
                if($this->playerList[$i]!= $this->currentZhuang){
                    array_push($tempPlayerList, $this->playerList[$i]);
                }
            }
            //起牌函数，这个函数只返回一人13张牌
            $fapaiResult = $this->maJongManager->qiPai($tempPlayerList);
            $returnResult['zhuang'] = $this->currentZhuang;
            $returnResult['touZi'] = $touZi;
            $returnResult['faPai'] = $fapaiResult;
            $returnResult['cardsNum']=108-count($this->playerList)*13;

            return $returnResult;
        }



        /**
         * 第一盘开始的时候，随机选庄
         * 存入属性currentZhuang
         */
        private function randFirstZhuang(){
            $id = mt_rand(0, count($this->playerList) - 1);
            $this->currentZhuang = $this->playerList[$id];
        }

    }

?>