<?php

namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Interface Timestamp
 * @package GoGentoOSS\SAMLBase\Configuration
 */
interface TimestampInterface
{
    /**
     * @todo do this with the intl extension possibly?
     *
     * @return string Get a valid timestamp
     */
    public function generate($time = null);

    /**
     * @return string
     */
    public function getDate();

    /**
     * @param int $seconds
     */
    public function add($seconds = 0);

    /**
     * @return mixed
     */
    public function toTimestamp();

    /**
     * @param string $dateFormat
     * @return mixed
     */
    public function toFormat($dateFormat = 'Y-m-d\TH:i:s\Z');
}
