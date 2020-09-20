<?php

namespace Sms77\Sms77Typo3\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Sms77\Sms77Typo3\Domain\Repository\MessageRepository;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

/**
 * Backend module user/group action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
abstract class AbstractController extends ActionController implements LoggerAwareInterface {
    use LoggerAwareTrait;

    /** @var FrontendUserRepository $feUserRepository */
    protected $feUserRepository;

    /** @var PersistenceManager $persistenceManager */
    protected $persistenceManager;

    /** @var MessageRepository $messageRepository */
    protected $messageRepository;

    /** @var string $defaultViewObjectName Backend Template Container */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /** @param PersistenceManager $persistenceManager */
    public function injectPersistenceManager(PersistenceManager $persistenceManager): void {
        $this->persistenceManager = $persistenceManager;
    }

    /** @param FrontendUserRepository $feUserRepository */
    public function injectFeUserRepository(FrontendUserRepository $feUserRepository): void {
        $this->feUserRepository = $feUserRepository;
    }

    /** @param MessageRepository $messageRepository */
    public function injectMessageRepository(MessageRepository $messageRepository): void {
        $this->messageRepository = $messageRepository;
    }

    /**
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function getConfiguration(): array {
        return GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('sms77typo3');
    }
}