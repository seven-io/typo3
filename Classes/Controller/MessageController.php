<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Controller;

use Sms77\Sms77Typo3\Domain\Model\Message;
use Sms77\Sms77Typo3\Domain\Repository\MessageRepository;

/**
 * Backend module message action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
class MessageController extends AbstractController {
    public function __construct(MessageRepository $messageRepository) {
        parent::__construct($messageRepository, Message::class);
    }

    public function showAction(Message $resource): void {
        $this->_showAction($resource);
    }
}