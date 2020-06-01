<?php
namespace Salesforce\ORM;

abstract class RelationHandle
{
    /** @var EntityManager */
    protected $entityManager;

    /**
     * RelationHandle constructor.
     *
     * @param \Salesforce\ORM\EntityManager $entityManager entity manager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
