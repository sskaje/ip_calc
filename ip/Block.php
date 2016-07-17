<?php
namespace sskaje\ip;
/**
 * IP 段管理
 *
 * @package sskaje\ip
 */
class Block
{
    /**
     * 从 IP / Netmask 创建网段
     *
     * @param string $ip
     * @param string $mask
     * @return \sskaje\ip\BlockNode
     */
    static public function CreateFromIPMask($ip, $mask)
    {
        return self::CreateFromIPCIDR($ip, Calculator::Netmask2Cidr($mask));
    }

    /**
     * 从 IP / CIDR 创建网段
     *
     * @param string $ip
     * @param string $cidr
     * @return \sskaje\ip\BlockNode
     */
    static public function CreateFromIPCIDR($ip, $cidr='')
    {
        if ($cidr == '') {
            $cidr = 32;
            if (strpos($ip, '/')){
                list($ip, $cidr) = explode('/', $ip, 2);
            }
        }
        list($subnet, $broadcast, ) = Calculator::ipCidr2Subnet($ip, $cidr, false);

        return self::CreateFromInt32Range($subnet, $broadcast);
    }

    /**
     * 从 IP 区间创建网段
     *
     * @param string $ip_begin
     * @param string $ip_end
     * @return \sskaje\ip\BlockNode
     */
    static public function CreateFromIPRange($ip_begin, $ip_end)
    {
        return new BlockNode(Utils::ip2long($ip_begin), Utils::ip2long($ip_end));
    }

    /**
     * 从整数区间创建网段
     *
     * @param int $int_begin
     * @param int $int_end
     * @return \sskaje\ip\BlockNode
     */
    static public function CreateFromInt32Range($int_begin, $int_end)
    {
        return new BlockNode($int_begin, $int_end);
    }
}

# EOF