<?php

namespace DGtal\Tokko\Adapter;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;

/**
 * Class GuzzleHttpAdapter
 */
class GuzzleHttpAdapter implements ConnectorInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     * @return mixed
     */
    public function connect(array $config)
    {
        $this->config = $this->getConfig($config);

        return $this->getAdapter();
    }

    /**
     * @param $config
     * @return array|null
     * @throws \InvalidArgumentException
     */
    private function getConfig($config)
    {
        if (!array_key_exists('key', $config) || empty($config['key'])) {
            throw new InvalidArgumentException('The guzzlehttp connector requires configuration.');
        }

        return $config;

        throw new InvalidArgumentException('Unsupported auth type');
    }

    /**
     * @return Client
     */
    private function getAdapter()
    {
        return new Client(
            [
                'base_uri' => $this->config['apiurl'],
                'timeout' => 30,
                'debug' => $this->config['debug'] ?: false,
                'query' => [
                    'lang' => $this->config['lang'],
                    'key' => $this->config['key'],
                    'format' => 'json',
                ],
                'headers' => [
                    'User-Agent' => 'Tokko API Interface',
                ]
            ]
        );
    }
}
