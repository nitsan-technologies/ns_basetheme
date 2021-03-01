<?php

namespace NITSAN\NsBasetheme\Controller;

use NITSAN\NsBasetheme\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use TYPO3\CMS\Core\TypoScript\ExtendedTemplateService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
 * NsBasethemeModuleController.
 */
class NsBasethemeLicenseModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * nsBasethemeRepository.
     *
     * @var \NITSAN\NsBasetheme\Domain\Repository\NsBasethemeLicenseRepository
     * @inject
     */
    protected $nsBasethemeLicenseRepository = null;

    protected $templateService;

    protected $constantObj;

    protected $contentObject = null;

    protected $siteRoot = null;

    /**
     * Initializes this object.
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
        $this->templateService = GeneralUtility::makeInstance(ExtendedTemplateService::class);
        $this->constantObj = GeneralUtility::makeInstance(TypoScriptTemplateConstantEditorModuleFunctionController::class);
    }

    /**
     * Initialize Action.
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();
        if (version_compare(TYPO3_branch, '9.0', '>')) {
            $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
        } else {
            $this->siteRoot = PATH_site;
        }
    }

    /**
     * action list.
     *
     * @return void
     */
    public function listAction()
    {
        $extensions = $this->nsBasethemeLicenseRepository->fetchData();
        $this->view->assign('extensions', $extensions);
    }

    /**
     * action list.
     *
     * @return void
     */
    public function connectToServer()
    {
        $this->initializeAction();
        $allExtensionskey = scandir($this->siteRoot . '/typo3conf/ext/');
        foreach ($allExtensionskey as $key => $value) {
            $exp_key = explode('_theme', $value);
            if ($exp_key[0] == 'ns') {
                $allExtensions[] = $value;
            }
        }
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $nsBasethemeLicenseRepository = $this->objectManager->get(\NITSAN\NsBasetheme\Domain\Repository\NsBasethemeLicenseRepository::class);
        foreach ($allExtensions as $extension) {
            $extData = $nsBasethemeLicenseRepository->fetchData($extension);
            if (empty($extData)) {
                $licenseData = $this->fetchLicense('ns_key=' . $extension);
                if ($licenseData->status) {
                    $disableExtensions[] = $extension;
                    $extFolder = $this->siteRoot . '/typo3conf/ext/' . $extension . '/';
                    if (file_exists($extFolder . 'ext_tables.php')) {
                        rename($extFolder . 'ext_tables.php', $extFolder . 'copy_ext_tables.txt');
                    }
                    if (file_exists($extFolder . 'Configuration/TCA/Overrides/sys_template.php')) {
                        rename($extFolder . 'Configuration/TCA/Overrides/sys_template.php', $extFolder . 'Configuration/TCA/Overrides/copy_sys_template.txt');
                    }
                }
            } else {
                $licenseData = $this->fetchLicense('ns_license=' . $extData[0]['license_key']);
                if ($licenseData->status) {
                    $nsBasethemeLicenseRepository->updateData($licenseData);
                } elseif (!$licenseData->status) {
                    $disableExtensions[] = $extension;
                    $extFolder = $this->siteRoot . '/typo3conf/ext/' . $extension . '/';
                    if (file_exists($extFolder . 'ext_tables.php')) {
                        rename($extFolder . 'ext_tables.php', $extFolder . 'copy_ext_tables.txt');
                    }
                    if (file_exists($extFolder . 'Configuration/TCA/Overrides/sys_template.php')) {
                        rename($extFolder . 'Configuration/TCA/Overrides/sys_template.php', $extFolder . 'Configuration/TCA/Overrides/copy_sys_template.txt');
                    }
                }
            }
        }
        $disableExtensions = implode(',', $disableExtensions);
        setcookie('NsLicense', $disableExtensions, time() + 3600, '/', '', 0);
    }

    /**
     * action list.
     *
     * @return void
     */
    public function updateAction()
    {
        $params = $this->request->getArguments();
        if (isset($params['extension']['license_key']) && $params['extension']['license_key'] != '') {
            $souceFolder = $this->siteRoot . 'typo3conf/ext/' . $params['extension']['extension_key'];
            if (is_dir($souceFolder)) {
                $uploadFolder = $this->siteRoot . 'uploads/ns_basetheme/' . $params['extension']['extension_key'] . '/' . $params['extension']['version'];
                try {
                    GeneralUtility::rmdir($uploadFolder, true);
                    GeneralUtility::mkdir_deep($uploadFolder);
                    GeneralUtility::copyDirectory($souceFolder, $uploadFolder);
                } catch (\Exception $e) {
                    $this->addFlashMessage($e->getMessage(), 'Extension not updated', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                    $this->redirect('list');
                }
            }
            $params['extension']['license'] = $params['extension']['license_key'];
            $params['extension']['overwrite'] = true;
            $this->downloadExtension($params['extension']);
        } else {
            $this->addFlashMessage('The license key is not available or entered the wrong license key.', 'WARNING', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        }
        $this->redirect('list');
    }

    /**
     * action activation.
     *
     * @return void
     */
    public function activationAction()
    {
        $params = $this->request->getArguments();
        if (isset($params['license']) && $params['license'] != '') {
            $this->downloadExtension($params);
        } else {
            $this->addFlashMessage('The license key is not available or entered the wrong license key.', 'WARNING', \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        }
        // return true;
        $this->redirect('list');
    }

    /**
     * action activation
     * params array $params.
     *
     * @return void
     */
    public function downloadExtension($params = null)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        if (isset($params['license']) && $params['license'] != '') {
            $licenseData = $this->fetchLicense('ns_license=' . $params['license']);
            if ($licenseData->status) {
                if ($_COOKIE['NsLicense'] != '') {
                    $disableExtensions = explode(',', $_COOKIE['NsLicense']);
                    $key = array_search($licenseData->extension_key, $disableExtensions);
                    if ($key) {
                        unset($disableExtensions[$key]);
                        $disableExtensions = implode(',', $disableExtensions);
                        setcookie('NsLicense', $disableExtensions, time() + 3600, '/', '', 0);
                    }
                }
                $isAvailable = $this->nsBasethemeLicenseRepository->fetchData($licenseData->extension_key);
                if ($isAvailable && $params['overwrite'] == 1) {
                    $ltsext = end($licenseData->extension_download_url);
                    $extKey = $licenseData->extension_key . '.zip';
                    $extKeyPath = $this->siteRoot . 'typo3temp/' . $extKey;
                    $this->downloadZipFile($ltsext, $licenseData->license_key, $extKeyPath);
                    $this->uploadExtension = $objectManager->get(\TYPO3\CMS\Extensionmanager\Controller\UploadExtensionFileController::class);
                    try {
                        $this->uploadExtension->extractExtensionFromFile($extKeyPath, $extKey, ($params['overwrite'] ? true : false));
                        unlink($extKeyPath);
                    } catch (\Exception $e) {
                        $this->addFlashMessage($e->getMessage(), $licenseData->extension_key, \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                        $this->redirect('list');
                    }
                    $this->nsBasethemeLicenseRepository->updateData($licenseData, 1);
                } elseif (!$isAvailable) {
                    $ltsext = end($licenseData->extension_download_url);
                    $extKey = $licenseData->extension_key . '.zip';
                    $extKeyPath = $this->siteRoot . 'typo3temp/' . $extKey;
                    $this->downloadZipFile($ltsext, $licenseData->license_key, $extKeyPath);
                    $this->uploadExtension = $objectManager->get(\TYPO3\CMS\Extensionmanager\Controller\UploadExtensionFileController::class);
                    try {
                        $this->uploadExtension->extractExtensionFromFile($extKeyPath, $extKey, ($params['overwrite'] ? true : false));
                        unlink($extKeyPath);
                    } catch (\Exception $e) {
                        $this->addFlashMessage($e->getMessage(), $licenseData->extension_key, \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                        $this->redirect('list');
                    }
                    $this->nsBasethemeLicenseRepository->insertNewData($licenseData);
                } else {
                    $this->addFlashMessage('The extension is already available. If you want to install it, then select the overwrite option.', $licenseData->extension_key, \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
                    $this->redirect('list');
                }
                $this->addFlashMessage('The Extension is downloaded successfully, now you can activate.', $licenseData->extension_key, \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);
                $this->redirect('list');
            }
        }
        // return to list;
        $this->addFlashMessage('The license key is not available or entered the wrong license key.', $params['license'], \TYPO3\CMS\Core\Messaging\AbstractMessage::WARNING);
        $this->redirect('list');
    }

    public function fetchLicense($license)
    {
        $url = 'https://composer-t3terminal.ddev.site/API/GetComposerDetails.php?' . $license;
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        $response = curl_exec($curl);
        if (!$response) {
            echo 'Error :- ' . curl_error($curl);
        }
        curl_close($curl);

        return json_decode($response);
    }

    public function downloadZipFile($extensionDownloadUrl, $license, $extKeyPath)
    {
        $authorization = 'Basic ' . base64_encode('admin:' . $license);
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $extensionDownloadUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => [
            'Authorization: ' . $authorization,
          ],
        ]);
        $response = curl_exec($curl);
        if (!$response) {
            echo 'Error :- ' . curl_error($curl);
        }
        curl_close($curl);
        file_put_contents($extKeyPath, $response);
    }
}
