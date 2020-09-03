<?php

namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Class UniqueID
 * @package GoGentoOSS\SAMLBase\Configuration
 */
class UniqueID implements UniqueIDInterface
{
    /**
     * @var string
     */
    protected $prefix = 'SAMLBase';

    /**
     * @param string $prefix
     * @param $algorithm
     * @return string
     */
    public function generate($prefix = 'SAMLBase', $algorithm = 'sha256')
    {
        $this->setPrefix($prefix);

        return $this->prefix . hash($algorithm, uniqid(mt_rand(), true));
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->uniqueID;
    }
}
