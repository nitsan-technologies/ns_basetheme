<?php
namespace NITSAN\NsBasetheme\ExpressionLanguage;

use TYPO3\CMS\Core\ExpressionLanguage\AbstractProvider;

class CheckContainerExtension extends AbstractProvider
{
    public function __construct()
    {
        if (TYPO3_MODE !== 'BE') {
            $returnTrueFalse = false;
            if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container')) {
                $returnTrueFalse = true;
            }
            $this->expressionLanguageVariables = [
                'CheckContainerExtension' => $returnTrueFalse,
            ];
        }
    }
}
