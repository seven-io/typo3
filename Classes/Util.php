<?php declare(strict_types=1);

namespace Sms77\Sms77Typo3;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

class Util {
    /**
     * @param string $transKey
     * @return array
     */
    public static function createTCA(string $transKey): array {
        return [
            'columns' => [
                'created' => [
                    'config' => [
                        'readOnly' => true,
                        'type' => 'none',
                    ],
                    'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:created',
                ],
                'config' => [
                    'config' => [
                        'eval' => 'trim,required',
                        'size' => 64,
                        'type' => 'input',
                    ],
                    'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:config',
                ],
                'response' => [
                    'config' => [
                        'eval' => 'trim',
                        'size' => 64,
                        'type' => 'input',
                    ],
                    'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:response',
                ],
                'type' => [
                    'label' => 'LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:type',
                ],
            ],
            'ctrl' => [
                'iconfile' => 'EXT:sms77typo3/Resources/Public/Icons/Extension.svg',
                'label' => 'created',
                'title' => "LLL:EXT:sms77typo3/Resources/Private/Language/locallang_db.xlf:$transKey",
            ],
            'types' => [
                '0' => ['showitem' => 'created, config, type, response'],
            ],
        ];
    }

    /**
     * @return array
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public static function getConfiguration(): array {
        return GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get('sms77typo3');
    }

    /**
     * @param string $ctrl
     * @param string $name
     * @param string $icon
     * @param string $labels
     * @return void
     */
    public static function registerModule(string $ctrl, string $name, string $icon, string $labels): void {
        ExtensionUtility::registerModule(
            'sms77typo3',
            'sms77typo3',
            "tx_sms77typo3_$name",
            '',
            [
                $ctrl => 'index, create, delete, new, show',
            ],
            [
                'icon' => $icon,
                'labels' => $labels,
            ]
        );

    }
}