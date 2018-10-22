<?php

namespace DGtal\Tokko;

use DGtal\Tokko\Adapter\ConnectorInterface;
use GuzzleHttp\Exception\ClientException;

/**
 * Class TokkoManager
 */
class TokkoManager
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var ConnectorInterface
     */
    protected $client;

    /**
     * Tokko constructor.
     * @param array $config
     * @param ConnectorInterface $client
     */
    public function __construct(array $config, ConnectorInterface $client)
    {
        $this->config = $config;
        $this->client = $client;
    }

    /**
     * @return ConnectorInterface
     */
    public function connection()
    {
        return $this->client->connect($this->getConfig());
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function execute(string $method, $parameters = [])
    {
        $parameters = array_merge_recursive($parameters, $this->connection()->getConfig());

        try {
            $response = $this->connection()->get($method, $parameters);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $ex) {
            $response = json_decode($ex->getResponse()->getBody()->getContents(), true);
            return $response;
        }
    }

    /**
     * Get the config array.
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
