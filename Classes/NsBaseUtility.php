<?php

namespace NITSAN\NsBasetheme;

use Symfony\Component\Config\ConfigCacheFactory;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * NsBaseUtility
 */
class NsBaseUtility {

    /**
     * getRootPageId
     *
     * @return void
     **/
    public function getChildThemeName() {
        $currentPageId = GeneralUtility::_GP('id');
        if(empty($currentPageId)) {
            $arrEditPage = GeneralUtility::_GP('edit');

            // If Edit Page
            if(isset($arrEditPage['pages'])) {
                $currentPageId = key($arrEditPage['pages']);
            }
            // If Add/Edit Content
            if(isset($arrEditPage['tt_content'])) {
                
                // Let's clear the Flush cache to re-generate ext_localconf
                $this->clearAll();

                $contentId = key($arrEditPage['tt_content']);
                $isNewElement = $arrEditPage['tt_content'][$contentId];
                if($isNewElement == 'new') {
                    $currentPageId = $contentId;
                }
                else {
                    $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('tt_content');
                    $statement = $queryBuilder
                        ->select('pid')
                        ->from('tt_content')
                        ->where(
                            $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($contentId))
                        )
                        ->execute();
                    $arrPages = $statement->fetch();
                    $currentPageId = $arrPages['pid'];
                }
            }
        }
        //echo $currentPageId;die;
        
        $rootPageId = 1;
        $arrMatchTheme = array();
        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('pages');
        $statement = $queryBuilder
            ->select('uid', 'pid')
            ->from('pages')
            ->execute();
        $arrPages = array();
        while ($row = $statement->fetch()) {
            $arrPages[] = $row;
        }

        $arrTree = $this->createPageTree($arrPages, 'uid', 'pid');
        $rootPageId = $this->getRootPageId($currentPageId, $arrTree);

        $queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)->getQueryBuilderForTable('sys_template');
        $statement = $queryBuilder
            ->select('uid', 'include_static_file')
            ->from('sys_template')
            ->where(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($rootPageId))
            )
            ->execute();
        $arrStaticFile = $statement->fetch();
        $needle = 'ns_theme_';
        if(!empty($arrStaticFile['include_static_file'])) {
            $matchTheme = preg_match_all('/\b(' . preg_quote($needle, '/') . '\w+)/', $arrStaticFile['include_static_file'], $arrMatchTheme);
            if(is_array($arrMatchTheme) && count($arrMatchTheme)) {
                $arrMatchTheme = $arrMatchTheme[0];
            }
            /*if (preg_match_all('/\b(' . preg_quote($needle, '/') . '\w+)/', $arrStaticFile['include_static_file'], $match)) {
                $arrMatchThemeName = array_values(array_diff($match[0], ['ns_theme_extend']));
            }
            if(isset($arrMatchThemeName)) {
                $arrThemeName = $arrMatchThemeName[0];
                if(isset($arrThemeName)) {
                    $themeName = $arrThemeName;
                }
            }*/
        }
        return $arrMatchTheme;
    }

    function createPageTree($results, $idField='id', $parentIdField='parent', $childrenField='children') {
        $hierarchy = array(); // -- Stores the final data
    
        $itemReferences = array(); // -- temporary array, storing references to all items in a single-dimention
    
        foreach ( $results as $item ) {
            $id       = $item[$idField];
            $parentId = $item[$parentIdField];
    
            if (isset($itemReferences[$parentId])) { // parent exists
                $itemReferences[$parentId][$childrenField][$id] = $item; // assign item to parent
                $itemReferences[$id] =& $itemReferences[$parentId][$childrenField][$id]; // reference parent's item in single-dimentional array
            } elseif (!$parentId || !isset($hierarchy[$parentId])) { // -- parent Id empty or does not exist. Add it to the root
                $hierarchy[$id] = $item;
                $itemReferences[$id] =& $hierarchy[$id];
            }
        }
    
        unset($results, $item, $id, $parentId);
    
        // -- Run through the root one more time. If any child got added before it's parent, fix it.
        foreach ( $hierarchy as $id => &$item ) {
            $parentId = $item[$parentIdField];
    
            if ( isset($itemReferences[$parentId] ) ) { // -- parent DOES exist
                $itemReferences[$parentId][$childrenField][$id] = $item; // -- assign it to the parent's list of children
                unset($hierarchy[$id]); // -- remove it from the root of the hierarchy
            }
        }
    
        unset($itemReferences, $id, $item, $parentId);
    
        return $hierarchy;
    }

    function getRootPageId($needle, $haystack, $currentKey = '') {
        foreach($haystack as $key=>$value) {
            if (is_array($value)) {
                $nextKey = $this->getRootPageId($needle,$value, $currentKey . '[' . $key . ']');
                if ($nextKey) {
                    return $key;
                    //return $nextKey;
                }
            }
            else if($value==$needle) {
                return $key;
                //return is_numeric($key) ? $currentKey . '[' .$key . ']' : $currentKey . '["' .$key . '"]';
            }
        }
        return false;
    }

    public function clearAll()
    {
        $cacheManager = new \TYPO3\CMS\Core\Cache\CacheManager();
        $cacheManager->setCacheConfigurations($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']);
        // Cache manager needs cache factory. cache factory injects itself to manager in __construct()
        $cacheManager->flushCaches();
    }
}