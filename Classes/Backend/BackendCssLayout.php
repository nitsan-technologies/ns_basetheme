<?php

namespace NITSAN\NsBasetheme\Backend;

use NITSAN\NsBasetheme\NsBasethemeUtility;
use TYPO3\CMS\Backend\Controller\Event\ModifyPageLayoutContentEvent;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class BackendCssLayout
{
    /**
     * @var NsBasethemeUtility
     */
    private NsBasethemeUtility $objNsBasetheme;

    /**
     * @param AssetCollector $pageRenderer
     */
    public function __construct(private readonly AssetCollector $renderer)
    {
        $this->objNsBasetheme = GeneralUtility::makeInstance(NsBasethemeUtility::class);
    }

    public function __invoke(ModifyPageLayoutContentEvent $event): void
    {
        $siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath().'/typo3conf/ext/';
        if (Environment::isComposerMode()) {
            $siteRoot = Environment::getComposerRootPath() . '/vendor/nitsan/';
        }
        $arrAllExtensions = $this->objNsBasetheme->getInstalledChildTheme();
        if (count($arrAllExtensions) > 0) {
            foreach ($arrAllExtensions as $extKey) {
                $rExtkey = $extKey;
                if (Environment::isComposerMode()) {
                    $extKey = str_replace('_', '-', $extKey);
                }
                // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
                $extensionPrefixKey = substr($rExtkey, 0, 9);

                if ($extensionPrefixKey == 'ns_theme_') {
                    // Grab CSS/JS of EXT.ns_basetheme
                    $css = $siteRoot . $extKey . '/Resources/Public/css/Backend.css';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $this->renderer->addStyleSheet('Base', 'EXT:ns_basetheme/Resources/Public/css/Backend.css');
                    }
                    $jsNaBaseThemeBackend = $siteRoot . $extKey . '/Resources/Public/JavaScript/Backend.js';
                    $jsNaBaseThemeImagePreview = $siteRoot . $extKey . '/Resources/Public/JavaScript/ImagePreview.js';
                    if (file_exists($jsNaBaseThemeImagePreview)) {
                        $this->renderer->addJavaScript('BasethemeJS', 'EXT:ns_basetheme/Resources/Public/JavaScript/ImagePreview.js');
                    }
                    if (file_exists($jsNaBaseThemeBackend)) {
                        $this->renderer->addJavaScript('themeBackend', 'EXT:ns_basetheme/Resources/Public/JavaScript/Backend.js');
                    }

                    // Grab CSS/JS of EXT.ns_theme_name
                    $css = $siteRoot . $extKey . '/Resources/Public/Backend/Css/Backend.css';
                    $js = $siteRoot . $extKey . '/Resources/Public/Backend/JavaScript/Backend.js';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $this->renderer->addStyleSheet('Childbase', $css);
                    }
                    if (file_exists($js)) {
                        $this->renderer->addJavaScript('childbaseJS', 'EXT:' . $extKey . '/Resources/Public/Backend/JavaScript/Backend.js');
                    }
                    unset($css);
                    unset($js);
                }
            }
        }

    }
}