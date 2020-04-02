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

    protected $sidebarData;

    protected $dashboardSupportData;

    protected $generalFooterData;

    protected $aboutExtensionData;

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
        //Links for the All Dashboard VIEW from API...
        $sidebarUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_basetheme&blockName=DashboardRightSidebar';
        $dashboardSupportUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_basetheme&blockName=DashboardSupport';
        $generalFooterUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_basetheme&blockName=GeneralFooter';
        $aboutExtensionUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_basetheme&blockName=AboutExtension';

        $this->nsBasethemeRepository->deleteOldApiData();
        $checkApiData = $this->nsBasethemeRepository->checkApiData();
        if (!$checkApiData) {
            $this->sidebarData = $this->nsBasethemeRepository->curlInitCall($sidebarUrl);
            $this->dashboardSupportData = $this->nsBasethemeRepository->curlInitCall($dashboardSupportUrl);
            $this->generalFooterData = $this->nsBasethemeRepository->curlInitCall($generalFooterUrl);
            $this->aboutExtensionData = $this->nsBasethemeRepository->curlInitCall($aboutExtensionUrl);

            $data = [
                'right_sidebar_html' => $this->sidebarData,
                'support_html'=> $this->dashboardSupportData,
                'footer_html' => $this->generalFooterData,
                'premuim_extension_html' => $this->aboutExtensionData,
                'extension_key' => 'ns_faq',
                'last_update' => date('Y-m-d')
            ];
            $this->nsBasethemeRepository->insertNewData($data);
        } else {
            $this->sidebarData = $checkApiData['right_sidebar_html'];
            $this->dashboardSupportData = $checkApiData['support_html'];
            $this->aboutExtensionData = $checkApiData['premuim_extension_html'];
        }

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
          'action' => 'dashboard',
          'rightSide' => $this->sidebarData,
          'dashboardSupport' => $this->dashboardSupportData
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
    }

    /**
     * action saveConstant
     */
    public function saveConstantAction()
    {
        $this->constantObj->main();
        return false;
    }

    public function addConstantsConfiguration($constantForDb, $pid)
    {
        $getConstants = $this->nsBasethemeRepository->fetchConstants($pid)['constants'];
        $buildAdditionalConstant = $constantForDb;
        return $getConstants . $buildAdditionalConstant;
    }

    /**
     * action aboutExtension
     *
     * @return void
     */
    public function aboutExtensionAction()
    {
        $this->view->assign('action', 'aboutExtension');
        $this->view->assign('aboutExdata', $this->aboutExtensionData);
    }
}
