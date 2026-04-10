<?php

declare(strict_types=1);

use Seven\TYPO3\Controller\LookupController;
use Seven\TYPO3\Controller\MessageController;

return [
    'seventypo3' => [
        'labels' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang.xlf',
        'iconIdentifier' => 'seventypo3-extension',
        'position' => ['after' => 'web'],
        'standalone' => true,
    ],
    'seventypo3_message' => [
        'parent' => 'seventypo3',
        'access' => 'user',
        'path' => '/module/seventypo3/message',
        'labels' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_message.xlf',
        'iconIdentifier' => 'seventypo3-message',
        'extensionName' => 'seventypo3',
        'controllerActions' => [
            MessageController::class => [
                'index',
                'new',
                'create',
                'delete',
                'show',
            ],
        ],
    ],
    'seventypo3_lookup' => [
        'parent' => 'seventypo3',
        'access' => 'user',
        'path' => '/module/seventypo3/lookup',
        'labels' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_lookup.xlf',
        'iconIdentifier' => 'seventypo3-lookup',
        'extensionName' => 'seventypo3',
        'controllerActions' => [
            LookupController::class => [
                'index',
                'new',
                'create',
                'delete',
                'show',
            ],
        ],
    ],
];
