<?php

declare(strict_types=1);

namespace Seven\TYPO3\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class GetTypeViewHelper extends AbstractViewHelper
{
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): string {
        return gettype($arguments['data']);
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('data', 'mixed', 'The data to look up', true);
    }
}
