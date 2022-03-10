<?php

namespace sskaje\ip;
/**
 * 子网
 */
class Subnet
{
    /**
     * 子网号
     * @var string IP
     */
    protected $subnet;
    /**
     * CIDR
     *
     * @var int CIDR
     */
    protected $cidr;

    public function __construct($subnet, $cidr)
    {
        $this->subnet = $subnet;
        $this->cidr = $cidr;
    }

    public function __toString()
    {
        return $this->subnet . '/' . $this->cidr;
    }
}