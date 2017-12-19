<?php
/**
 * 总管麻将的类，每一个房间开始游戏，都要从本类生成一个对象，代表某一局的过程
 * User: LiBing
 * Date: 2017/12/18
 * Time: 10:51
 */
     class MaJongManager{
         public $maJong = array();  //当前牌堆,默认情况下为一个1,1,1,1, 2,2,2,2 .... 29,29,29,29构成的乱序数组
         public $oldMaJong = array(); //老的牌堆，即上一局的牌堆

         public $totalNum = 108;   //总牌数

         public $curCardNum = 0;   //当前是第几张牌

         public $touZi = array(); //每盘扔骰子的两个骰子

         public $startIndex = 0; //牌堆开始的索引

         /**
          *构造函数，创建初始牌堆，这个只调用一次
          */
         function __construct()
         {
            $this->createMajong();
         }


         /**
          *每一局重新开始的时候，初始化所有参数
          * 并在上一局的基础上洗牌,历史牌堆是为了防止多次初始化牌
          */
         function createMaJong(){
             //如果没有上一局的麻将，则生成新的麻将
             if(!$this->oldMaJong){
                 $this->initPai();
                 //保存本局麻将
                 $this->oldMaJong = $this->maJong;
             }else{
                 $this->maJong=$this->oldMaJong;
             }
             //洗牌
             $this->xiPai();
         }

         /**
          *创建初始牌堆（给牌进行赋值）
          */
         private function initPai(){
             //生成牌型
             for($type = 0; $type < 3; $type++){
                 for($num = 1; $num <= 9; $num++ ){
                     $count = 0;
                     while($count < 4){
                         array_push($this->maJong, $type * 10 +$num);
                         $count++;
                     }
                 }
             }
         }

         /**
          *洗牌函数，打乱牌堆顺序
          */
         private function xiPai(){
             if(count($this->oldMaJong) > 0){
                 $this->maJong = $this->oldMaJong;
             }
            //洗牌
             shuffle($this->maJong);
             shuffle($this->maJong);
             shuffle($this->maJong);
         }

         /**
          *丢骰子，随机生成两个骰子，得到结果
          * 传入哪个玩家丢的骰子，玩家位置由下确定，拿牌位置也是这么开始
          *       3
          *    4     2
          *       1
          * //如果是三家的情况和四家一样，只是座位如下所述
          *       虚拟
          *    3       2
          *        1
          * 丢完骰子后，记录开始位置
          * 注意：1、3: 13凳牌
          *       2、4: 14凳牌
          * @param $playerPosition 玩家位置，按上图取得房间列表中玩家在数组中的ID（从1开始)
          * @return 骰子的信息 array(骰子1， 骰子2)
          */
         function startTouZi($playerPosition){
             //随机掷骰子
             $a = mt_rand(10000, 50000) % 6 + 1;
             $b = mt_rand(50000, 100000) % 6 + 1;

             //记录骰子
             $this->touZi[0] = $a;
             $this->touZi[1] = $b;
             //记录最小点数
             $tempSmall = ($a <= $b ? $a : $b);


            //计算牌堆开始对应的位置 1, 2, 3, 4
             $tempStart = $playerPosition + ($a + $b) % 4 - 1;
             if($tempStart > 4){
                 $tempStart %= 4;
             }
             if($tempStart == 0){
                 $tempStart = 4;
             }

             //处理起牌位置，记录入startIndex
             switch($tempStart){
                  case 1:
                      $this->startIndex =  2 * $tempSmall;
                      break;
                 case 2:
                     $this->startIndex = 26 + 2 * $tempSmall;
                     break;
                 case 3:
                     $this->startIndex = 54 + 2 * $tempSmall;
                     break;
                 case 4:
                     $this->startIndex = 80 + 2 * $tempSmall;
                     break;

             }

             return array($a, $b);
         }


         /**
          * 发牌的函数
          *丢完骰子后，开始起牌, 需要知道哪个玩家开始，玩家排列顺序
          * 而实际从玩家的哪凳牌开始，由骰子的最小数确定
          *       3
          *    4     2
          *       1
          * @param $playerArray 是一个按照庄家为1，按照上图顺序传进来的数组玩家ID，{庄家ID1, playerID1, playerID2...}
          * @return array 数组{{玩家ID， 手牌},{'playerID', 'pai'}, {'playerID', 'pai'} ...}
          *         pai: 是一个数组，1-9筒子，11-19条子，21-29万子
          */

         function qiPai($playerArray){
             //初始化返回结果
             $paiArray = array();
             for($i = 0; $i < count($playerArray); $i++){
                 $paiArray[$i] = array('playerID'=>$playerArray[$i], 'pai'=>array());
             }

             //记录最初开始位置，为了最后发完牌后删除牌堆相应数据
             $oldStart = $this->startIndex;
             //先发每个玩家前12张牌
             $count = 1;        //控制玩家数量
             do {
                 for ($i = 0; $i < count($playerArray); $i++) {
                     $c = 1;  //控制发牌数量
                     while ($c <= 4) {
                         array_push($paiArray[$i]['pai'], $this->majong[$this->startIndex++]);
                         $this->startIndex = $this->startIndex % count($this->maJong);
                         $c++;
                     }
                 }
                 $count++;
             }while($count < 4);

             //发最后一人一张的牌，根据玩家数确定
             for($i = 0; $i < count($playerArray); $i++){
                 array_push($paiArray[$i]['pai'], $this->majong[$this->startIndex++]);
                 $this->startIndex = $this->startIndex % count($this->maJong);
             }

             //删除所有牌，先计算从开始位置到最后剩下的牌，这里庄家的最后一张牌作为摸牌处理
             $startRight = count($this->maJong) - $oldStart;
             //因为第一次发牌要发52张，所以如果剩下的不足52张，则意味着，
             $tempNum=count($playerArray)*13;
             if($startRight < $tempNum){
                 //先删除右边剩余的牌
                 array_splice($this->maJong, $oldStart, $startRight);
                 //再计算左边剩余的牌，并删除
                 $left_length = $tempNum - $startRight;
                 array_splice($this->maJong, 0, $left_length);
             }
             else{
                 array_splice($this->maJong, $oldStart, $tempNum);
             }
             return $paiArray;
         }


         /**
          * 摸牌函数
          * @return 成功时返回玩家摸的牌 否则为false
          */
         function moPai(){
             if(count($this->maJong) > 0){
                 $temp = $this->maJong[0];
                 array_splice($this->maJong, 0, 1);
                 return $temp;
             }
             return false;
         }
     }



?>