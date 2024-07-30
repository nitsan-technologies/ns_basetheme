<?php

namespace NITSAN\NsBasetheme\ViewHelpers;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class ImagePreviewViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
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
    ) {
        $selectedValue = $arguments['selectedValue'];
        $id = $arguments['id'];
        $baseThemeRootPath = $arguments['baseThemeRootPath'];
        $currentThemeName = $arguments['currentThemeName'];
        $siteRootPath = $arguments['siteRootPath'];
        $value = $arguments['value'];
        $label = $arguments['label'];
        $params = $arguments['params'];

        if (empty($currentThemeName)) {
            $objNsBasetheme = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
            $arrAllExtensions = $objNsBasetheme->getInstalledChildTheme();
            $currentThemeName = isset($arrAllExtensions[0]) ? $arrAllExtensions[0] : '';
        }

        // $idName = str_replace('.', '-', $id);

        $instance = new self();
        list($fN, $fV, $params, $id) = $instance->ext_fNandV($arguments);

        // Make selectBoxName dynamic
        $arrSelectBox = explode('-', $id);
        // \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($arrSelectBox, __FILE__.' Line '.__LINE__);die;
        $selectBoxName = end($arrSelectBox);
      
        $NsBaseThemeRootPath = rtrim($baseThemeRootPath, '/') . '/typo3conf/ext/' . $currentThemeName . '/Resources/Public/Backend/ThemeOptionsPreview/';

        $imageExtension = ($selectBoxName == 'loader') ? '.gif' : '.png';
        $previewImagePath = $NsBaseThemeRootPath . $selectBoxName . '/' . htmlspecialchars($value) . $imageExtension;

        return $previewImagePath;
    }

    public function ext_getTypeData($type)
    {
        $retArr = [];
        $type = trim($type);
        if (!$type) {
            $retArr['type'] = 'string';
        } else {
            $m = strcspn($type, ' [');
            $retArr['type'] = strtolower(substr($type, 0, $m));
            $types = ['int' => 1, 'options' => 1, 'file' => 1, 'boolean' => 1, 'offset' => 1, 'user' => 1, 'checkbox'=>1];
            if (isset($types[$retArr['type']])) {
                $p = trim(substr($type, $m));
                $reg = [];
                preg_match('/\\[(.*)\\]/', $p, $reg);
                if(isset($reg[1])){
                    $p = trim($reg[1]);
                }
                if ($p) {
                    $retArr['paramstr'] = $p;
                    switch ($retArr['type']) {
                        case 'options':
                            $retArr['params'] = explode(',', $retArr['paramstr']);
                            break;
                    }
                }
            }
        }
        return $retArr;
    }
}
