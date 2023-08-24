<?php defined('TYPO3_MODE') || die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addModule(
    'seventypo3',
    '',
    '',
    '',
    [
        'icon' => 'EXT:seventypo3/Resources/Public/Icons/Extension.svg',
        'labels' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang.xlf',
        'name' => 'seventypo3',
        'standalone' => true,
    ]
);

\Seven\TYPO3\Util::registerModule(
    \Seven\TYPO3\Controller\MessageController::class,
    'message',
    "EXT:seventypo3/Resources/Public/Icons/actions-message.svg",
    'LLL:EXT:seventypo3/Resources/Private/Language/locallang_message.xlf'
);

\Seven\TYPO3\Util::registerModule(
    \Seven\TYPO3\Controller\LookupController::class,
    'lookup',
    "EXT:seventypo3/Resources/Public/Icons/actions-search.svg",
    'LLL:EXT:seventypo3/Resources/Private/Language/locallang_lookup.xlf'
);
