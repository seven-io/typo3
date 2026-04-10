<?php

declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    'seventypo3-extension' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:seventypo3/Resources/Public/Icons/Extension.svg',
    ],
    'seventypo3-message' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:seventypo3/Resources/Public/Icons/actions-message.svg',
    ],
    'seventypo3-lookup' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:seventypo3/Resources/Public/Icons/actions-search.svg',
    ],
];
