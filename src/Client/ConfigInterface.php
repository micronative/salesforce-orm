<?php

namespace Salesforce\Client;

interface ConfigInterface
{
    /**
     * @return string
     */
    public function getClientId(): string;
    
    /**
     * @param string $clientId client id
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setClientId(string $clientId = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getClientSecret(): string;
    
    /**
     * @param string $clientSecret secret
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setClientSecret(string $clientSecret = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getPath(): string;
    
    /**
     * @param string $path path
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setPath(string $path = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getUsername(): string;
    
    /**
     * @param string $username username
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setUsername(string $username = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getPassword(): string;
    
    /**
     * @param string $password password
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setPassword(string $password = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getApiVersion(): string;
    
    /**
     * @param string $apiVersion api version
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setApiVersion(string $apiVersion = null): ConfigInterface;
    
    /**
     * @return string
     */
    public function getApexEndPoint(): string;
    
    /**
     * @param mixed $apexEndPoint
     * @return \Salesforce\Client\ConfigInterface
     */
    public function setApexEndPoint(string $apexEndPoint = null): ConfigInterface;
}
