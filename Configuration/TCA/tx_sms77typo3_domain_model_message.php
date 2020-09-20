<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:message',
        'label' => 'created',
        'iconfile' => 'EXT:sms77typo3/Resources/Public/Icons/Extension.svg',
    ],
    'columns' => [
        'created' => [
            'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:created',
            'config' => [
                'type' => 'none',
                'readOnly' => true,
            ],
        ],
        'config' => [
            'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:config',
            'config' => [
                'eval' => 'trim,required',
                'size' => 64,
                'type' => 'input',
            ],
        ],
        'response' => [
            'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:response',
            'config' => [
                'eval' => 'trim',
                'size' => 64,
                'type' => 'input',
            ],
        ],
    ],
    'types' => [
        '0' => ['showitem' => 'created, config, response'],
    ],
];