<?php

namespace sskaje\ip;

/**
 * IP 数学计算器
 *
 * @package sskaje\ip
 */
class BitCalculator
{
    /**
     * CIDR to Netmask
     *
     * @param int $cidr
     * @return int
     * @throws \Exception
     */
    static public function CIDR2Netmask($cidr)
    {
        Utils::checkCIDR($cidr);

        return (pow(2, $cidr) - 1) << (32 - $cidr);
    }

    /**
     * Netmask to CIDR
     *
     * @param int $netmask
     * @return int
     * @throws \Exception
     */
    static public function Netmask2CIDR($netmask)
    {
        $base = 0xFFFFFFFF; # 255.255.255.255
        $mn = log(($netmask ^ $base)+1,2);

        if (!Utils::isInt32($mn)) {
            throw new \Exception('无法转换成CIDR');
        }

        return 32-$mn;
    }

    /**
     * Get Subnet from IP and Netmask
     *
     * @param int $ip
     * @param int $netmask
     * @return int
     */
    static public function Subnet($ip, $netmask)
    {
        return $ip & $netmask;
    }

    /**
     * Get Broadcast from IP and Netmask
     *
     * @param int $ip
     * @param int $netmask
     * @return int
     */
    static public function Broadcast($ip, $netmask)
    {
        return $ip | ((~$netmask) & 0xffffffff);
    }

    /**
     * Calculate subnet, netmask, broadcast addresses from IP and CIDR
     *
     * @param int    $ip
     * @param int    $cidr
     * @return bool|array   false on invalid ip or cidr, array(long subnet, long broadcast, long netmask)
     */
    static public function IPCIDR2All($ip, $cidr)
    {
        $netmask   = self::CIDR2Netmask($cidr);
        $subnet    = self::Subnet($ip, $netmask);
        $broadcast = self::Broadcast($subnet, $netmask);

        return array($subnet, $broadcast, $netmask);
    }
}

# EOF