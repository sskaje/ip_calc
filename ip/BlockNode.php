<?php

namespace sskaje\ip;
/**
 * 网段节点
 *
 * @package sskaje\ip
 */
class BlockNode
{
    /**
     * 开始 IP
     *
     * @var int
     */
    protected $begin;

    /**
     * 结束IP
     *
     * @var int
     */
    protected $end;

    /**
     * BlockNode constructor.
     *
     * @param int $begin
     * @param int $end
     */
    public function __construct($begin, $end)
    {
        $this->begin = (int) $begin;
        $this->end   = (int) $end;

        if ($this->begin > $this->end) {
            list($this->begin, $this->end) = [$this->end, $this->begin];
        }
    }

    public function __get($name)
    {
        return in_array($name, ['begin', 'end']) ? $this->$name : null;
    }

    public function __set($name, $val)
    {
        # pass
    }

    public function __isset($name)
    {
        return $this->begin !== null;
    }

    public function __unset($name)
    {
        # pass
    }
}

# EOF
