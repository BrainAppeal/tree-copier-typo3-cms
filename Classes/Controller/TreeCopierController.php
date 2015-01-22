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
class Tx_Braintreecopier_Controller_TreeCopierController extends Tx_Extbase_MVC_Controller_ActionController
{

    /**
     * action confirm
     *
     * @param int $source
     * @param int $destination
     * @return void
     */
    public function confirmAction($source, $destination)
    {
        $source = (int) $source;
        $destination = (int) $destination;
        if (empty($source) || empty($destination)) {
            $args = array('source' => $source, 'destination' => $destination);
            $msg = Tx_Extbase_Utility_Localization::translate(
                    'errormsg_source_destination', 'Braintreecopier');
            $this->flashMessageContainer->add($msg, null, t3lib_Flashmessage::ERROR);
            $this->redirect('enter', null, null, $args);
        }

        $util = $this->objectManager
            ->create('Tx_Braintreecopier_Utility_TreeCopierUtility');
        $languages = $util->detectLanguagesOfPages((int) $source);

        $this->view->assign('source', $source);
        $this->view->assign('destination', $destination);
        $this->view->assign('languages', $languages);
        $this->view->assign('selectedLanguages', array());
    }

    /**
     * action enter
     *
     * @param int $source
     * @param int $destination
     * @return void
     */
    public function enterAction($source = 0, $destination = 0)
    {

        // For some reason $source and $destination are sometimes
        // NULL instead of 0, despite validation and defaults.
        if (empty($source)) {
            $source = 0;
        }
        if (empty($destination)) {
            $destination = 0;
        }
        $this->view->assign('source', $source);
        $this->view->assign('destination', $destination);
    }

    /**
     * action enter
     *
     * @param int $source
     * @param int $destination
     * @return void
     */
    public function executeAction($source, $destination)
    {
        // Change button was clicked
        $reqArgs = $this->request->getArguments();
        if (array_key_exists('change', $reqArgs) || empty($source) || empty($destination)
        ) {
            $args = array('source' => $source, 'destination' => $destination);
            $this->redirect('enter', null, null, $args);
        }

        // Currently there seem to be bugs with multiselect in Fluid 6.1.x
        // So we get the selection this way.
        $selectedLanguages = array();
        if (array_key_exists('selectedLanguages', $reqArgs)) {
            foreach($reqArgs['selectedLanguages'] as $lang) {
                if (!empty($lang)) {
                    $selectedLanguages[] = (int) $lang;
                }
            }
        }

        $util = $this->objectManager
            ->create('Tx_Braintreecopier_Utility_TreeCopierUtility');
        $results = $util->main((int) $source, (int) $destination,
            $selectedLanguages);
        $error = null;
        if (isset($results['error'])) {
            $error = $results['error'];
            unset($results['error']);
        }

        // Update page tree so the changes are visible as soon
        // as the user switches to Page or List module.
        t3lib_BEfunc::openPageTree($destination, false);
        t3lib_BEfunc::setUpdateSignal('updatePageTree', $destination);

        $this->view->assign('destination', $destination);
        $this->view->assign('error', $error);
        $this->view->assign('results', $results);
        $this->view->assign('source', $source);
    }
}
?>