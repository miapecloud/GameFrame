<?php

/**
 * 用于书写游戏日志的类
 * User: LiBing
 * Date: 2017/12/10
 */
class Log
{
    public $fp;
    public $fileName;
    function __construct(){
        $this->fileName=date("Y-m-d");
        $this->fp=fopen("Applications/YourApp/$this->fileName.txt","a+");
    }

    /**
     * 写发牌日志到文件中
     * @param $roomID
     * @param $pan
     * @param $playerID
     * @param $paiArray
     */
    function faPaiLog($roomID,$pan,$playerID,$paiArray){
        if(!$this->fp){
            $this->fileName=date("Y-m-d");
            $this->fp=fopen("Applications/YourApp/$this->fileName.txt","a+");
        }
        $str=date("Y/m/d H:i:s")."-".$roomID."-".$pan."-".$playerID."-1-".json_encode($paiArray,true)."-"."发牌"."-"."某房间的某一盘为某人发了哪些牌"."\r\n";
        fwrite($this->fp,$str);
    }

    function __destruct(){
        if($this->fp){
            fclose($this->fp);
        }
    }
}