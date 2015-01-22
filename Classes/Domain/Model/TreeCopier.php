<?php
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @package braintreecopier
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Braintreecopier_Domain_Model_TreeCopier extends Tx_Extbase_DomainObject_AbstractValueObject
{

    /**
     * @var int
     * @validate Integer
     */
    protected $destination;

    /**
     * @var int
     * @validate Integer
     */
    protected $source;

    /**
     * Setter for destination
     *
     * @param Integer $destination
     * @return void
     */
    public function setDestination($destination)
    {
        $this->destination = (int) $destination;
    }

    /**
     * Getter for destination
     *
     * @return int
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Setter for source
     *
     * @param Integer $source
     * @return void
     */
    public function setSource($source)
    {
        $this->source = (int) $source;
    }

    /**
     * Getter for source
     *
     * @return int
     */
    public function getSource()
    {
        return $this->source;
    }
}
?>