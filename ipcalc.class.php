<?php
/**
 * IP Calculator
 *
 * @author sskaje (https://sskaje.me/ sskaje@gmail.com)
 */
class IPCalculator
{
    /**
     * Simple IPv4 Format Check
     *
     * @param string $ip
     * @return bool
     */
    static public function isIPv4($ip)
    {
        return preg_match('#^\d+\.\d+\.\d+\.\d+$#', $ip);
    }
    /**
     * Get subnets from begin ip and end ip
     *
     * @param string $begin
     * @param string $end
     * @return bool|array     false on invalid ip, array(array(subnet=>, cidr=>), ...)
     */
    static public function getSubnetsFromRange($begin, $end)
    {
        if (!self::isIPv4($begin) || !self::isIPv4($end)) {
            return false;
        }
        $begin = ip2long($begin);
        $end   = ip2long($end);

        if ($end < $begin) {
            return false;
        }

        $lsb = self:: _getLSBPos($begin);
        $result = array();
        self::_getAllSubnets($begin, $end, $lsb, $result);
        return $result;
    }

    /**
     * Find the right most bit which is 1 from a int
     *
     * @param int $int
     * @return int
     */
    static protected function _getLSBPos($int)
    {
        $test_bit = 1;
        for ($i=0; $i < 32; $i++) {
            if ($int & $test_bit) {
                break;
            }

            $test_bit <<= 1;
        }

        return $i;
    }

    /**
     * Get all subnets from begin ip and end ip and LSB pos
     *
     * @param int $begin
     * @param int $end
     * @param int $pos  
     * @param array & $ret
     * @return void
     */
    static protected function _getAllSubnets($begin, $end, $pos, &$ret=array())
    {
        if ($pos > 32 || $pos < 0) {
            return false;
        }
        $cidr = 32 - $pos;
        list($subnet, $broadcast, $netmask) = self::_ipCidr2Subnet($begin, $cidr);

        #var_dump($pos, sprintf('%032s', decbin($begin)), decbin($netmask), sprintf('%032s', decbin($broadcast)));
        #var_dump(long2ip($end), long2ip($broadcast));

        if ($end > $broadcast) {
            $ret[] = array('subnet'=>$subnet, 'cidr'=>$cidr);
            # broadcast + 1, next loop
            $begin = $broadcast + 1;
            $lsb = self::_getLSBPos($begin);
            self::_getAllSubnets($begin, $end, $lsb, $ret);
        } else if ($end == $broadcast) {
            $ret[] = array('subnet'=>$subnet, 'cidr'=>$cidr);
        } else {
            self::_getAllSubnets($begin, $end, $pos - 1, $ret);
        }
    }

    /**
     * Calculate subnet, netmask, broadcast addresses from IP and CIDR
     *
     * @param int    $ip
     * @param int    $cidr
     * @return bool|array   false on invalid ip or cidr, array(long subnet, long broadcast, long netmask) 
     */
    static protected function _ipCidr2Subnet($ip, $cidr)
    {
        if ($cidr > 32 || $cidr < 0) {
            return false;
        }
        $netmask = (pow(2, $cidr) - 1) << (32 - $cidr);
        $subnet = $ip & $netmask;
        $broadcast = $subnet | (pow(2, 32 - $cidr) - 1);
        return array($subnet, $broadcast, $netmask);
    }

    /**
     * Calculate subnet, netmask, broadcast addresses from IP and CIDR
     *
     * @param string $ip
     * @param int    $cidr
     * @return bool|array   false on invalid ip or cidr, array(ipv4 subnet, ipv4 broadcast, ipv4 netmask) 
     */
    static public function ipCidr2Subnet($ip, $cidr)
    {
        if (self::isIPv4($ip)) {
            $ip = ip2long($ip);
        }
        list($subnet, $broadcast, $netmask) = self::_ipCidr2Subnet($ip, $cidr);
        return array(long2ip($subnet), long2ip($broadcast), long2ip($netmask));
    }
    /**
     * Convert CIDR to netmask
     *
     * @param int $cidr
     * @return int
     */
    static protected function _Cidr2Netmask($cidr)
    {
        return (pow(2, $cidr) - 1) << (32 - $cidr);
    }
    /**
     * Convert CIDR to netmask
     *
     * @param int $cidr
     * @return string
     */
    static public function Cidr2Netmask($cidr)
    {
        return long2ip(self::_Cidr2Netmask($cidr));
    }
    /**
     * Get Subnet from IP and Netmask
     *
     * @param string $ip
     * @param string $netmask
     * @return string
     */
    static public function Subnet($ip, $netmask)
    {
        if (self::isIPv4($ip)) {
            $ip = ip2long($ip);
        }
        if (self::isIPv4($netmask)) {
            $netmask = ip2long($netmask);
        }
        return long2ip(self::_Subnet($ip, $netmask));
    }
    /**
     * Get Subnet from IP and Netmask
     *
     * @param long $ip
     * @param long $netmask
     * @return long
     */

    static protected function _Subnet($ip, $netmask)
    {
        return $ip & $netmask;
    }
}

# EOF
