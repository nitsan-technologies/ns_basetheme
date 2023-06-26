<?php

declare(strict_types=1);

namespace NITSAN\NsBasetheme\EventListener;

use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Messaging\FlashMessageService;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Service\FlexFormService;
use TYPO3\CMS\Backend\Utility\BackendUtility;

final class PageLayoutView
{
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {

        $allComponents = constant('ALL_COMPONENTS');
        $rowFlag = $extensionKey = '';
        $row = $event->getRecord();
        $flexFormService = '';
        $extKey ='';
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
            //Get image from tt_content table
            $images = BackendUtility::resolveFileReferences('tt_content', 'image', $row);
                // template
                $view = $this->getFluidTemplateComponents
                (
                    $extKey,
                    GeneralUtility::underscoredToUpperCamelCase($row['CType']),
                    $extensionKey
                );


            if (!empty($row['pi_flexform'])) {
                $flexFormService = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Service\FlexFormService::class);
            }

            $view->assign('flexformData', $flexFormService->convertFlexFormContentToArray($row['pi_flexform']));
            $view->assign('data', $row);
            $view->assign('image', $images);

                   
            // return the preview
            $event->setPreviewContent($view->render());
        
        }
    }


    /**
     * @param string $extKey
     * 
     
     * @param string $templateName
     * @return \TYPO3\CMS\Fluid\View\StandaloneView the fluid template
     */
    protected function getFluidTemplateComponents($extKey, $templateName)
    {
        // prepare own template
        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Components/Backend/' . $templateName . '.html');
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }
}
