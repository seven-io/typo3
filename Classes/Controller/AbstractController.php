<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Sms77\Api\Client;
use Sms77\Sms77Typo3\Domain\Model\Model;
use Sms77\Sms77Typo3\Util;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Backend module message action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
abstract class AbstractController extends ActionController implements LoggerAwareInterface {
    use LoggerAwareTrait;

    /** @var string $defaultViewObjectName Backend Template Container */
    protected $defaultViewObjectName = BackendTemplateView::class;

    /** @var FrontendUserRepository $_feUserRepository */
    private $_feUserRepository;

    /** @var PersistenceManager $_persistenceManager */
    private $_persistenceManager;

    /** @var Repository $_repository */
    private $_repository;

    /** @var string $_resourceFQDN */
    private $_resourceFQDN;

    public function __construct(Repository $repo, string $fqdn) {
        $this->_repository = $repo;
        $this->_resourceFQDN = $fqdn;
    }

    /**
     * @param int $uid
     * @return void
     * @throws StopActionException
     */
    public function deleteAction(int $uid): void {
        if ($this->_repository->removeByUid($uid)) {
            $msg = "delete_success";
            $title = 'success';
            $type = FlashMessage::OK;
        } else {
            $msg = "delete_error";
            $title = 'error';
            $type = FlashMessage::ERROR;
        }

        $this->cacheService->clearCachesOfRegisteredPageIds();

        $transRoot = 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang.xlf:';
        $this->addFlashMessage(
            LocalizationUtility::translate("$transRoot$msg", null, [$uid]),
            LocalizationUtility::translate("$transRoot$title"), $type, true);

        $this->redirect('index');
    }

    public function _showAction($resource): void {
        $this->view->assign('resource', $resource);
    }

    /**
     * @param array|null $configuration
     * @param string|null $type
     * @return void
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     */
    public function newAction(?array $configuration = null, ?string $type = null): void {
        if (!isset($configuration)) {
            $configuration = Util::getConfiguration();
        }

        if (!isset($configuration['apiKey'])) {
            $configuration['apiKey'] = Util::getConfiguration()['apiKey'];
        }

        $feUsers = [];
        $isLookup = false;

        if ('lookup' === $type) {
            $isLookup = true;
        } elseif (array_key_exists('type', $configuration)) { // is resend lookup
            $isLookup = true;
            $type = $configuration['type'];
        } else {
            if (array_key_exists('_type', $configuration)) { // is voice or sms
                $type = $configuration['_type'];
            } else {
                $type = $type ?? 'sms'; // fallback for older versions without type column
            }

            foreach ($this->_feUserRepository->findAll() as $k => $feUser) {
                /** @var FrontendUser $feUser */
                if ('' !== $feUser->getTelephone()) {
                    $feUsers[] = $feUser;
                }
            }

            $configuration['_type'] = $type;
        }

        $this->view->assignMultiple([
            'config' => (object)$configuration,
            'feUsers' => array_unique($feUsers),
        ]);
    }

    /**
     * @param array $config
     * @return void
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\IllegalObjectTypeException
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\UnknownObjectException
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     */
    public function createAction(array $config): void {
        $this->log($config);

        /** @var Model $msg */
        $resource = new $this->_resourceFQDN();
        $isLookup = isset($config['type']);
        $resource->setType($isLookup ? $config['type'] : $config['_type']);

        foreach ($config as $k => $v) {
            if ('' === $v) {
                unset($config[$k]);
            }
        }

        $type = $resource->getType();

        unset($config['_type'], $config['feUsers']);

        $resource->setConfig($config);

        $this->_repository->add($resource);
        $this->_persistenceManager->persistAll();

        $client = new Client(Util::getConfiguration()['apiKey'], 'typo3');
        $arg1Key = $isLookup ? 'type' : 'to';
        $arg2Key = $isLookup ? 'number' : 'text';
        $arg1 = $config[$arg1Key];
        $arg2 = $config[$arg2Key];
        unset($config[$arg1], $config[$arg2]);

        $res = $client->{$isLookup ? 'lookup' : $type}($arg1, $arg2, $config);
        $this->log($res);

        $resource->setResponse($res);
        $this->_repository->update($resource);
        $this->_persistenceManager->persistAll();

        $this->redirect('show', null, null, ['resource' => $resource]);
    }

    /**
     * @param mixed $data
     * @return void
     */
    private function log($data): void {
        if (!Environment::getContext()->isProduction()) {
            $this->logger->critical(json_encode($data));
        }
    }

    /** @param PersistenceManager $persistenceManager */
    public function injectPersistenceManager(PersistenceManager $persistenceManager): void {
        $this->_persistenceManager = $persistenceManager;
    }

    /** @param FrontendUserRepository $feUserRepository */
    public function injectFeUserRepository(FrontendUserRepository $feUserRepository): void {
        $this->_feUserRepository = $feUserRepository;
    }

    /** @return void */
    public function indexAction(): void {
        $this->view->assignMultiple([
            'resources' => $this->_repository->findAll(),
        ]);
    }
}