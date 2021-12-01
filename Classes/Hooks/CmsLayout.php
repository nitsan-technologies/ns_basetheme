<?php

namespace NITSAN\NsBasetheme\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Hook to display verbose information about the felogin plugin
 *
 */
class CmsLayout implements PageLayoutViewDrawItemHookInterface
{

    /**
     * Preprocesses the preview rendering of a content element.
     *
     * @param \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionalities
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     * @return  void
     */
    public function preProcess(
        \TYPO3\CMS\Backend\View\PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        $objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

        // Get Components from ext_localconf.php
        $allComponents = constant('ALL_COMPONENTS');
        $rowFlag = $extensionKey = '';

        // Finalize components
        foreach ($allComponents as $extKey => $extValue) {
            foreach ($extValue as $key => $theComponent) {
                if ($row['CType'] == $theComponent) {
                    $rowFlag = 1;
                    $extensionKey = $extKey;
                }
            }
        }

        // Let's check if our components is going to be load in backned?
        if ($rowFlag == 1 && $extensionKey != '') {
            $drawItem = false;
            $headerContent = '';

            // template
            $view = $this->getFluidTemplate(
                $extKey,
                GeneralUtility::underscoredToUpperCamelCase($row['CType']),
                $extensionKey
            );

            if (!empty($row['pi_flexform'])) {
                /** @var FlexFormService $flexFormService */
                if (version_compare(TYPO3_branch, '9.0', '>')) {
                    $flexFormService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
                } else {
                    $flexFormService = GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\Service\FlexFormService::class);
                }
                $flexformData = $flexFormService->convertFlexFormContentToArray($row['pi_flexform']);
            }

            // same name as field name
            $images = BackendUtility::resolveFileReferences('tt_content', 'image', $row);

            // assign all to view
            $view->assignMultiple([
                'data' => $row,
                'image' => $images,
                'flexformData' => $flexformData,
            ]);

            // return the preview
            $itemContent = $parentObject->linkEditContent($view->render(), $row);
        }
    }

    /**
     * @param string $extKey
     * @param string $templateName
     * @return string the fluid template
     */
    protected function getFluidTemplate($extKey, $templateName, $extensionKey)
    {
        // prepare own template
        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extensionKey . '/Resources/Private/Components/Backend/' . $templateName . '.html');
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }

    /**
     * translate Helper
     * @param $key
     */
    protected function translateKey($key)
    {
        return $GLOBALS['LANG']->sL('LLL:EXT:ns_basetheme/Resources/Private/Language/locallang_db.xlf:' . $key);
    }
}
