<?php declare(strict_types=1);

namespace Seven\TYPO3\Domain\Repository;

use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

abstract class AbstractRepository extends Repository {
    /** @var string $_tableName */
    private $_tableName;

    public function __construct(ObjectManagerInterface $objectManager, string $tableName) {
        parent::__construct($objectManager);

        $this->_tableName = "tx_seventypo3_domain_model_$tableName";
    }

    public function removeByUid(int $uid): bool {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable($this->_tableName);

        return $queryBuilder->delete($this->_tableName)
                ->where($queryBuilder->expr()->eq('uid',
                    $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT)))
                ->execute() === 1;
    }
}
