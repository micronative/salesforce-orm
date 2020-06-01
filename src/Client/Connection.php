<?php
namespace Salesforce\Client;

use Psr\Log\LoggerInterface;
use Salesforce\Cache\CacheEngineFactory;

class Connection
{
    /** @var ConfigInterface */
    protected $config;
    /** @var ClientInterface */
    protected $client;

    /**
     * Connection constructor.
     *
     * @param array $clientConfig
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     * ]
     * @param array $cacheConfig
     * @param \Psr\Log\LoggerInterface|null $logger
     * @throws Exception\ConfigException
     * @throws \EventFarm\Restforce\RestforceException
     * @throws \Salesforce\Cache\Exception\CacheException
     */
    public function __construct($clientConfig = [], $cacheConfig = [], LoggerInterface $logger = null)
    {
        $cache = empty($cacheConfig) ? null : CacheEngineFactory::createCacheEngine($cacheConfig);
        $this->config = new Config($clientConfig);
        $this->client = new Client($this->config, $cache, $logger);
    }

    /**
     * @return \Salesforce\Client\ConfigInterface
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Salesforce\Client\ConfigInterface $config
     * @return \Salesforce\Client\Connection
     */
    public function setConfig(ConfigInterface $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Salesforce\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \Salesforce\Client\ClientInterface $client
     * @return \Salesforce\Client\Connection
     */
    public function setClient(ClientInterface $client = null)
    {
        $this->client = $client;

        return $this;
    }

}
