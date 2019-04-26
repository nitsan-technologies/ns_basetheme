<?php
namespace NITSAN\site_default\Hooks;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\FlexFormService;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Hook to display verbose information about the felogin plugin
 *
 */
class CmsLayout implements PageLayoutViewDrawItemHookInterface {

	/**
	 * Preprocesses the preview rendering of a content element.
	 *
	 * @param   \TYPO3\CMS\Backend\View\PageLayoutView $parentObject Calling parent object
	 * @param   boolean $drawItem Whether to draw the item using the default functionalities
	 * @param   string $headerContent Header content
	 * @param   string $itemContent Item content
	 * @param   array $row Record row of tt_content
	 * @return  void
	 */
	public function preProcess(\TYPO3\CMS\Backend\View\PageLayoutView &$parentObject, &$drawItem, &$headerContent, &$itemContent, array &$row) {
		$content = $this->getOptionsFromFlexFormData($row);
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');

		// Get Components from ext_localconf.php
		$allComponents = constant('ALL_COMPONENTS');

		// Let's check if our components is going to be load in backned?
		if (in_array($row['CType'], $allComponents)) {
			$drawItem = false;
			$headerContent = '';

			// template
			$view = $this->getFluidTemplate($extKey, GeneralUtility::underscoredToUpperCamelCase($row['CType']));

			if (!empty($row['pi_flexform'])) {
				/** @var FlexFormService $flexFormService */
				$flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
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
	protected function getFluidTemplate($extKey, $templateName) {
		// prepare own template
		$fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:site_default/Resources/Private/Templates/Components/Backend/' . $templateName . '.html');
		$view = GeneralUtility::makeInstance(StandaloneView::class);
		$view->setTemplatePathAndFilename($fluidTemplateFile);
		return $view;
	}

	/**
	 * @param array $row
	 * @return array
	 */
	protected function getOptionsFromFlexFormData(array $row) {
		$options = [];
		$flexFormAsArray = GeneralUtility::xml2array($row['pi_flexform']);
		if (isset($flexFormAsArray['data']) && is_array($flexFormAsArray['data'])) {
			foreach ($flexFormAsArray['data'] as $base) {
				if (!empty($base['lDEF']) && is_array($base['lDEF'])) {
					foreach ($base['lDEF'] as $optionKey => $optionValue) {
						$optionParts = explode('.', $optionKey);
						$optionKey = array_pop($optionParts);
						if (isset($optionValue['el']) && is_array($optionValue['el'])) {
							foreach ($optionValue['el'] as $subprekey => $subArrayItem) {
								foreach ($subArrayItem as $subsubArrayItem) {
									if (isset($subsubArrayItem['el'])) {
										foreach ($subsubArrayItem['el'] as $subkey => $value) {
											if (!is_array($options[$optionKey])) {
												$options[$optionKey] = [];
											}

											if (!is_array($options[$optionKey][$subprekey])) {
												$options[$optionKey][$subprekey] = [];
											}

											$options[$optionKey][$subprekey][$subkey] = $value['vDEF'];
										}
									}
								}
							}
						} else {
							$options[$optionKey] = $optionValue['vDEF'] === '1' ? true : $optionValue['vDEF'];
						}
					}
				}
			}
		}
		return $options;
	}

	/**
	 * translate Helper
	 * @param $key
	 */
	protected function translateKey($key) {
		return $GLOBALS['LANG']->sL('LLL:EXT:site_default/Resources/Private/Language/locallang_db.xlf:' . $key);
	}
}
