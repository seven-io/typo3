<?php

declare(strict_types=1);

namespace Seven\TYPO3\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class IsIntegerViewHelper extends AbstractViewHelper
{
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext,
    ): bool {
        return is_int($arguments['data']);
    }

    public function initializeArguments(): void
    {
        $this->registerArgument('data', 'mixed', 'The data to test for', true);
    }
}
