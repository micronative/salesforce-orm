<?php

namespace Salesforce\Repository;

use Salesforce\Entity\Account;
use Salesforce\ORM\Repository;

class AccountRepository extends Repository
{
    protected $className = Account::class;
}
