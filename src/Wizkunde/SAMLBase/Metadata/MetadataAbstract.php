<?php

namespace Wizkunde\SAMLBase\Metadata;

/**
 * This class automatically maps IDP metadata to the designated values
 *
 * Class Metadata
 * @package Wizkunde\SAMLBase\Metadata
 */
abstract class MetadataAbstract
{
    /**
     * Namespace for the metadata
     *
     * @var string
     */
    protected $metadataNamespace = 'md';

    /**
     * Contains the metadata for every mapping needed in subclasses of this class
     *
     * @var array
     */
    protected $xpathMappings = array();

    /**
     * @var \DOMDocument that contains the unmapped metadata
     */
    protected $document = null;

    /**
     * Current Metadata from source
     *
     * @var array
     */
    protected $metadata = array();

    /**
     * Sign methods
     */
    const SIGN_SHA1 = 'http://www.w3.org/2000/09/xmldsig#sha1';
    const SIGN_SHA256 = 'http://www.w3.org/2000/09/xmldsig#sha256';

    protected $xpathMetadataNamespace = 'urn:oasis:names:tc:SAML:2.0:metadata';
    protected $xpathSignatureNamespace = 'http://www.w3.org/2000/09/xmldsig#';

    /**
     * Initialize and possibly automatically map the metadata
     *
     * @param string $metadata
     */
    public function __construct($metadata = '')
    {
        if ($metadata != '') {
            $this->mapMetadata($metadata);
        }
    }

    /**
     * @return string
     */
    public function getMetadataNamespace()
    {
        return ($this->metadataNamespace != '') ? $this->metadataNamespace . ':' : '';
    }

    /**
     * @param string $metadataNamespace
     */
    public function setMetadataNamespace($metadataNamespace = 'md')
    {
        $this->metadataNamespace = $metadataNamespace;
    }

    /**
     * Get the mapped metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param $query
     * @return mixed
     */
    protected function replaceNamespaces($query)
    {
        return str_replace('{mdNs}', $this->getMetadataNamespace(), $query);
    }

    /**
     * Automatically determine the namespaces used in the metadata for metadata
     *
     * Sometimes it is added as md: and sometimes its not set at all
     */
    protected function setNamespaceFromMetadata()
    {
        $nameSpaces = $this->metadata->getNamespaces(true);

        $this->metadata->registerXPathNamespace('md', $this->xpathMetadataNamespace);

        foreach($nameSpaces as $namespace => $definition) {
            if($definition == $this->xpathMetadataNamespace) {
                //$this->setMetadataNamespace($namespace);
            }
        }
    }

    /**
     * Map a new metadata xml document
     *
     * @param $metadata
     */
    public function mapMetadata($metadata)
    {
        $this->metadata = new \SimpleXMLElement($metadata);

        $this->metadata->registerXPathNamespace('md', $this->xpathMetadataNamespace);

        $mappings = array();
        foreach ($this->xpathMappings as $namespace => $xpathMappings) {
            foreach ($xpathMappings as $query => $mapping) {
                $data = current($this->metadata->xpath($this->replaceNamespaces($query)));

                if (is_array($mapping)) {
                    if (isset($mapping['Attributes'])) {
                        foreach ($mapping['Attributes'] as $attribute => $mappedAttribute) {
                            if (is_object($data)) {
                                $mappings[$namespace][$mappedAttribute] = (string)$data->attributes()->$attribute;
                            }
                        }
                    }

                    if (array_key_exists('Value', $mapping)) {
                        if (is_object($data)) {
                            $mappings[$namespace][$mapping['Value']] = (string)$data;
                        }
                    }
                } else {
                    if (is_object($data)) {
                        $mappings[$namespace][$mappedAttribute] = (string)$data;
                    }
                }
            }
        }

        if(count($mappings) == 0) {
            throw new \Exception('Unable to map IDP metadata');
        }

        return $mappings;
    }
}