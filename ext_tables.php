<?php
defined('TYPO3_MODE') || die();

(function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
        'sms77typo3',
        'tools',
        'tx_sms77typo3',
        'top',
        [
            \Sms77\Sms77Typo3\Controller\MessageController::class => 'index, create, delete, new, show',
        ],
        [
            'access' => 'admin',
            'icon' => 'EXT:sms77typo3/Resources/Public/Icons/Extension.svg',
            'labels' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang.xlf',
        ]
    );
})();