<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'seven.io API',
    'description' => 'Send SMS & voice messages via seven.io.',
    'category' => 'module',
    'author' => 'seven communications GmbH & Co. KG',
    'author_company' => 'seven communications GmbH & Co. KG',
    'author_email' => 'support@seven.io',
    'state' => 'stable',
    'clearCacheOnLoad' => false,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.4.99',
            'php' => '8.2.0-8.4.99',
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Seven\\TYPO3\\' => 'Classes',
        ],
    ],
];
