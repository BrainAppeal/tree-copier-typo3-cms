<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Brain Appeal <info@brain-appeal.com>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
/**
 * Utility to copy a tree segment from a source page to a destination page.
 *
 * @author	Brain Appeal <info@brain-appeal.com>
 * @package	TYPO3
 * @subpackage	tx_braintreecopier
 */
class Tx_Braintreecopier_Utility_TreeCopierUtility
{

    /**
     * The results to display to the user.
     *
     * @var array
     */
    protected $results = array('error' => '');

    /**
     * Uids of selected languages
     *
     * @var array
     */
    protected $selectedLanguages = array();

    /**
     * Call custom PHP-scripts
     *
     * @param int $source
     * @param int $destination
     * @param array $selectedLanguages Uids.
     * @return array The results of the copy operation
     */
    public function main($source, $destination, $selectedLanguages)
    {
        $this->results = array();
        $this->selectedLanguages = $selectedLanguages;
        $newUids = array(); // Pass it by reference.
        $newPagesOriginalShortcuts = array(); // Pass it by reference.
        $oldPagesNewPages = array(); // Pass it by reference.
        $successCopy = $this->copyPageTree($source, $destination, $newUids, $newPagesOriginalShortcuts, $oldPagesNewPages);

        if (!$successCopy) {
            $this->results['error'] = 'error_page_copy';
            return $this->results;
        }

        $this->results['pagesCopied'] = count($newUids);
        $numberShortcuts = 0; // Pass it by reference.
        $successUpdate = $this->updateShortcuts($newUids, $numberShortcuts, $newPagesOriginalShortcuts, $oldPagesNewPages);

        if ($successUpdate) {
            $this->results['shortcutsUpdated'] = $numberShortcuts;
        } else {
            $this->results['error'] = 'error_shortcut_update';
        }

        return $this->results;
    }

    /**
     * Detect language uids of all subpages of page number $searchPid
     *
     * @param int $searchPid
     * @return array $langUids The used language uids
     */
    public function detectLanguagesOfPages($searchPid = 1)
    {
        $languages = array();

        $childPagesUnfiltered = array();
        $this->getChildPagesRecursive($searchPid, $childPagesUnfiltered);
        $childPages = array_unique($childPagesUnfiltered);
        if (empty($childPages)) {
            return $languages;
        }

        $langUids = $this->getLangUids($childPages);
        if (empty($langUids)) {
            return $languages;
        }

        $select_fields = 'uid, title';
        $table = 'sys_language';
        $where = 'uid IN (' . implode(',', $langUids) . ')';
        $resource = $this->execSelect($select_fields, $table, $where);

        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {
                $languages[(int) $row['uid']] = $row['title'];
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($resource);
        }
        return $languages;
    }

