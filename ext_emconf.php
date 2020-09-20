<?php

/** @var string $_EXTKEY extension key set by typo3 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Sms77.io API',
    'description' => 'Send SMS via the Sms77.io gateway.',
    'category' => 'module',
    'author' => 'Andre Matthies',
    'author_company' => 'sms77 e.K.',
    'author_email' => 'a.matthies@sms77.io',
    'state' => 'beta',
    'clearCacheOnLoad' => false,
    'version' => '0.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.0.00-10.4.99',
            'php' => '7.2.0-7.3.99'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Sms77\\Sms77Typo3\\' => 'Classes',
        ],
    ],
];