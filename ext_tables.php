<?php defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'sms77typo3',
    '',
    '',
    '',
    [
        'icon' => 'EXT:sms77typo3/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang.xlf',
        'name' => 'sms77typo3',
        'standalone' => true,
    ]
);

\Sms77\Sms77Typo3\Util::registerModule(
    \Sms77\Sms77Typo3\Controller\MessageController::class,
    'message',
    "EXT:sms77typo3/Resources/Public/Icons/actions-message.svg",
    'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_message.xlf'
);

\Sms77\Sms77Typo3\Util::registerModule(
    \Sms77\Sms77Typo3\Controller\LookupController::class,
    'lookup',
    "EXT:sms77typo3/Resources/Public/Icons/actions-search.svg",
    'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_lookup.xlf'
);