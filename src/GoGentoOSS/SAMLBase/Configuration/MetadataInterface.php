<?php

namespace GoGentoOSS\SAMLBase\Configuration;

use GoGentoOSS\SAMLBase\Configuration;

interface MetadataInterface
{
    /**
     * @return string with xml data
     */
    public function getMetadata();
}
