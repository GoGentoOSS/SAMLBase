<?php
/**
 * @author Ron van der Molen <ron@gogento.com>
 */
namespace GoGentoOSS\SAMLBase\Configuration;

class Claim implements ClaimInterface
{
    protected $claimKey = '';
    protected $claimValue = '';

    /**
     * Set the claim key that we retreived from the AuthRequest
     *
     * @param string $claimKey
     */
    public function setKey($claimKey)
    {
        $this->claimKey = $claimKey;
    }

    /**
     * Get the claim key that we retreived from the AuthRequest
     *
     * @return string
     */
    public function getKey()
    {
        return $this->claimKey;
    }

    /**
     * Set the claim value that we retreived from the AuthRequest
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->claimValue;
    }

    /**
     * Set the claim value that we retreived from the AuthRequest
     *
     * @param string $claimValue
     */
    public function setValue($claimValue)
    {
        $this->claimValue = $claimValue;
    }
}
