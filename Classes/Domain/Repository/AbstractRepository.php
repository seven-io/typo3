<?php

declare(strict_types=1);

namespace Seven\TYPO3\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Repository;

abstract class AbstractRepository extends Repository
{
    private string $tableName;

    public function __construct(
        private readonly ConnectionPool $connectionPool,
        string $tableName,
    ) {
        parent::__construct();
        $this->tableName = "tx_seventypo3_domain_model_$tableName";
    }

    public function removeByUid(int $uid): bool
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable($this->tableName);

        return $queryBuilder->delete($this->tableName)
                ->where($queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT),
                ))
                ->executeStatement() === 1;
    }
}
