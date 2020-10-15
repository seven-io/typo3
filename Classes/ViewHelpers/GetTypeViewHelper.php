<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetTypeViewHelper extends AbstractViewHelper {
    /**
     * Render unordered list for pages
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext) {
        return gettype($arguments['data']);
    }

    public function initializeArguments() {
        $this->registerArgument('data', 'mixed', 'The data look up', true);
    }
}
