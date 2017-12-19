<?php
/**
 * 表示扑克牌的类
 * User: LiBing
 * Date: 2017/9/28
 * Time: 10:34
 */
    class NiuNiuCard{
        //type : 1 : 黑桃 2：红桃 3：梅花 4：方块
        public $type;
        //num: 1-13对应牌型
        public $num;
        //count:对应的点数
        public $count;

        /**
         * @param $type  牌型
         * @param $num   牌号
         * @param $count 牌点数 >10都作为10
         */
        function __construct($type, $num, $count)
        {
            $this->type = $type;
            $this->num = $num;
            $this->count = $count;
        }
    }


?>