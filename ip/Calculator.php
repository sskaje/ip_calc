<?php

namespace sskaje\ip;

/**
 * IP Calculator
 *
 * @author sskaje (https://sskaje.me/ sskaje@gmail.com)
 */
class Calculator
{
    /**
     * Get subnets from begin ip and end ip
     *
     * @param string $begin
     * @param string $end
     * @return bool|array     false on invalid ip, array(array(subnet=>, cidr=>), ...)
     */
    static public function Range2Blocks($begin, $end)
    {
        Utils::checkIPv4($begin);
        Utils::checkIPv4($end);

        $begin = Utils::ip2long($begin);
        $end   = Utils::ip2long($end);

        if ($end < $begin) {
            # swap if necessary
            list($begin, $end) = [$end, $begin];
        }

        $lsb = Utils::GetLSBPos($begin);
        $result = array();
        self::GetAllBlocks($begin, $end, $lsb, $result);
        return $result;
    }

    /**
     * Get all subnets from begin ip and end ip and LSB pos
     *
     * @param int $begin
     * @param int $end
     * @param int $pos  
     * @param array & $ret
     * @return bool
     */
    static protected function GetAllBlocks($begin, $end, $pos, &$ret=array())
    {
        if ($pos > 32 || $pos < 0) {
            return false;
        }
        $cidr = 32 - $pos;
        list($subnet, $broadcast, ) = BitCalculator::IPCIDR2All($begin, $cidr);

        if ($end > $broadcast) {
            $ret[] = array('subnet'=>Utils::long2ip($subnet), 'cidr'=>$cidr);
            # broadcast + 1, next loop
            $begin = $broadcast + 1;
            $lsb = Utils::GetLSBPos($begin);
            self::GetAllBlocks($begin, $end, $lsb, $ret);
        } else if ($end == $broadcast) {
            $ret[] = array('subnet'=>Utils::long2ip($subnet), 'cidr'=>$cidr);
        } else {
            self::GetAllBlocks($begin, $end, $pos - 1, $ret);
        }
        return true;
    }
    
    /**
     * Calculate subnet, netmask, broadcast addresses from IP and CIDR
     *
     * @param string $ip
     * @param int    $cidr
     * @param bool   $return_ip  return IPv4 or long
     * @return bool|array   false on invalid ip or cidr, array(ipv4 subnet, ipv4 broadcast, ipv4 netmask, ipv4 wildcard mask)
     */
    static public function ipCidr2Subnet($ip, $cidr, $return_ip=true)
    {
        Utils::checkIPv4($ip);
        Utils::checkCIDR($cidr);

        $ip = Utils::ip2long($ip);

        list($subnet, $broadcast, $netmask, $wildcardmask) = BitCalculator::IPCIDR2All($ip, $cidr);

        if ($return_ip) {
            return array(Utils::long2ip($subnet), Utils::long2ip($broadcast), Utils::long2ip($netmask), Utils::long2ip($wildcardmask));
        } else {
            return array($subnet, $broadcast, $netmask, $wildcardmask);
        }
    }

    /**
     * Convert CIDR to Netmask
     *
     * @param int $cidr
     * @return string
     */
    static public function Cidr2Netmask($cidr)
    {
        return Utils::long2ip(BitCalculator::CIDR2Netmask($cidr));
    }

    /**
     * Convert Netmask to CIDR
     *
     * @param string $netmask
     * @return int
     * @throws \Exception
     */
    static public function Netmask2Cidr($netmask)
    {
        Utils::checkIPv4($netmask);

        $netmask = Utils::ip2long($netmask);

        return BitCalculator::Netmask2CIDR($netmask);
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
        Utils::checkIPv4($ip);
        Utils::checkIPv4($netmask);

        $ip = Utils::ip2long($ip);
        $netmask = Utils::ip2long($netmask);

        return Utils::long2ip(BitCalculator::Subnet($ip, $netmask));
    }
}

# EOF
