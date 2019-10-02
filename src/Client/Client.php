<?php
namespace Salesforce\Client;

use Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Salesforce\Cache\CacheEngineInterface;
use Salesforce\Client\Exception\ClientException;
use Salesforce\Job\Constants\JobConstants;
use Salesforce\Restforce\ExtendedRestforce;

/**
 * Class Client
 *
 * @package Salesforce\Client
 */
class Client
{
    /** @var ExtendedRestforce */
    protected $restforce;

    /** @var Config */
    protected $config;

    /** @var CacheEngineInterface */
    protected $cache;

    /** @var LoggerInterface */
    protected $logger;

    const MSG_DEBUG_CREATE_START = 'Start creating object in Salesforce. Type: %s. Data: %s';
    const MSG_DEBUG_CREATE_BULK_START = 'Start creating object in Salesforce. Type: %s. Action: %s';
    const MSG_DEBUG_ADD_BATCHES_TO_BULK_START = 'Start adding batches to bulk in Salesforce. JobId: %s. Data: %s';
    const MSG_DEBUG_CLOSE_BULK_START = 'Start closing bulk Job in Salesforce. JobId: %s.';
    const MSG_DEBUG_CREATE_FINISH = 'Finish creating object in Salesforce.';
    const MSG_DEBUG_UPDATE_START = 'Start updating object in Salesforce. Type: %s. Id: %s .Data: %s';
    const MSG_DEBUG_UPDATE_FINISH = 'Finish updating object in Salesforce.';
    const MSG_DEBUG_FIND_START = 'Start finding object in Salesforce. Type: %s. Id: %s';
    const MSG_DEBUG_FIND_FINISH = 'Finish finding object in Salesforce.';
    const MSG_DEBUG_QUERY_START = 'Start querying object in Salesforce. Query: %s';
    const MSG_DEBUG_QUERY_FINISH = 'Finish querying object in Salesforce.';
    const MSG_DEBUG_APEX_API_START = 'Star Apex api request. Uri: %s';
    const MSG_DEBUG_APEX_API_FINISH = 'Finish Apex api request.';

