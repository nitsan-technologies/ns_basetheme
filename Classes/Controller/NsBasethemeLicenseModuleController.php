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
 * NsBasethemeModuleController
 */
class NsBasethemeLicenseModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * nsBasethemeRepository
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
        if (version_compare(TYPO3_branch, '9.0', '>')) {
            $this->siteRoot = \TYPO3\CMS\Core\Core\Environment::getPublicPath() . '/';
        } else {
            $this->siteRoot = PATH_site;
        }
    }

    /**
     * action list
     * @return void
     */
    public function listAction()
    {
        $extensions = $this->nsBasethemeLicenseRepository->fetchData();
        $this->view->assign('extensions', $extensions);
    }

    /**
     * action list
     * @return void
     */
    public function connectToServer()
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $nsBasethemeLicenseRepository = $objectManager->get(\NITSAN\NsBasetheme\Domain\Repository\NsBasethemeLicenseRepository::class);
        $extensions = $nsBasethemeLicenseRepository->fetchData();
        foreach ($extensions as $extension) {
            $licenseData = $this->fetchLicense($extension['license_key']);
            $nsBasethemeLicenseRepository->updateData($licenseData);
        }
    }

    /**
     * action list
     * @return void
     */
    public function updateAction()
    {
        $params = $this->request->getArguments();
        if (isset($params['extension']['license_key']) && $params['extension']['license_key'] != '') {
            $uploadFolder = $this->siteRoot . 'uploads/ns_basetheme/' . $params['extension']['extension_key'] . '/' . $params['extension']['version'];
            try {
                \TYPO3\CMS\Core\Utility\GeneralUtility::rmdir($uploadFolder, true);
                \TYPO3\CMS\Core\Utility\GeneralUtility::mkdir_deep($uploadFolder);
                rename($this->siteRoot . 'typo3conf/ext/' . $params['extension']['extension_key'], $uploadFolder);
            } catch (\Exception $e) {
                $this->addFlashMessage($e->getMessage(), 'Extension not updated', \TYPO3\CMS\Core\Messaging\AbstractMessage::ERROR);
                $this->redirect('list');
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
     * action activation
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
     * params array $params
     * @return void
     */
    public function downloadExtension($params = null)
    {
        $objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        if (isset($params['license']) && $params['license'] != '') {
            $licenseData = $this->fetchLicense($params['license']);
            if ($licenseData->status) {
                $isAvailable = $this->nsBasethemeLicenseRepository->fetchData($licenseData->extension_key);
                if ($isAvailable && $params['overwrite'] == 1) {
                    $ltsext = end($licenseData->extension_download_url);
                    $this->nsBasethemeLicenseRepository->updateData($licenseData, 1);
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
                } elseif (!$isAvailable) {
                    $ltsext = end($licenseData->extension_download_url);
                    $this->nsBasethemeLicenseRepository->insertNewData($licenseData);
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
        $url = 'https://composer-t3terminal.ddev.site/API/GetComposerDetails.php?ns_license=' . $license;
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
            'Authorization: ' . $authorization
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
