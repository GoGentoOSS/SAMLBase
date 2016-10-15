<?php

namespace Wizkunde\SAMLBase\Response;

use Wizkunde\SAMLBase\Security\Encryption;
use Wizkunde\SAMLBase\Security\Signature;

class AuthnResponse
{
    /**
     * @var null
     */
    protected $signatureService = null;
    /**
     * @var null
     */
    protected $encryptionService = null;

    /**
     * @return null
     */
    public function getSignatureService()
    {
        return $this->signatureService;
    }

    /**
     * @param null $signatureService
     */
    public function setSignatureService(Signature $signatureInterface)
    {
        $this->signatureService = $signatureInterface;
    }

    /**
     * @return null
     */
    public function getEncryptionService()
    {
        return $this->encryptionService;
    }

    /**
     * @param null $encryptionService
     */
    public function setEncryptionService(Encryption $encryptionService)
    {
        $this->encryptionService = $encryptionService;
    }

    /**
     * Handle the response string that we receive
     *
     * @param $response
     */
    public function decode($response)
    {
        $responseData = base64_decode($response);

        $inflatedResponseData = @gzinflate($responseData);
	if($inflatedResponseData != false) {
	    $responseData = $inflatedResponseData;
        }

        $decryptedDocument = $this->getEncryptionService()->decrypt($responseData);

        if ($this->getSignatureService()->verifyDOMDocument($decryptedDocument) == false) {
            throw new \Exception('Could not verify Signature');
        }

        return $decryptedDocument->version;
    }
}