    /**
     * Copy translations of a page (selected languages only)
     *
     * @param int $uid
     * @param int $uidOfInsert
     */
    protected function copyPageTranslations($uid, $uidOfInsert)
    {
        $select_fields = '*';
        $table = 'pages_language_overlay';
        $where = 'pid = ' . (int) $uid . ' AND sys_language_uid IN ('
            . implode(',', $this->selectedLanguages) . ')';
        $resource = $this->execSelect($select_fields, $table, $where);

        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {
                array_shift($row); // Drop first field, which is 'uid'.
                $row['pid'] = (int) $uidOfInsert;
                $row['tstamp'] = time();
                $row['crdate'] = $row['tstamp'];

                $no_quote_fields = FALSE;
                $GLOBALS["TYPO3_DB"]->exec_INSERTquery($table, $row, $no_quote_fields);
            }
        }
    }

    /**
     * Copy the page tree recursively
     *
     * @param int $searchPid Where to look for pages. Standard: root page.
     * @param int $newPid Pid of newly inserted row.
     * @param array $newUids New pages
     * @param array $newPagesOriginalShortcuts
     *   Key: New page id; Val: Old shortcut target.
     * @param array $oldPagesNewPages Key: Old page id; Val: New page id.
     * @return boolean/int False if there was an SQL error,
     * or number of inserts.
     */
    protected function copyPageTree($searchPid = 1, $newPid = NULL, &$newUids = array(), &$newPagesOriginalShortcuts = array(), &$oldPagesNewPages = array())
    {
        if ($newPid === NULL) {
            return false;
        }

        $select_fields = '*';
        $table = 'pages';
        $where = 'pid = ' . $searchPid . ' AND deleted = 0';
        $resource = $this->execSelect($select_fields, $table, $where);

        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {

                // Adapt values
                $uid = $row['uid'];
                array_shift($row); // Drop first field, which is 'uid'.
                $row['pid'] = $newPid;
                $row['content_from_pid'] = $uid;

                $no_quote_fields = FALSE;
                $GLOBALS["TYPO3_DB"]->exec_INSERTquery($table, $row, $no_quote_fields);
                $uidOfInsert = $GLOBALS["TYPO3_DB"]->sql_insert_id();

                // Recursively create child pages
                $newUids[] = $uidOfInsert;
                $newPagesOriginalShortcuts[$uidOfInsert] = $row['shortcut'];
                $oldPagesNewPages[$uid] = $uidOfInsert;
                $this->copyPageTranslations($uid, $uidOfInsert);
                $this->copyPageTree($uid, $uidOfInsert, $newUids);
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($resource);
            return true;
        }
        return false;
    }

    /**
     * Execute a select query on tt_content
     *
     * @param array $select_fields
     * @param string $table
     * @param string $where
     * @param string $orderBy
     * @param string $groupBy
     * @param string $limit
     * @return boolean Success
     */
    protected function execSelect($select_fields, $table, $where, $orderBy = '', $groupBy = '', $limit = '10000')
    {
        if (empty($orderBy)) {
            $orderBy = $select_fields == '*' ? '' : $select_fields;
        }
        $resource = $GLOBALS["TYPO3_DB"]->exec_SELECTquery($select_fields, $table, $where, $groupBy, $orderBy, $limit);
        return $resource;
    }

    /**
     * Execute an update query on tt_content
     *
     * @param string $table
     * @param string $where
     * @param array $fields_values
     */
    protected function execUpdate($table, $where, $fields_values)
    {
        $no_quote_fields = FALSE;
        $GLOBALS["TYPO3_DB"]->exec_UPDATEquery($table, $where, $fields_values, $no_quote_fields);
    }

    /**
     * Get whole tree of pages
     *
     * @param int $searchPid
     * @param array $pages Found pages are filled in here
     * @return boolean Success.
     */
    protected function getChildPagesRecursive($searchPid = 1, &$pages = array())
    {
        $select_fields = 'uid';
        $table = 'pages';
        $where = 'pid = ' . $searchPid . ' AND deleted = 0';
        $resource = $this->execSelect($select_fields, $table, $where);

        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {
                $pages[] = (int) $row['uid'];
                $this->getChildPagesRecursive($row['uid'], $pages);
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($resource);
            return true;
        }
        return false;
    }

    /**
     * Get sys_language_uid values of any of the $pages
     *
     * @param array $pages
     * @return array
     */
    protected function getLangUids($pages)
    {
        $select_fields = 'pid, sys_language_uid';
        $table = 'pages_language_overlay';
        $where = 'pid IN (' . implode(',', $pages) . ') AND deleted = 0';
        $resource = $this->execSelect($select_fields, $table, $where);

        $langUids = array();
        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {
                $langUids[] = (int) $row['sys_language_uid'];
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($resource);
        }
        return array_unique($langUids);
    }

    /**
     * Clean up the results and return them
     *
     * @return array
     */
    protected function returnResults()
    {
        $true = Tx_Extbase_Utility_Localization::translate(
                'TRUE', 'Braintreecopier');
        $false = Tx_Extbase_Utility_Localization::translate(
                'FALSE', 'Braintreecopier');

        if (empty($this->results['error'])) {
            unset($this->results['error']);
        }
        foreach ($this->results as &$val) {
            if (is_bool($val)) {
                $val = $val ? $true : $false;
            }
        }

        return $this->results;
    }

    /**
     * Update the shortcuts of these pages; to be called after copyPageTree().
     *
     * @param array $uidsToSearch
     * @param int $numberShortcuts
     * @param array $newPagesOriginalShortcuts
     *   Key: New page id; Val: Old shortcut target.
     * @param array $oldPagesNewPages Key: Old page id; Val: New page id.
     * @return boolean Success.
     */
    protected function updateShortcuts($uidsToSearch = array(), &$numberShortcuts = 0, $newPagesOriginalShortcuts = array(), $oldPagesNewPages = array())
    {
        if (empty($uidsToSearch)) {
            return true;
        }
        $select_fields = 'uid, shortcut';
        $table = 'pages';
        $where = 'uid IN (' . implode(', ', $uidsToSearch) . ')';
        $where .= ' AND shortcut > 0 AND deleted = 0';
        $resource = $this->execSelect($select_fields, $table, $where);

        if ($resource !== false) {
            while ($row = $GLOBALS["TYPO3_DB"]->sql_fetch_assoc($resource)) {

                // Find new target page for shortcut
                $origShortcut = isset($newPagesOriginalShortcuts[$row['uid']]) ?
                    $newPagesOriginalShortcuts[$row['uid']] : 0;
                if (empty($origShortcut)) {
                    continue;
                }

                if (!isset($oldPagesNewPages[$origShortcut])) {
                    // Shortcut points to a page that was NOT
                    // just copied by this utility.
                    // Therefore the shortcut in the new tree should
                    // point to the same page as the one in the old tree.
                    continue;
                }
                $newShortcut = $oldPagesNewPages[$origShortcut];

                $where = 'uid = ' . $row['uid'];
                $fields_values = array('shortcut' => $newShortcut);
                $this->execUpdate($table, $where, $fields_values);
                ++$numberShortcuts;
            }
            $GLOBALS["TYPO3_DB"]->sql_free_result($resource);
            return true;
        }
        return false;
    }
}
?>
