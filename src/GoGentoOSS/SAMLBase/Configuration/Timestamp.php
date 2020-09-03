<?php

namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Class Timestamp
 * @package GoGentoOSS\SAMLBase\Configuration
 */
class Timestamp implements TimestampInterface
{
    const SECONDS_MINUTE = 60;
    const SECONDS_HOUR = 3600;
    const SECONDS_DAY = 86400;
    const SECONDS_WEEK = 604800;

    /**
     * @var string
     */
    protected $timestamp = '';

    /**
     * @todo do this with the intl extension possibly?
     *
     * @return string Get a valid timestamp
     */
    public function generate($time = null)
    {
        if ($time === null) {
            $time = time();
        }

        $this->timestamp = new \DateTime();
        $this->timestamp->setTimestamp($time);

        $UTC = new \DateTimeZone("UTC");
        $this->timestamp->setTimezone($UTC);

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->timestamp;
    }

    /**
     * @param int $seconds
     */
    public function add($seconds = 0)
    {
        $dateInterval = new \DateInterval('PT' . $seconds . 'S');
        $this->timestamp->add($dateInterval);
    }

    /**
     * @return mixed
     */
    public function toTimestamp()
    {
        return $this->timestamp->getTimestamp();
    }

    /**
     * @param string $dateFormat
     * @return mixed
     */
    public function toFormat($dateFormat = 'Y-m-d\TH:i:s\Z')
    {
        return $this->timestamp->format($dateFormat);
    }
}
