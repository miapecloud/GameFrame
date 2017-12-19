<?php
/**
 * 用于牛牛的房间类 继承自Room
 * User: LiBing
 * Date: 2017/10/4
 * Time: 22:30
 */
require_once __DIR__.'/../../Object/Room.php';
require_once('NiuNiuManager.php');
class NiuNiuRoom extends Room {

    //牛牛牌堆
    private $niuNiuManager;
    //庄ID
    public $currentZhuang;
    //创建者ID
    public $roomCreator;

    //麻将房间其它数据
    public $endPoints;//底分
    //..............

    /**
     * @param $roomRequest 创建房间时，玩家传来的房间参数
     */
    function __construct($roomRequest)
    {
        //初始化所有参数
        $this->niuNiuManager=new NiuNiuManager();

        //.................所有的房间参数

    }

    /**
     * 获取niuNiuManager函数  因为niuNiuManager是私有的  不能直接访问
     * @return MaJongManager
     */
    function getNiuNiuManager(){
        return $this->niuNiuManager;
    }

}
?>