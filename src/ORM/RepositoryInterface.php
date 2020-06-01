<?php

namespace Salesforce\ORM;

interface RepositoryInterface
{
    
    /**
     * Find object by id
     *
     * @param string $id id
     * @param bool $lazy lazy loading relations
     * @return \Salesforce\ORM\Entity
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function find(string $id, $lazy = false);
    
    /**
     * Find objects on by conditions
     *
     * @param array $conditions conditions
     * @param array $orders order
     * @param int $limit
     * @param bool $lazy lazy loading relations
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function findBy(array $conditions = null, array $orders = null, int $limit = null, bool $lazy = false);
    
    /**
     * Find all object of this class
     *
     * @param array $orders order
     * @param bool $lazy lazy loading relations
     * @return array|bool
     * @throws \Salesforce\ORM\Exception\RepositoryException
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function findAll(array $orders = null, bool $lazy = true);
    
    /**
     * @throws \Salesforce\ORM\Exception\MapperException
     * @throws \Salesforce\Client\Exception\ClientException
     * @throws \Salesforce\Client\Exception\ResultException
     * @throws \Salesforce\ORM\Exception\EntityException
     */
    public function count();
    
    /**
     * @return string
     */
    public function getClassName();
    
    /**
     * @param string $className class name
     * @return \Salesforce\ORM\RepositoryInterface
     */
    public function setClassName($className);
    
    /**
     * @return \Salesforce\ORM\EntityManager
     */
    public function getEntityManager();
    
    /**
     * @param \Salesforce\ORM\EntityManager $entityManager entity manager
     * @return \Salesforce\ORM\RepositoryInterface
     */
    public function setEntityManager(EntityManager $entityManager = null);
    
    /**
     * @return \Salesforce\ORM\EntityFactory
     */
    public function getEntityFactory();
    
    /**
     * @param \Salesforce\ORM\EntityFactory $entityFactory
     * @return Repository
     */
    public function setEntityFactory(EntityFactory $entityFactory = null);
    
}
