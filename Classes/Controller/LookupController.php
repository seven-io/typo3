<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\Controller;

use Sms77\Sms77Typo3\Domain\Model\Lookup;
use Sms77\Sms77Typo3\Domain\Repository\LookupRepository;

/**
 * Backend module lookup action controller
 * Scope: backend
 * @package Sms77\Sms77Typo3\Controller
 */
class LookupController extends AbstractController {
    public function __construct(LookupRepository $lookupRepository) {
        parent::__construct($lookupRepository, Lookup::class);
    }

    public function showAction(Lookup $resource): void {
        $this->_showAction($resource);
    }
}