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
class Tx_Braintreecopier_Utility_IconUtility
{

    /**
     * Determines icon class for special page cases
     *
     * @param array $parameters array with record
     * @param string $ref Unused reference parameters
     * @return string Class name of page
     */
    public function getPageIcon($parameters, $ref)
    {
        $iconClass = '';
        $row = $parameters['row'];
        if (0 < (int) $row['content_from_pid']) {
            $iconClass = 'extensions-braintreecopier-show_content_from_page';
            if ((int) $row['nav_hide'] === 1) {
                $iconClass .= '_hide';
            }
        }
        return $iconClass;
    }
}
?>
