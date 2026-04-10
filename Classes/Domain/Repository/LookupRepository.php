<?php

declare(strict_types=1);

namespace Seven\TYPO3\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;

class LookupRepository extends AbstractRepository
{
    public function __construct(ConnectionPool $connectionPool)
    {
        parent::__construct($connectionPool, 'lookup');
    }
}
