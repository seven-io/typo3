<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Domain\Repository;

use PDO;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class MessageRepository extends Repository {
    private const TABLE_NAME = 'tx_sms77typo3_domain_model_message';

    public function removeByUid(int $uid): bool {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(self::TABLE_NAME);

        return $queryBuilder->delete(self::TABLE_NAME)
                ->where($queryBuilder->expr()->eq('uid',
                    $queryBuilder->createNamedParameter($uid, PDO::PARAM_INT)))
                ->execute() === 1;
    }
}
