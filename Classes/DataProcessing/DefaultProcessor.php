<?php
declare(strict_types=1);

namespace NITSAN\NsBasetheme\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\ContentObject\Exception\ContentRenderingException;

/**
 * Deafult processor
 */
class DefaultProcessor implements DataProcessorInterface
{
    /**
     * Process data
     *
     * @param ContentObjectRenderer $cObj The content object renderer, which contains data of the content element
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     * @throws ContentRenderingException
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $processedData['content'] = $this->getOptionsFromFlexFormData($processedData['data']);

        return $processedData;
    }

    /**
     * @param array $row
     * @return array
     */
    protected function getOptionsFromFlexFormData(array $row)
    {
        $options = [];
        if (!empty($row['pi_flexform'])) {
            // Note: xml2array is deprecated but still needed for parsing raw flexform XML structure
            // FlexFormService::convertFlexFormContentToArray() returns a flattened structure
            // which doesn't match the nested structure this code expects
            // @extensionScannerIgnoreLine
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
                                                if (isset($options[$optionKey]) && !is_array($options[$optionKey])) {
                                                    $options[$optionKey] = [];
                                                }

                                                if (isset($options[$optionKey][$subprekey]) && !is_array($options[$optionKey][$subprekey])) {
                                                    $options[$optionKey][$subprekey] = [];
                                                }

                                                $options[$optionKey][$subprekey][$subkey] = $value['vDEF'];
                                            }
                                        }
                                    }
                                }
                            } else {
                                $options[$optionKey] = isset($optionValue['vDEF']) && $optionValue['vDEF'] === '1' ? true : ($optionValue['vDEF'] ?? null);
                            }
                        }
                    }
                }
            }
        }

        return $options;
    }
}
