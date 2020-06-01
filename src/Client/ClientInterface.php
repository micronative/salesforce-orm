<?php

namespace Salesforce\Client;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Salesforce\Cache\CacheEngineInterface;
use Salesforce\Client\Exception\ClientException;
use Salesforce\Job\Job;
use Salesforce\Restforce\ExtendedRestforce;

/**
 * interface Client
 *
 * @package Salesforce\Client
 */
interface ClientInterface
{
    
    /**
     * @param string $sObject object name
     * @param array $data associative array to send to salesforce.
     * @return string
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function createObject(string $sObject = null, array $data = null): string;
    
    /**
     * @param string|null $uri
     * @param string|null $object
     * @param string|null $action
     * @param array $additionalData
     * @return mixed
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function createJob(string $uri = null, string $object = null, string $action = null,
                              array $additionalData = []);
    
    /**
     * @param string|null $uri
     * @param string $csvData
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function batchJob(string $uri = null, string $csvData = null);
    
    /**
     * @param string|null $uri
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function closeJob(string $uri = null);
    
    /**
     * @param string|null $uri
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function getJob(string $uri = null);
    
    /**
     * @param string $sObject object
     * @param string $sObjectId id to update
     * @param array $data data in associate array format
     * @return bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function updateObject(string $sObject = null, string $sObjectId = null, array $data = null);
    
    /**
     * @param string $sObject salesforce object name
     * @param string $sObjectId id
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findObject(string $sObject = null, string $sObjectId = null);
    
    /**
     * @param string $query query string
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function query(string $query = null);
    
    /**
     * @param string $uri
     * @param array $data
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function apexPostJson(string $uri = null, array $data = null);
    
    /**
     * @param string $uri
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function apexGet(string $uri = null);
    
    /**
     * @return \Salesforce\Restforce\ExtendedRestforce
     */
    public function getRestforce(): ExtendedRestforce;
    
    /**
     * @param \Salesforce\Restforce\ExtendedRestforce $restforce
     * @return \Salesforce\Client\ClientInterface
     */
    public function setRestforce(ExtendedRestforce $restforce = null): ClientInterface;
    
    /**
     * @return \Salesforce\Client\ConfigInterface
     */
    public function getConfig(): ConfigInterface;
    
    /**
     * @param \Salesforce\Client\ConfigInterface $config
     * @return \Salesforce\Client\ClientInterface
     */
    public function setConfig(ConfigInterface $config = null): ClientInterface;
    
    /**
     * @return CacheEngineInterface
     */
    public function getCache(): CacheEngineInterface;
    
    /**
     * @param \Salesforce\Cache\CacheEngineInterface $cache
     * @return \Salesforce\Client\ClientInterface
     */
    public function setCache(CacheEngineInterface $cache = null): ClientInterface;
    
    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface;
    
    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @return \Salesforce\Client\ClientInterface
     */
    public function setLogger(LoggerInterface $logger = null): ClientInterface;
}
