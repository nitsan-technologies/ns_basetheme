<?php

namespace NITSAN\NsBasetheme;

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
        $rootPageId = 1;
        $themeName = '';

        // Get root page of current page
        //$rootline = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Utility\RootlineUtility::class, $currentPageId);
        //$rootline = $rootLineUtility->get();
        //echo $rootline;die;

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

        if(!empty($arrStaticFile['include_static_file'])) {
            $matchThemeName = preg_match('/\bns_theme_\S*/', $arrStaticFile['include_static_file'], $arrMatchThemeName);
            if(isset($arrMatchThemeName)) {
                $arrThemeName = explode("/",$arrMatchThemeName[0]);
                if(isset($arrThemeName[0])) {
                    $themeName = $arrThemeName[0];
                }
            }
        }
        return $themeName;
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
}