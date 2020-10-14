<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Controller;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Sms77\Api\Client;
use Sms77\Sms77Typo3\Domain\Model\Message;
use Sms77\Sms77Typo3\Domain\Repository\MessageRepository;
use TYPO3\CMS\Backend\View\BackendTemplateView;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Backend module user/group action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
class MessageController extends ActionController implements LoggerAwareInterface {
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

    public function showAction(Message $message): void {
        $this->view->assign('message', $message);
    }

    public function createAction(array $config): void {
        if (!Environment::getContext()->isProduction()) {
            $this->logger->critical(json_encode($config));
        }

        $msg = new Message();
        $msg->setType($config['_type']);

        foreach ($config as $k => $v) {
            if ('' === $v) {
                unset($config[$k]);
            }
        }

        unset($config['_type'], $config['feUsers']);
        $msg->setConfig($config);

        $this->messageRepository->add($msg);
        $this->persistenceManager->persistAll();

        $apiKey = $this->getConfiguration()['apiKey'];
        $client = new Client($apiKey);
        $to = $config['to'];
        $text = $config['text'];
        unset($config['to'], $config['text']);

        $method = $msg->getType();
        $res = $client->$method($to, $text, $config);

        $msg->setResponse($res);
        $this->messageRepository->update($msg);
        $this->persistenceManager->persistAll();

        $this->redirect('show', null, null, ['message' => $msg]);
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

    /**
     * Deletes a message
     * @param int $uid
     * @return void
     * @throws StopActionException
     */
    public function deleteAction(int $uid): void {
        if ($this->messageRepository->removeByUid($uid)) {
            $msg = "delete_success";
            $title = 'success';
            $type = FlashMessage::OK;
        } else {
            $msg = "delete_error";
            $title = 'error';
            $type = FlashMessage::ERROR;
        }

        $this->cacheService->clearCachesOfRegisteredPageIds();

        $transRoot = 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_message.xlf:';
        $this->addFlashMessage(
            LocalizationUtility::translate("$transRoot$msg", null, [$uid]),
            LocalizationUtility::translate("$transRoot$title"), $type, true);

        $this->redirect('index');
    }

    /**
     * Index Action
     * @return void
     */
    public function indexAction(): void {
        $this->view->assign('messages', $this->messageRepository->findAll());
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
            $configuration = $this->getConfiguration();
        }

        if (!isset($configuration['apiKey'])) {
            $configuration['apiKey'] = $this->getConfiguration()['apiKey'];
        }

        if (array_key_exists('_type', $configuration)) {
            $type = $configuration['_type'];
        } else {
            $type = $type ?? 'sms';
        }

        foreach ($this->feUserRepository->findAll() as $k => $feUser) {
            /** @var FrontendUser $feUser */
            if ('' !== $feUser->getTelephone()) {
                $feUsers[] = $feUser;
            }
        }
        $configuration['_type'] = $type;

        $this->view->assignMultiple([
            'config' => (object)$configuration,
            'feUsers' => isset($feUsers) ? array_unique($feUsers) : [],
        ]);
    }
}