<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\PathUtility;

// @extensionScannerIgnoreFile
class ImagePreviewViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument('selectedValue', 'string', 'Currently selected value', false, '');
        $this->registerArgument('id', 'string', 'ID of the select element', true);
        $this->registerArgument('baseThemeRootPath', 'string', 'Base path for the theme images', false, '');
        $this->registerArgument('currentThemeName', 'string', 'Current theme name', false, '');
        $this->registerArgument('siteRootPath', 'string', 'Root path of the site', true);
        $this->registerArgument('value', 'string', 'The value of the option', true);
        $this->registerArgument('label', 'string', 'The label of the option', true);
        $this->registerArgument('params', 'array', 'Parameters for options', false, []);
        $this->registerArgument('name', 'string', 'Parameters for options', false,);

    }

    public function render(): string
    {
        return static::renderStatic(
            $this->arguments,
            $this->renderChildrenClosure,
            $this->renderingContext
        );
    }

    public function ext_fNandV($params)
    {
        $fN = 'data[' . $params['name'] . ']';
        $idName = str_replace('.', '-', $params['name']);

        $fV = $params['value'];
        if (preg_match('/^{[\\$][a-zA-Z0-9\\.]*}$/', trim($fV), $reg)) {
            $fV = '';
        }
        $fV = htmlspecialchars($fV);
        return [$fN, $fV, $params, $idName];
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ): string {
        $selectedValue = $arguments['selectedValue'];
        $id = $arguments['id'];
        $baseThemeRootPath = $arguments['baseThemeRootPath'] ?? '';
        $currentThemeName = $arguments['currentThemeName'];
        $siteRootPath = $arguments['siteRootPath'];
        $value = $arguments['value'];
        $label = $arguments['label'];
        $params = $arguments['params'];

        if (empty($currentThemeName)) {
            $objNsBasetheme = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
            $arrAllExtensions = $objNsBasetheme->getInstalledChildTheme();
            $currentThemeName = $arrAllExtensions[0] ?? '';
        }

        $instance = new self();
        list($fN, $fV, $params, $id) = $instance->ext_fNandV($arguments);

        // Make selectBoxName dynamic
        $arrSelectBox = explode('-', $id);
        $selectBoxName = end($arrSelectBox);

        // Ensure $baseThemeRootPath is never null
        $NsBaseThemeRootPath = rtrim((string)$baseThemeRootPath, '/') . '/typo3conf/ext/' . $currentThemeName . '/Resources/Public/Backend/ThemeOptionsPreview/';

        if(\TYPO3\CMS\Core\Core\Environment::isComposerMode()) {
            $arguments = ['extensionName' => $currentThemeName];
            $path = $arguments['path'] ?? '';
            $publicPath = sprintf('EXT:%s/Resources/Public/%s', $arguments['extensionName'], ltrim($path, '/'));
            $assetPath = PathUtility::getPublicResourceWebPath($publicPath);           
            $NsBaseThemeRootPath =  $assetPath."Backend/ThemeOptionsPreview/";
        }
        else{
            // $uri = PathUtility::getPublicResourceWebPath($path);
            // $imagePath = GeneralUtility::locationHeaderUrl($uri);
            $NsBaseThemeRootPath = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . "/typo3conf/ext/".$currentThemeName."/Resources/Public/Public/Backend/ThemeOptionsPreview/";
        }  
        $NsBaseThemeRootPath = 'EXT:'.$currentThemeName.'/Resources/Public/Backend/ThemeOptionsPreview/';
        $imageExtension = ($selectBoxName == 'loader') ? '.gif' : '.png';
        $previewImagePath = $NsBaseThemeRootPath . $selectBoxName . '/' . htmlspecialchars($value) . $imageExtension;
        $uri = PathUtility::getPublicResourceWebPath($previewImagePath);
        return GeneralUtility::locationHeaderUrl($uri);
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($previewImagePath, __FILE__.' Line '.__LINE__);die;
        // return $previewImagePath;
    }




}
