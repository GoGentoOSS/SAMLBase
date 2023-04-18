<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Configuration;

interface MetadataInterface
{
    /**
     * @return string with xml data
     */
    public function getMetadata();
}
