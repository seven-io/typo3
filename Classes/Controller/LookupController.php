<?php declare(strict_types=1);

namespace Seven\TYPO3\Controller;

use Seven\TYPO3\Domain\Model\Lookup;
use Seven\TYPO3\Domain\Repository\LookupRepository;

/**
 * Backend module lookup action controller
 * Scope: backend
 * @package Seven\TYPO3\Controller
 */
class LookupController extends AbstractController {
    public function __construct(LookupRepository $lookupRepository) {
        parent::__construct($lookupRepository, Lookup::class);
    }

    public function showAction(Lookup $resource): void {
        $this->_showAction($resource);
    }
}
