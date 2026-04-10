<?php

declare(strict_types=1);

namespace Seven\TYPO3\Controller;

use Psr\Http\Message\ResponseInterface;
use Seven\TYPO3\Domain\Model\Lookup;
use Seven\TYPO3\Domain\Repository\LookupRepository;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;

#[AsController]
class LookupController extends AbstractController
{
    public function __construct(
        ModuleTemplateFactory $moduleTemplateFactory,
        PersistenceManager $persistenceManager,
        FrontendUserRepository $feUserRepository,
        LookupRepository $lookupRepository,
    ) {
        parent::__construct(
            $moduleTemplateFactory,
            $persistenceManager,
            $feUserRepository,
            $lookupRepository,
            Lookup::class,
        );
    }

    public function showAction(Lookup $resource): ResponseInterface
    {
        return $this->renderShowAction($resource);
    }
}
