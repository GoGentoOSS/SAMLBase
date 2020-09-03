<?php

namespace GoGentoOSS\SAMLBase\Configuration;

use GoGentoOSS\SAMLBase\Configuration\SettingsInterface;

/**
 * Class Settings
 * @package GoGentoOSS\SAMLBase\Configuration
 */
class Settings implements SettingsInterface
{
    /**
     * Add default values that will not error in case you request them
     *
     * @var array
     */
    protected $values = array(
        'OptionalURLParameters'   => array()
    );

    /**
     * @param $key
     * @return mixed
     * @throws \Exception
     */
    public function getValue($key)
    {
        if(isset($this->values[$key])) {
            return $this->values[$key];
        }

        throw new \Exception('Cannot vind settings key ' . $key . ' in SAML2 IDP Settings');
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setValue($key, $value)
    {
        $this->values[$key] = $value;

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function setValues($values = array())
    {
        if(is_array($values) && count($values) > 0) {
            $this->values = array_merge($this->values, $values);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}