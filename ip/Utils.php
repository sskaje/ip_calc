<?php
namespace sskaje\ip;

class Utils
{
    /**
     * Convert IP to long
     *
     * @param string $ip
     * @return mixed
     */
    static public function ip2long($ip)
    {
        return sprintf('%u', ip2long($ip));
    }

    /**
     * Convert long to IP
     *
     * @param int $long
     * @return mixed
     */
    static public function long2ip($long)
    {
        return long2ip($long);
    }

    /**
     * Check if IP is valid IPv4 format
     *
     * @param string $ip
     * @return bool
     */
    static public function isIPv4($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * Check if IP is valid IPv4 format
     *
     * @param string $ip
     * @throws \Exception
     */
    static public function checkIPv4($ip)
    {
        if (!self::isIPv4($ip)) {
            throw new \Exception("{$ip} 不是IPv4");
        }
    }

    /**
     * 检查是否是合法的整数
     *
     * @param int $int
     * @return mixed
     */
    static public function isInt($int)
    {
        return ctype_digit(strval($int));
    }

    /**
     * 检查是否是合法的32位整数
     *
     * @param int $int
     * @return bool
     */
    static public function isInt32($int)
    {
        return self::isInt($int) && $int >= 0x00000000 && $int <= 0xFFFFFFFF;
    }

    /**
     * 检查是否是合法的CIDR
     *
     * @param int $cidr
     * @throws \Exception
     */
    static public function checkCIDR($cidr)
    {
        if (self::isInt32($cidr) && $cidr >= 0 && $cidr <= 32) {
            # pass
        } else {
            throw new \Exception("{$cidr} 不是合法的CIDR");
        }
    }


    /**
     * Find the right most bit which is 1 from a int
     *
     * @param int $int
     * @return int
     */
    static public function GetLSBPos($int)
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

}

# EOF