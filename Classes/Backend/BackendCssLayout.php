<?php
declare(strict_types=1);

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
            $siteRoot = Environment::getProjectPath() . '/vendor/nitsan/';
        }
        $arrAllExtensions = $this->objNsBasetheme->getInstalledChildTheme();
        if (count($arrAllExtensions) > 0) {
            $basethemeKey = 'ns_basetheme';
            foreach ($arrAllExtensions as $extKey) {
                $rExtkey = $extKey;
                if (Environment::isComposerMode()) {
                    $basethemeKey = str_replace('_', '-', $basethemeKey);
                    $extKey = str_replace('_', '-', $extKey);
                }
                // Get only extension which are child theme eg., EXT:ns_theme_cleanblog
                $extensionPrefixKey = substr($rExtkey, 0, 9);
                if ($extensionPrefixKey === 'ns_theme_') {
                    // Grab CSS/JS of EXT.ns_basetheme
                    $css = $siteRoot . $extKey . '/Resources/Public/css/Backend.css';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $this->renderer->addStyleSheet('Base', 'EXT:ns_basetheme/Resources/Public/css/Backend.css');
                    }

                    $jsNaBaseThemeImagePreview = $siteRoot .$basethemeKey. '/Resources/Public/JavaScript/ImagePreview.js';
                    if (file_exists($jsNaBaseThemeImagePreview)) {
                        $this->renderer->addJavaScript('NsBaseThemeImagePreviewJs', 'EXT:ns_basetheme/Resources/Public/JavaScript/ImagePreview.js');
                    }
                    $jsNaBaseThemeBackend = $siteRoot . $basethemeKey . '/Resources/Public/JavaScript/Backend.js';
                    if (file_exists($jsNaBaseThemeBackend)) {
                        $this->renderer->addJavaScript('NsBaseThemeBackendJs', 'EXT:ns_basetheme/Resources/Public/JavaScript/Backend.js');
                    }
                    $js = $siteRoot . $extKey . '/Resources/Public/Backend/JavaScript/ThemeBackend.js';
                    if (file_exists($js)) {
                        $this->renderer->addJavaScript('ChildThemeBackendJs', 'EXT:' . $rExtkey . '/Resources/Public/Backend/JavaScript/ThemeBackend.js');
                    }

                    // Grab CSS/JS of EXT.ns_theme_name
                    $css = $siteRoot . $extKey . '/Resources/Public/Backend/Css/Backend.css';
                    if (file_exists($css)) {
                        // @extensionScannerIgnoreLine
                        $this->renderer->addStyleSheet('Childbase', 'EXT:' . $rExtkey . '/Resources/Public/Backend/Css/Backend.css');
                    }
                    unset($css);
                    unset($js);
                }
            }
        }
    }
}
