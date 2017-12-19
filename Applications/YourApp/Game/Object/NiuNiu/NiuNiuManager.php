<?php

/**
 * 总管牛牛的类，每一个房间开始游戏，都要从本类生成一个对象，代表某一局的过程
 * User: LiBing
 * Date: 2017/10/9
 * Time: 16:49
 */
require_once "NiuNiuCard.php";
class NiuNiuManager
{
    private $niuNiu=array();//当前牌堆
    private $startIndex=0;//牌堆开始的索引

    function __construct()
    {
        $this->initPai();
        $this->xipai();
    }

    /**
     * 初始化牌堆
     */
    function initPai(){
        //生成牌型
        for($type = 1; $type <= 4; $type++){
            for($num = 1; $num <= 13; $num++ ){
                array_push($this->niuNiu, new NiuNiuCard($type, $num, $num > 10 ? 10 : $num));
            }
        }
    }

    /**
     * 洗牌函数
     */
    function xiPai(){
        //洗牌
        shuffle($this->niuNiu);
        shuffle($this->niuNiu);
        shuffle($this->niuNiu);
    }

    /**
     * 发牌函数(取五张牌返回)
     * @return array 返回发牌的结果
     */
    public function faPai(){
        //发牌
        $quPai=array();
        for($i=0;$i<5;$i++){
            $quPai[$i]=$this->niuNiu[$this->startIndex];
            $this->startIndex++;//让当前索引自加1

            //取模以保证循环,由于有管理员，管理员取牌后，需要删除取掉的牌，剩余的牌会减少
            $this->startIndex %= count($this->niuNiu);
        }
        return $quPai;
    }


}