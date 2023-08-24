<?php declare(strict_types=1);

namespace Seven\TYPO3\Controller;

use Seven\TYPO3\Domain\Model\Message;
use Seven\TYPO3\Domain\Repository\MessageRepository;

/**
 * Backend module message action controller
 * Scope: backend
 * @package Seven\TYPO3\Controller
 */
class MessageController extends AbstractController {
    public function __construct(MessageRepository $messageRepository) {
        parent::__construct($messageRepository, Message::class);
    }

    public function showAction(Message $resource): void {
        $this->_showAction($resource);
    }
}
