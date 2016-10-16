<?php

namespace Wizkunde\SAMLBase\Metadata;

use GuzzleHttp\Client;

class ResolveService
{
    /**
     * Initialize the resolver service
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * Resolve the metadata
     */
    public function resolve(MetadataAbstract $metadataClass, $metadataUrl)
    {
        $response = $this->getClient()->get($metadataUrl)->send();

        $xmlDocument = (string) $response->getBody();

        return $metadataClass->mapMetadata($xmlDocument);
    }

    /**
     * Set the client for fetching the metadata
     *
     * @param Client $client
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the client for fetching the metadata
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }
}