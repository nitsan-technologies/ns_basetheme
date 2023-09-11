<?php
declare(strict_types=1);

use NITSAN\NsBasetheme\NsBasethemeUtility;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$objNsBasetheme = GeneralUtility::makeInstance(NsBasethemeUtility::class);
$allComponents = $arrAllExtensions = [];
$arrAllExtensions = $objNsBasetheme->getInstalledChildTheme();
$allComponents = $objNsBasetheme->getChildThemeComponents($arrAllExtensions);
$iconList = [];
$siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/typo3conf/ext/';
if (Environment::isComposerMode()) {
    $siteRoot = Environment::getProjectPath() . '/vendor/nitsan/';
}
if (count($allComponents) > 0) {
    foreach ($allComponents as $extKey => $extValue) {
        if (count($extValue) > 0) {
            foreach ($extValue as $key => $theComponent) {
                $identifier = str_replace('_', '-', $theComponent);
                if (Environment::isComposerMode()) {
                    $extPackKey = str_replace('_', '-', $extKey);
                    $iconList[$identifier] = [
                        'provider' => BitmapIconProvider::class,
                        'source' => (
                        file_exists($siteRoot . $extPackKey . '/Resources/Public/Icons/' . $theComponent . '.png')
                        )
                            ? 'EXT:' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png'
                            : 'EXT:ns_basetheme/Resources/Public/Icons/default_icon.png'
                    ];
                } else {

                    $iconList[$identifier] = [
                        'provider' => BitmapIconProvider::class,
                        'source' => (
                        file_exists($siteRoot . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png')
                        )
                            ? 'EXT:' . $extKey . '/Resources/Public/Icons/' . $theComponent . '.png'
                            : 'EXT:ns_basetheme/Resources/Public/Icons/default_icon.png'
                    ];
                }

            }
        }
    }
}

return $iconList;