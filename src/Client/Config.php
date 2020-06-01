<?php

namespace Salesforce\Client;

use Salesforce\Client\Exception\ConfigException;

class Config implements ConfigInterface
{
    protected $clientId;
    protected $clientSecret;
    protected $path;
    protected $username;
    protected $password;
    protected $apiVersion;
    protected $apexEndPoint;
    
    const REQUIRED_CONFIGURATION_DATA = ['clientId', 'clientSecret', 'path', 'username', 'password', 'apiVersion',
                                         'apexEndPoint'];
    
    /**
     * Config constructor.
     *
     * @param array $config config
     * [
     *  'clientId' =>
     *  'clientSecret' =>
     *  'path' =>
     *  'username' =>
     *  'password' =>
     *  'apiVersion' =>
     *  'apexEndPoint' =>
     * ]
     * @throws \Salesforce\Client\Exception\ConfigException
     */
    public function __construct(array $config = null)
    {
        if (!isset($config['clientId']) || !isset($config['clientSecret']) || !isset($config['path']) ||
            !isset($config['username']) || !isset($config['password']) || !isset($config['apiVersion']) ||
            !isset($config['apexEndPoint'])) {
            throw new ConfigException(ConfigException::MSG_MISSING_SALESFORCE_CONFIG .
                                      implode(",", self::REQUIRED_CONFIGURATION_DATA));
        }
        $this->clientId     = $config['clientId'];
        $this->clientSecret = $config['clientSecret'];
        $this->path         = $config['path'];
        $this->username     = $config['username'];
        $this->password     = $config['password'];
        $this->apiVersion   = $config['apiVersion'];
        $this->apexEndPoint = $config['apexEndPoint'];
    }
    
    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }
    
    /**
     * @param string $clientId client id
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setClientId(string $clientId = null): ConfigInterface
    {
        $this->clientId = $clientId;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }
    
    /**
     * @param string $clientSecret secret
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setClientSecret(string $clientSecret = null): ConfigInterface
    {
        $this->clientSecret = $clientSecret;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * @param string $path path
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setPath(string $path = null): ConfigInterface
    {
        $this->path = $path;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    
    /**
     * @param string $username username
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setUsername(string $username = null): ConfigInterface
    {
        $this->username = $username;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * @param string $password password
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setPassword(string $password = null): ConfigInterface
    {
        $this->password = $password;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }
    
    /**
     * @param string $apiVersion api version
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setApiVersion(string $apiVersion = null): ConfigInterface
    {
        $this->apiVersion = $apiVersion;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getApexEndPoint(): string
    {
        return $this->apexEndPoint;
    }
    
    /**
     * @param mixed $apexEndPoint
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setApexEndPoint(string $apexEndPoint = null): ConfigInterface
    {
        $this->apexEndPoint = $apexEndPoint;
        
        return $this;
    }
}
