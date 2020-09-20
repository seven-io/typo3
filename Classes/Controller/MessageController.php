<?php

namespace Sms77\Sms77Typo3\Controller;

use Sms77\Api\Client;
use Sms77\Sms77Typo3\Domain\Model\Message;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

/**
 * Backend module user/group action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
class MessageController extends AbstractController {
    public function showAction(Message $message): void {
        $this->view->assign('message', $message);
    }

    public function createAction(array $config): void {
        $this->logger->critical(json_encode($config));

        foreach ($config as $k => $v) {
            if ('' === $v) {
                unset($config[$k]);
            }
        }

        unset($config['feUsers']);

        $message = new Message();
        $message->setConfig($config);

        $this->messageRepository->add($message);
        $this->persistenceManager->persistAll();

        $apiKey = $this->getConfiguration()['apiKey'];
        $client = new Client($apiKey);
        $to = $config['to'];
        $text = $config['text'];
        unset($config['to'], $config['text']);

        $res = $client->sms($to, $text, $config);

        $message->setResponse($res);
        $this->messageRepository->update($message);
        $this->persistenceManager->persistAll();

        $this->redirect('show', null, null, ['message' => $message]);
    }

    /**
     * Deletes a message
     * @param int $uid
     * @return void
     * @throws StopActionException
     */
    public function deleteAction(int $uid): void {
        if ($this->messageRepository->removeByUid($uid)) {
            $msg = "Successfully deleted message with UID #$uid";
            $title = 'Success';
            $type = FlashMessage::OK;
        } else {
            $msg = "Failed to delete message with UID #$uid";
            $title = 'Error';
            $type = FlashMessage::ERROR;
        }

        $this->cacheService->clearCachesOfRegisteredPageIds();

        $this->addFlashMessage($msg, $title, $type, true);

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
     * New Action
     * @param array|null $configuration
     * @return void
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function newAction(array $configuration = null): void {
        !$configuration && $configuration = $this->getConfiguration();

        foreach ($this->feUserRepository->findAll() as $k => $feUser) {
            /** @var FrontendUser $feUser */
            if ('' !== $feUser->getTelephone()) {
                $feUsers[] = $feUser;
            }
        }

        $this->view->assignMultiple([
            'config' => (object)$configuration,
            'feUsers' => isset($feUsers) ? array_unique($feUsers) : [],
        ]);
    }
}