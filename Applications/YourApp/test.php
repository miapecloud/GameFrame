<?php
/**
 * 测试两个点之间的直线距离
 * User: Administrator
 * Date: 2017/11/29
 * Time: 13:28
 */


function getPlayerDistance($lat1=104.5446,$log1=28.72096,$lat2=104.619939,$log2=28.800269){
    $R=6378137.0;//地球半径

    //将角度转化为弧度
    $radLat1=($lat1*M_PI/180.0);
    $radLog1=($log1*M_PI/180.0);
    $radLat2=($lat2*M_PI/180.0);
    $radLog2=($log2*M_PI/180.0);
    //纬度的差值
    $a=$radLat1-$radLat2;
    //经度差值
    $b=$radLog1-$radLog2;
    //弧度长度
    $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
    //获取长度
    $s=$s*$R;
    //返回最接近参数的 long。结果将舍入为整数：加上 1/2
    $s=round($s*10000)/10000;
    return $s;
}

var_dump(getPlayerDistance());