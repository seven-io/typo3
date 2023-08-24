<?php

/** @var string $_EXTKEY extension key set by typo3 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'seven.io API',
    'description' => 'Send SMS & voice messages via seven.io.',
    'category' => 'module',
    'author' => 'seven communications GmbH & Co. KG',
    'author_company' => 'seven communications GmbH & Co. KG',
    'author_email' => 'support@seven.io',
    'state' => 'stable',
    'clearCacheOnLoad' => false,
    'version' => '0.4.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.0.00-10.4.99',
            'php' => '7.2.0-7.3.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Seven\\TYPO3\\' => 'Classes',
        ],
    ],
];
