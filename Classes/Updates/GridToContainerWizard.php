<?php
namespace NITSAN\NsBasetheme\Updates;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\RepeatableInterface;

class GridToContainerWizard implements UpgradeWizardInterface, RepeatableInterface
{
    /**
     * Return the identifier for this wizard
     * This should be the same string as used in the ext_localconf class registration
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return 'nsBasetheme_gridToContainerWizard';
    }
    /**
     * Return the speaking name of this wizard
     *
     * @return string
     */
    public function getTitle(): string
    {
        return 'Grid to Container Migration';
    }

    /**
     * Return the description for this wizard
     *
     * @return string
     */
    public function getDescription(): string
    {
        return 'Run this wizard for migrate EXT:Gridelements to EXT:container';
    }

    /**
     * Is an update necessary?
     *
     * Is used to determine whether a wizard needs to be run.
     * Check if data for migration exists.
     *
     * @return bool Whether an update is required (TRUE) or not (FALSE)
     */
    public function updateNecessary(): bool
    {   $nsbasethemeUtility = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
        $installedTheme = $nsbasethemeUtility->getInstalledChildTheme();
        
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $elementCount = $queryBuilder->count('uid')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('gridelements_pi1', \PDO::PARAM_STR))
            )
            ->execute()->fetchColumn(0);

            return (bool)$elementCount && \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('container') && ($installedTheme[0] == 'ns_theme_t3karma' || $installedTheme[0] == 'ns_theme_bootstrap') ;
    }

    /**
     * Execute the update
     *
     * Called when a wizard reports that an update is necessary
     *
     * @return bool
     */
    public function executeUpdate(): bool
    {
        $nsbasethemeUtility = GeneralUtility::makeInstance(\NITSAN\NsBasetheme\NsBasethemeUtility::class);
        $installedTheme = $nsbasethemeUtility->getInstalledChildTheme();
        
    
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $queryBuilder = $connection->createQueryBuilder();
        $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));
        $statement = $queryBuilder->select('uid', 'tx_gridelements_backend_layout')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('CType', $queryBuilder->createNamedParameter('gridelements_pi1', \PDO::PARAM_STR)),
            )
            ->execute();
        while ($record = $statement->fetch()) {
                $containerCtype = ['ns_base_container', 'ns_base_container', 'ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols','ns_base_5Cols', 'ns_base_6Cols'];
                $gridsCtype = ['sectionGrid','nsBase1Col', 'nsBase2Col', 'nsBase3Col', 'nsBase4Col', 'nsBase5Col', 'nsBase6Col'];
         
            if($installedTheme[0] == 'ns_theme_t3karma'){
                $containerCtype = ['ns_base_container', 'ns_base_container', 'ns_base_container', 'ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols','ns_base_5Cols', 'ns_base_6Cols'];
                $gridsCtype = ['nsCustomContainer','nsKarma1Col', 'nsBaseContainer', 'nsBase2Col', 'nsBase3Col', 'nsBase4Col', 'nsBase5Col', 'nsBase6Col'];
            }

            if($installedTheme[0] == 'ns_theme_t3blogger'){
                $containerCtype = ['ns_base_container','ns_base_1Cols','ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols'];
                $gridsCtype = ['nsContainer','nsBase1Col','nsBase2Col', 'nsBase3Col', 'nsBase4Col'];
                
            }

            if($installedTheme[0] == 'ns_theme_digitalmarketing'){
                $containerCtype = ['ns_base_container', 'ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols'];
                $gridsCtype = ['nsBase1Col','nsBase2Col', 'nsBase3Col', 'nsBase4Col'];
           
            }
            if($installedTheme[0] == 'ns_theme_ngo'){
                $containerCtype = ['ns_base_container','ns_base_1Cols','ns_base_2Cols', 'ns_base_3Cols', 'ns_base_4Cols'];
                $gridsCtype = ['nsBase1Col','nsContainer','nsBase2Col', 'nsBase3Col', 'nsBase4Col'];
            }
            $cType = str_replace($gridsCtype, $containerCtype, $record['tx_gridelements_backend_layout']);
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('CType', $cType);
            $queryBuilder->execute();
        }

        $queryBuilder = $connection->createQueryBuilder();
        $statement = $queryBuilder->select('uid', 'tx_gridelements_columns', 'tx_gridelements_container')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('colPos', $queryBuilder->createNamedParameter('-1', \PDO::PARAM_STR)),
            )
            ->execute();

        while ($record = $statement->fetch()) {
            $colPos = $record['tx_gridelements_columns'] + 100;
            $queryBuilder = $connection->createQueryBuilder();
            $queryBuilder->update('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'uid',
                        $queryBuilder->createNamedParameter($record['uid'], \PDO::PARAM_INT)
                    )
                )
                ->set('colPos', $colPos)
                ->set('tx_gridelements_backend_layout', 0)
                ->set('tx_gridelements_children', 0)
                ->set('tx_gridelements_container', 0)
                ->set('tx_gridelements_columns', 0)
                ->set('tx_container_parent', $record['tx_gridelements_container']);
            $queryBuilder->execute();
        }

       
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('pages');
        $queryBuilder = $connection->createQueryBuilder();

        $result = $queryBuilder
                    ->select('*')
                    ->from('pages')
                    ->where(
                        $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter('1' , \PDO::PARAM_INT))
                    )
                    ->executeQuery()
                    ->fetch();

        $ts = $result['TSconfig']."\n".'tx_gridelements.setup >';
        $queryBuilder
            ->update('pages')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter('1' , \PDO::PARAM_INT))
            )
            ->set('TSconfig', $ts)
            ->execute();
        return true;
    }

    /**
     * Returns an array of class names of prerequisite classes
     *
     * This way a wizard can define dependencies like "database up-to-date" or
     * "reference index updated"
     *
     * @return string[]
     */
    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class
        ];
    }
}