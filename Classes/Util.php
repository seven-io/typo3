<?php

declare(strict_types=1);

namespace Seven\TYPO3;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Util
{
    public static function createTCA(string $transKey): array
    {
        return [
            'columns' => [
                'created' => [
                    'config' => [
                        'readOnly' => true,
                        'type' => 'number',
                    ],
                    'label' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_db.xlf:created',
                ],
                'config' => [
                    'config' => [
                        'required' => true,
                        'type' => 'text',
                        'rows' => 5,
                    ],
                    'label' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_db.xlf:config',
                ],
                'response' => [
                    'config' => [
                        'type' => 'text',
                        'rows' => 5,
                    ],
                    'label' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_db.xlf:response',
                ],
                'type' => [
                    'config' => [
                        'type' => 'input',
                    ],
                    'label' => 'LLL:EXT:seventypo3/Resources/Private/Language/locallang_db.xlf:type',
                ],
            ],
            'ctrl' => [
                'iconfile' => 'EXT:seventypo3/Resources/Public/Icons/Extension.svg',
                'label' => 'created',
                'title' => "LLL:EXT:seventypo3/Resources/Private/Language/locallang_db.xlf:$transKey",
            ],
            'types' => [
                '0' => ['showitem' => 'created, config, type, response'],
            ],
        ];
    }

    public static function getConfiguration(): array
    {
        return GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('seventypo3');
    }
}
