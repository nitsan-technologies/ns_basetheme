<?php
namespace NITSAN\NsBasetheme\Controller;

use NITSAN\NsBasetheme\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;
use TYPO3\CMS\Tstemplate\Controller\TypoScriptTemplateModuleController;

/***
 *
 * This file is part of the "[NITSAN] NS Bas" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * NsBasethemeModuleController
 */
class NsBasethemeModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * nsBasethemeRepository
     *
     * @var \NITSAN\NsBasetheme\Domain\Repository\NsBasethemeRepository
     * @inject
     */
    protected $nsBasethemeRepository = null;

    protected $templateService;

    protected $constantObj;

    protected $constants;

    protected $actions;

    /**
    * @var TypoScriptTemplateModuleController
    */
    protected $pObj;

    protected $contentObject = null;

    protected $pid = null;

    /**
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
        $this->templateService = GeneralUtility::makeInstance(ExtendedTemplateService::class);
        $this->constantObj = GeneralUtility::makeInstance(TypoScriptTemplateConstantEditorModuleFunctionController::class);
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();

        //GET CONSTANTs
        $this->constantObj->init($this->pObj);
        $this->constants = $this->constantObj->main();
    }

    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction()
    {
        //Assign variables values
        $assign = [
          'action' => 'dashboard'
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action generalSettings
     *
     * @return void
     */
    public function generalSettingsAction()
    {
        $this->view->assign('action', 'generalSettings');
        $this->view->assign('constant', $this->constants);
        $cat = 'ns_basetheme';
        if (GeneralUtility::_GP('cat')){
            $cat = GeneralUtility::_GP('cat');
        }
        $this->view->assign('cat', $cat);
    }

    /**
     * action seoSettings
     *
     * @return void
     */
    public function seoSettingsAction()
    {
        $this->view->assign('action', 'seoSettings');
        $this->view->assign('constant', $this->constants);
        $cat = 'ns_basetheme';
        if (GeneralUtility::_GP('cat')){
            $cat = GeneralUtility::_GP('cat');
        }
        $this->view->assign('cat', $cat);
    }

    /**
     * action gdprSettings
     *
     * @return void
     */
    public function gdprSettingsAction()
    {

        $this->view->assign('action', 'gdprSettings');
        $this->view->assign('constant', $this->constants);
        $cat = 'ns_basetheme';
        if (GeneralUtility::_GP('cat')){
            $cat = GeneralUtility::_GP('cat');
        }
        $this->view->assign('cat', $cat);
    }

    /**
     * action styleSettings
     *
     * @return void
     */
    public function styleSettingsAction()
    {
        $this->view->assign('action', 'styleSettings');
        $this->view->assign('constant', $this->constants);
        $cat = 'ns_basetheme';
        if (GeneralUtility::_GP('cat')){
            $cat = GeneralUtility::_GP('cat');
        }
        $this->view->assign('cat', $cat);
    }

    /**
     * action integrationSettings
     *
     * @return void
     */
    public function integrationSettingsAction()
    {
        $this->view->assign('action', 'integrationSettings');
        $this->view->assign('constant', $this->constants);
        $cat = 'ns_basetheme';
        if (GeneralUtility::_GP('cat')){
            $cat = GeneralUtility::_GP('cat');
        }
        $this->view->assign('cat', $cat);
    }

    /**
     * action saveConstant
     * @return void
     */
    public function saveConstantAction()
    {
        $param = GeneralUtility::_GP('tx_nsbasetheme_nitsan_nsbasethemensbasethememodule');
        $action = $param['currentAction'];
        $cat = $param['defCat'];
        $uri = $this->uriBuilder->reset()->setCreateAbsoluteUri(true)->setArguments(['tx_nsbasetheme_nitsan_nsbasethemensbasethememodule[action]' => $action, 'tx_nsbasetheme_nitsan_nsbasethemensbasethememodule[controller]' => 'NsBasethemeModule', 'cat' => $cat])->build();
        $this->redirectToUri($uri);
    }

    public function addConstantsConfiguration($constantForDb, $pid)
    {
        $getConstants = $this->nsBasethemeRepository->fetchConstants($pid)['constants'];
        $buildAdditionalConstant = $constantForDb;
        return $getConstants . $buildAdditionalConstant;
    }
}
