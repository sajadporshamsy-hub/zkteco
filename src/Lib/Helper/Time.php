<?php

namespace Rats\Zkteco\Lib\Helper;

use Rats\Zkteco\Lib\ZKTeco;

class Time
{
    /**
     * @param ZKTeco $self
     * @param string $t Format: "Y-m-d H:i:s"
     * @return bool|mixed
     */
    static public function set(ZKTeco $self, $t)
    {
        $self->_section = __METHOD__;
        $command = Util::CMD_SET_TIME;
        $time = is_numeric($t) ? $t : strtotime($t);        
        $year = (int)date('Y', $time);
        $month = (int)date('n', $time);
        $day = (int)date('j', $time);
        $hour = (int)date('H', $time);
        $minute = (int)date('i', $time);
        $second = (int)date('s', $time);
        $encoded = (($year - 2000) * 12 * 31 + ($month - 1) * 31 + $day - 1) * 
                   (24 * 60 * 60) + ($hour * 60 * 60 + $minute * 60 + $second);
        $command_string = pack('I', $encoded);
        return $self->_command($command, $command_string);
    }
    /**
     * @param ZKTeco $self
     * @return bool|mixed
     */
    static public function get(ZKTeco $self)
    {
        $self->_section = __METHOD__;
        $command = Util::CMD_GET_TIME;
        $command_string = '';
        $ret = $self->_command($command, $command_string);
        if ($ret) {
            return Util::decodeTime(hexdec(Util::reverseHex(bin2hex($ret))));
        } else {
            return false;
        }
    }
}
