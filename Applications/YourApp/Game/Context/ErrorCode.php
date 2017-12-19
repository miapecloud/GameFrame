<?php
/**
 * 定义了服务器所有的错误信息
 * User: LiBing
 * Date: 2017/9/13
 * Time: 1:21
 */
    class ErrorCode{

        public static $ERROR_NOT_LOGIN = "此帐号还没有登录";
        public static $ERROR_EMPTY_ROOM = "房間是空的";
        public static $ERROR_NOT_THIS_ROOM = "你不是这个房间的";

        public static $ERROR_NO_SUCH_MAJANG = "牌数组里没有这张牌";
        public static $Error_000008 = "牌数组里已经有4张牌";
        public static $Error_000009 = "摸牌出錯";
        public static $Error_000010 = "房间次数已经用完";
        public static $Error_000011 = "房间人已经满了";
        public static $Error_000012 = "房间不存在";
        public static $Error_000013 = "你已经在房间，不能重复创建";
        public static $Error_000014 = "房卡不足！";
        public static $Error_000015 = "只有庄家才能开始";
        public static $Error_000016 = "杠牌出错";
        public static $Error_000017 = "你已经在房间，不能加入新房间";
        public static $Error_000018 = "你输入的房间号不存在!";
        public static $Error_000019 = "参数传递错误!";
        public static $Error_000020 = "你的抽奖次数不足!";
        public static $Error_000021 = "今天你还未进行过游戏!";
        public static $Error_000022 = "你的账号在其他设备登录!";

    }



?>