<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Configuration;

/**
 * Interface SettingsInterface
 * @package GoGentoOSS\SAMLBase
 */
interface SettingsInterface
{
    /**
     * @param $value
     * @return mixed
     */
    public function getValue($value);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setValue($key, $value);

    /**
     * @return mixed
     */
    public function getValues();

    /**
     * @param array $values
     * @return mixed
     */
    public function setValues($values = array());
}