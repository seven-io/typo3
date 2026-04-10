<?php

declare(strict_types=1);

namespace Seven\TYPO3\Controller;

use Psr\Http\Message\ResponseInterface;
use Seven\TYPO3\Domain\Model\Message;
use Seven\TYPO3\Domain\Repository\MessageRepository;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

#[AsController]
class MessageController extends AbstractController
{
    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        PersistenceManager $persistenceManager,
        FrontendUserRepository $feUserRepository,
        MessageRepository $messageRepository,
    ) {
        parent::__construct(
            $moduleTemplateFactory,
            $persistenceManager,
            $feUserRepository,
            $messageRepository,
            Message::class,
        );
    }

    public function showAction(Message $resource): ResponseInterface
    {
        return $this->renderShowAction($resource);
    }
}
