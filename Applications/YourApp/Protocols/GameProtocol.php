<?php
/**
 * Created by PhpStorm.
 * User: zlkmu
 * Date: 2017/9/6
 * Time: 0:07
 */
namespace Protocols;
class GameProtocol{
    //TOTAL_LENGTH(4B) + HEAD_CODE(4B) + status(4B) + GAME_TYPE(2B) + CONTENT
    //发包的整体长度
    const PROTOCOL_PACK_LENGTH = 4;
    //实际信息的前缀包体长度
    const PROTOCOL_PREV_LENGTH = 14;
    public static function input($recv_buffer){
        //先获取实际长度
        if(strlen($recv_buffer) < self::PROTOCOL_PACK_LENGTH){
            return 0;
        }
        $unpack_data = unpack('Ntotal_length', $recv_buffer);
        return $unpack_data['total_length'];
    }

    public static function decode($recv_buffer){
        //解析前14个字节
        $recv_data = unpack('Ntotal_length/Nhead_request/Nstatus/ngame_type', $recv_buffer);
        $recv_data['client_msg'] = json_decode(substr($recv_buffer, self::PROTOCOL_PREV_LENGTH), true);
        return $recv_data;
    }

    public static function encode($data){
        $headCode = $data['head_code'];
        $status = $data['status'];
        $game_type = $data['game_type'];

        $body_json_str = json_encode($data['client_msg']);
        $total_length = self::PROTOCOL_PREV_LENGTH + strlen($body_json_str);

        return pack('N', $total_length).pack('N', $headCode).pack('N', $status).pack('n', $game_type).$body_json_str;
    }
}

?>