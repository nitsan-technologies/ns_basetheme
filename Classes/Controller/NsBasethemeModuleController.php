<?php
namespace NITSAN\NsBasetheme\Controller;

use NITSAN\NsBasetheme\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use NITSAN\NsBasetheme\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;

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

    protected $premiumExtensionData;

    protected $constants;

    protected $actions;

    /**
    * @var TypoScriptTemplateModuleController
    */
    protected $pObj;

    /*
    * ts
    * @var TypoScriptTemplateConstantEditorModuleFunctionController
    */
    protected $ts;

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
        $this->ts = GeneralUtility::makeInstance(TypoScriptTemplateModuleController::class);
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
        $sidebarUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=DashboardRightSidebar';
        $dashboardSupportUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=DashboardSupport';
        $generalFooterUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=GeneralFooter';
        $premiumExtensionUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=PremiumExtension';

        $this->nsBasethemeRepository->deleteOldApiData();
        $checkApiData = $this->nsBasethemeRepository->checkApiData();
        if (!$checkApiData) {
            $this->sidebarData = $this->nsBasethemeRepository->curlInitCall($sidebarUrl);
            $this->dashboardSupportData = $this->nsBasethemeRepository->curlInitCall($dashboardSupportUrl);
            $this->generalFooterData = $this->nsBasethemeRepository->curlInitCall($generalFooterUrl);
            $this->premiumExtensionData = $this->nsBasethemeRepository->curlInitCall($premiumExtensionUrl);

            $data = [
                'right_sidebar_html' => $this->sidebarData,
                'support_html'=> $this->dashboardSupportData,
                'footer_html' => $this->generalFooterData,
                'premuim_extension_html' => $this->premiumExtensionData,
                'extension_key' => 'ns_faq',
                'last_update' => date('Y-m-d')
            ];
            $this->nsBasethemeRepository->insertNewData($data);
        } else {
            $this->sidebarData = $checkApiData['right_sidebar_html'];
            $this->dashboardSupportData = $checkApiData['support_html'];
            $this->premiumExtensionData = $checkApiData['premuim_extension_html'];
        }

        //GET CONSTANTs
        $this->constantObj->init($this->pObj);
        $this->constants = $this->constantObj->main();
        $this->ts->init();
        $this->actions = $this->ts->main();
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
     * action basicSettings
     *
     * @return void
     */
    public function basicSettingsAction()
    {
        $this->view->assign('action', 'basicSettings');
        $this->view->assign('constant', $this->constants);
        $this->view->assign('constantAttrib', $this->actions);
    }

    /**
     * action saveConstant
     */
    public function saveConstantAction()
    {
        $this->constantObj->main();

//        $this->templateService->changed = 0;
//        $constant = $this->constantObj->getConstant();
//        $templateRow = $this->constantObj->getTemplateRow();
//        $rawConstant = $this->constantObj->getRawConstant();
//        $this->templateService->ext_procesInput(GeneralUtility::_POST(), [], $constant, $templateRow);
//
//        if ($this->templateService->changed) {
//            // Set the data to be saved
//            $recData = [];
//            $recData['sys_template'][$this->pid]['constants'] = implode($this->templateService->raw, LF);
//            // Create new  tce-object
//            $tce = GeneralUtility::makeInstance(DataHandler::class);
//            $tce->start($recData, []);
//            $tce->process_datamap();
//            // Clear the cache (note: currently only admin-users can clear the cache in tce_main.php)
//            $tce->clear_cacheCmd('all');
//            // re-read the template ...
//            // re-read the constants as they have changed
        ////            $this->initialize_editor($this->id, $template_uid);
//        }

        $returnAction = $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']; //get action name
//        $constantRawData = GeneralUtility::_GP('data');
//
//        foreach ($constantRawData as $key => $v){
//            if(is_array($v)){
//                $constantRawData[$key] = implode(',',$v);
//            }
//        }
//        $pid = GeneralUtility::_GP('id');
//        $constantForDb ='';
//        array_walk(
//            $constantRawData,
//            function ($item, $key) use (&$constantForDb) {
//                $constantForDb .= $key ." = " . preg_replace('/\n+/','',$item) ."\r\n";
//            }
//        );
//
//        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_template');
//        $newConstantsValue = $this->addConstantsConfiguration($constantForDb,$pid);
//        $query = $queryBuilder
//            ->update('sys_template')
//            ->where(
//                $queryBuilder->expr()->eq(
//                    'pid',
//                    $queryBuilder->createNamedParameter($pid)
//                )
//            )
//            ->set('constants', '');
//        $query = $queryBuilder->execute();
//
//        $query = $queryBuilder
//            ->update('sys_template')
//            ->where(
//                $queryBuilder->expr()->eq(
//                    'pid',
//                    $queryBuilder->createNamedParameter($pid)
//                )
//            )
//            ->set('constants', $newConstantsValue);
//        $query = $queryBuilder->execute();
        return false;
    }

    public function addConstantsConfiguration($constantForDb, $pid)
    {
        $getConstants = $this->nsBasethemeRepository->fetchConstants($pid)['constants'];
        $buildAdditionalConstant = $constantForDb;
        return $getConstants . $buildAdditionalConstant;
    }

    /**
     * action premiumExtension
     *
     * @return void
     */
    public function premiumExtensionAction()
    {
        $this->view->assign('action', 'premiumExtension');
        $this->view->assign('premiumExdata', $this->premiumExtensionData);
    }
}
