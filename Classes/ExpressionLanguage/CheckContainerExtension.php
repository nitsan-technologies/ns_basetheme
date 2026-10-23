<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\ExpressionLanguage;

use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

class CheckContainerExtension extends AbstractProvider
{
    public function __construct()
    {
        if (defined('TYPO3') && TYPO3 === 'BE') {
            return;
        }

        $loaded = false;
        try {
            $loaded = ExtensionManagementUtility::isLoaded('container');
        } catch (\Throwable) {
            $loaded = false;
        }

        $this->expressionLanguageVariables = [
            'CheckContainerExtension' => $loaded,
        ];
    }
}
