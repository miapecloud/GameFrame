<?php
/**
 * 玩家基本信息类
 * User: LiBing
 * Date: 2017/9/28
 * Time: 10:33
 */
use \GatewayWorker\Lib\Db;
class Player{
    public $userOpenID;
    //玩家的ID，在数据库中的唯一标记
    public $userUid;
    //玩家的昵称
    public $userNickname;

    function __construct($playerInfo,$type)
    {
        //初始化玩家信息
        $this->userOpenId=$playerInfo["openId"];
        $this->userNickname=$playerInfo["nickName"];
        //检查数据库里面是否有该玩家的信息，如果没有，创建本记录
        $this->createUserToDB();
    }

    //创建游戏玩家（检查是否已注册、否则注册）
    function createUserToDB(){
        $db = Db::instance('game');

        $s = $db->select('user_openid,user_uid,user_nickname')
            ->from('qg_user')->where("user_openid=:id")->bindValues(array('id'=>$this->userOpenId))->query();
        if(!$s){
            //代表着此玩家没有注册，所以需要注册
            $resUid=$db->insert('qg_user')->cols(array(
                'user_openid'=>$this->userOpenId,
                'user_nickname'=>$this->userNickname,
                'register_time'=>date("Y-m-d H:i:s"),
            ))->query();

        }else{
            //已经注册 设置当前用户UID(并更新微信上的数据到数据库)
            $this->userUid=(int)$s[0]["user_uid"];

            $db->update('qg_user')->cols(array(
                'user_nickname'=>$this->userNickname,
            ))->where("user_openid=:id")->bindValues(array('id'=>$this->userOpenId))->query();
        }

    }

}

?>