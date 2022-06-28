<?php
namespace NITSAN\NsBasetheme\Hooks;

use TYPO3\CMS\Backend\Template\Components\Buttons\InputButton;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Add an extra save and close button at the end
 *
 * Class SaveButtonHook
 */
class SaveCloseHook
{
    /**
     * @param array $params
     * @param $buttonBar
     * @return array
     */
    public function addSaveCloseButton($params, &$buttonBar)
    {
       
        $buttons = $params['buttons'];
        $saveButton = [];
 
        if (!empty($buttons['left'])) {
            if(is_array($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT])){
                $buttonAvail = array_key_exists(2, $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT]);
            }
        }
        if (!empty($buttonAvail)) {
            $saveButton = $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][2][0];
        }
        
        if ($saveButton instanceof InputButton) {
            $iconFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconFactory::class);

            $saveCloseButton = $buttonBar->makeInputButton()
            ->setName('_saveandclosedok')
            ->setValue('1')
            ->setForm($saveButton->getForm())
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:rm.saveCloseDoc'))
            ->setIcon($iconFactory->getIcon('actions-document-save-close', \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL))
            ->setShowLabelText(true);
            $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][11][] = $saveCloseButton;
        }
        return $buttons;
    }

    /**
     * @param array $params
     * @param $buttonBar
     * @return array
     */
    public function addSaveShowButton($params, &$buttonBar)
    { 
        $buttons = $params['buttons'];
        $saveButton = [];
        if (!empty($buttons['left'])) {
            if(is_array($buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT])){
                $buttonAvail = array_key_exists(2, $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT]);
            }
        }
        if (!empty($buttonAvail)) {
            $saveButton = $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][2][0];
        }
        if ($saveButton instanceof InputButton) {
            $iconFactory = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconFactory::class);

            $saveShowButton = $buttonBar->makeInputButton()
            ->setName('_savedokview')
            ->setValue('1')
            ->setForm($saveButton->getForm())
            ->setTitle($this->getLanguageService()->sL('LLL:EXT:core/Resources/Private/Language/locallang_core.xlf:rm.saveDocShow'))
            ->setIcon($iconFactory->getIcon('actions-document-save-view', \TYPO3\CMS\Core\Imaging\Icon::SIZE_SMALL))
            ->setShowLabelText(true);
            $buttons[\TYPO3\CMS\Backend\Template\Components\ButtonBar::BUTTON_POSITION_LEFT][12][] = $saveShowButton;
        }
        return $buttons;
    }

    /**
     * Returns the language service
     * @return LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