    /**
     * Client constructor.
     *
     * @param \Salesforce\Client\Config|null $config config
     * @param \Salesforce\Cache\CacheEngineInterface|null $cache
     * @param \Psr\Log\LoggerInterface|null $logger
     * @throws \EventFarm\Restforce\RestforceException
     */
    public function __construct(Config $config = null, CacheEngineInterface $cache = null, LoggerInterface $logger = null)
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->restforce = new ExtendedRestforce(
            $this->config->getClientId(),
            $this->config->getClientSecret(),
            $this->config->getPath(),
            null,
            $this->config->getUsername(),
            $this->config->getPassword(),
            $this->config->getApiVersion(),
            $this->config->getApexEndPoint()
        );
    }

    /**
     * @param string $sObject object name
     * @param array $data associative array to send to salesforce.
     * @return string
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function createObject(string $sObject = null, array $data = null)
    {
        if (empty($sObject)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_CREATE_START, $sObject, json_encode($data)));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->create($sObject, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_CREATE_OBJECT . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_CREATE_FINISH);
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string|null $object
     * @param string|null $action
     * @param array $additionalData
     * @return mixed
     * @throws ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function createBulkJob(string $object = null, string $action = null, array $additionalData = [])
    {
        if (empty($object)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if (empty($action)) {
            throw new ClientException(ClientException::MSG_ACTION_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_CREATE_BULK_START, $object, $action));
        }

        $data = [
            'operation' => $action,
            'object' => $object,
            'contentType' => 'CSV',
        ];

        if (!empty($additionalData)) {
            $data = array_merge($data, $additionalData);
        }

        $response = $this->restforce->createBulkJob(JobConstants::JOB_INGEST_ENDPOINT, $data);

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string|null $jobId
     * @param string $csvData
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function addToBulkJobBatches(string $jobId = null, string $csvData = null)
    {
        if (empty($jobId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_ADD_BATCHES_TO_BULK_START, $jobId, $csvData));
        }

        $response = $this->restforce->addToBulkJobBatches(JobConstants::JOB_INGEST_ENDPOINT . $jobId . '/' . JobConstants::JOB_ADD_BATCHES_ENDPOINT, $csvData);

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string|null $jobId
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws ClientException
     */
    public function closeBulkJob(string $jobId = null)
    {
        if (empty($jobId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_CLOSE_BULK_START, $jobId));
        }

        $response = $this->restforce->closeBulkJob(JobConstants::JOB_INGEST_ENDPOINT . $jobId, [
            'state' => JobConstants::STATE_UPLOAD_COMPLETE
        ]);

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string|null $uri
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function bulkJobGet(string $uri = null)
    {
        if (empty($uri)) {
            throw new ClientException(ClientException::MSG_APEX_API_URI_MISSING);
        }

        $response = $this->restforce->bulkJobGet($uri);

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string $sObject object
     * @param string $sObjectId id to update
     * @param array $data data in associate array format
     * @return bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function updateObject(string $sObject = null, string $sObjectId = null, array $data = null)
    {
        if (empty($sObject)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_UPDATE_START, $sObject, $sObjectId, json_encode($data)));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->update($sObject, $sObjectId, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_UPDATE_OBJECT . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_UPDATE_FINISH);
        }

        $result = new Result($response);

        return $result->get();
    }

    /**
     * @param string $sObject salesforce object name
     * @param string $sObjectId id
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function findObject(string $sObject = null, string $sObjectId = null)
    {
        if (empty($sObject)) {
            throw new ClientException(ClientException::MSG_OBJECT_TYPE_MISSING);
        }

        if (empty($sObjectId)) {
            throw new ClientException(ClientException::MSG_OBJECT_ID_MISSING);
        }

        if ($this->cache) {
            $cache = $this->cache->getCache($this->cache->createKey($sObject . $sObjectId));
            if ($cache !== null) {
                return $cache;
            }
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_FIND_START, $sObject, $sObjectId));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->find($sObject, $sObjectId);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_FIND_OBJECT . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_FIND_FINISH);
        }

        $result = (new Result($response))->get();

        if ($this->cache) {
            $this->cache->writeCache($this->cache->createKey($sObject . $sObjectId), $result);
        }

        return $result;
    }

    /**
     * @param string $query query string
     * @return array|bool
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     */
    public function query(string $query = null)
    {
        if (empty($query)) {
            throw new ClientException(ClientException::MSG_QUERY_MISSING);
        }

        if ($this->cache) {
            $cache = $this->cache->getCache($this->cache->createKey($query));
            if ($cache !== null) {
                return $cache;
            }
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_QUERY_START, $query));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->query($query);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_FAILED_TO_FIND_OBJECT . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_QUERY_FINISH);
        }

        $result = (new Result($response))->get();

        if ($this->cache) {
            $this->cache->writeCache($this->cache->createKey($query), $result);
        }

        return $result;
    }

    /**
     * @param string $uri
     * @param array $data
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function apexPostJson(string $uri = null, array $data = null)
    {
        if (empty($uri)) {
            throw new ClientException(ClientException::MSG_APEX_API_URI_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_APEX_API_START, $uri));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->apexPostJson($uri, $data);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_APEX_API_FAILED . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_APEX_API_FINISH);
        }

        $result = (new Result($response))->get();

        return $result;
    }

    /**
     * @param string $uri
     * @return mixed
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\Client\Exception\ClientException
     */
    public function apexGet(string $uri = null)
    {
        if (empty($uri)) {
            throw new ClientException(ClientException::MSG_APEX_API_URI_MISSING);
        }

        if ($this->logger) {
            $this->logger->debug(sprintf(self::MSG_DEBUG_APEX_API_START, $uri));
        }

        try {
            /* @var ResponseInterface $response */
            $response = $this->restforce->apexGet($uri);
        } catch (Exception $e) {
            throw new ClientException(ClientException::MSG_APEX_API_FAILED . $e->getMessage());
        }

        if ($this->logger) {
            $this->logger->debug(self::MSG_DEBUG_APEX_API_FINISH);
        }

        $result = (new Result($response))->get();

        return $result;
    }

    /**
     * @return \Salesforce\Restforce\ExtendedRestforce
     */
    public function getRestforce()
    {
        return $this->restforce;
    }

    /**
     * @param \Salesforce\Restforce\ExtendedRestforce $restforce
     * @return \Salesforce\Client\Client
     */
    public function setRestforce(ExtendedRestforce $restforce = null)
    {
        $this->restforce = $restforce;

        return $this;
    }

    /**
     * @return \Salesforce\Client\Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param \Salesforce\Client\Config $config
     * @return \Salesforce\Client\Client
     */
    public function setConfig(Config $config = null)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return CacheEngineInterface
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param \Salesforce\Cache\CacheEngineInterface $cache
     * @return \Salesforce\Client\Client
     */
    public function setCache(CacheEngineInterface $cache = null)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     * @return \Salesforce\Client\Client
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;

        return $this;
    }
}
