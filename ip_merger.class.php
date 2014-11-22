<?php

/**
 * IPMerger
 * Merge more than one subnets
 *
 * @author sskaje (https://sskaje.me/ sskaje@gmail.com)
 */
class IPMerger
{
    const BEGIN = 0;
    const END = 1;

    protected $range = array();
    /**
     * Single Dots
     * Not yet supported
     *
     * @var array
     */
    protected $dots = array();
    /**
     * Maximum cidr supported
     * Max cidr accepted in EdgeOS is 31
     *
     * @var int
     */
    protected $max_cidr = 31;

    /**
     * set the maximum allowed value of cidr
     *
     * @param int $cidr
     */
    public function set_max_cidr($cidr)
    {
        if ($cidr > 32) {
            $cidr = 32;
        }
        $this->max_cidr = $cidr;
    }

    /**
     * add ip ranges
     *
     * @param int $begin
     * @param int $end
     */
    protected function _add($begin, $end)
    {
        if ($begin == $end) {
            $this->dots[$begin] = $begin;
            return;
        }
        if ($end < $begin) {
            list($begin, $end) = array($end, $begin);
        }
        $flag_insert_begin = true;
        $flag_insert_end = true;

        if (isset($this->range[$begin])) {
            if ($this->range[$begin] == self::END) {
                unset($this->range[$begin]);
                $flag_insert_begin = false;
            }

        }
        if (isset($this->range[$end])) {
            if ($this->range[$end] == self::BEGIN) {
                unset($this->range[$end]);
                $flag_insert_begin = false;
            }
        }

        if ($flag_insert_begin) {
            $this->range[$begin] = self::BEGIN;
        }
        if ($flag_insert_end) {
            $this->range[$end] = self::END;
        }
    }

    /**
     * add ip range in long
     *
     * @param int $begin
     * @param int $end
     */
    public function addByRange($begin, $end)
    {
        $this->_add($begin, $end);
    }

    /**
     * add ip range in IPv4 format
     *
     * @param string $begin
     * @param string $end
     */
    public function addByIPRange($begin, $end)
    {
        $this->_add(ip2long($begin), ip2long($end));
    }

    /**
     * add ip range in ip/cidr format
     *
     * @param string $subnet
     */
    public function addBySubnet($subnet)
    {
        $ip = explode("/", $subnet);
        if (!isset($ip[1])) {
            $ip[1] = $this->max_cidr;
        }

        if (!class_exists('IPCalculator')) {
            require(__DIR__ . '/ipcalc.class.php');
        }
        list($subnet, $broadcast, $netmask) = IPCalculator::ipCidr2Subnet($ip[0], $ip[1], false);

        $this->addByRange($subnet, $broadcast);
    }

    /**
     * clean duplicated or overlapped
     */
    public function clean()
    {
        ksort($this->range);
        foreach ($this->range as $num=>$type) {
            //echo long2ip($num) . "\t$type\n";
            if ($type == self::END && isset($this->range[$num+1]) && $this->range[$num+1] == self::BEGIN) {
                unset($this->range[$num]);
                unset($this->range[$num+1]);
            }

            if (isset($this->dots[$num])) {
                unset($this->dots[$num]);
            }
        }
        $result = array();
        $last = self::END;
        $last_add = '';
        foreach ($this->range as $num=>$type) {
            if ($type != $last) {
                $result[$num] = $type;
                $last_add = $num;
            }
            $last = $type;
        }
        if (isset($num) && $num != $last_add) {
            unset($result[$last_add]);
            $result[$num] = self::END;
        }
/*
        # not yet supported
        foreach ($this->dots as $dot) {

        }
*/
        $this->range = $result;
    }

    /**
     * get merged subnets
     *
     * @return array
     */
    public function getSubnets()
    {
        $this->clean();

        $c = 0;
        $result = array();
        foreach ($this->range as $num=>$type) {
            $result[intval($c / 2)][$c % 2] = long2ip($num);
            ++ $c;
        }

        return $result;
    }
}



# EOF