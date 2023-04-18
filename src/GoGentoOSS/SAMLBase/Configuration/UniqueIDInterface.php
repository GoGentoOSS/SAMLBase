<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Interface UniqueID
 * @package GoGentoOSS\SAMLBase\Configuration
 */
interface UniqueIDInterface
{
    /**
     * @param string $prefix
     * @return string
     */
    public function generate($prefix = 'SAMLBase');

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix);

    /**
     * @return string
     */
    public function __toString();
}
