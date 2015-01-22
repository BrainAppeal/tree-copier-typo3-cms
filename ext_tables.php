<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {

	/**
	 * Registers a Backend Module
	 */
	Tx_Extbase_Utility_Extension::registerModule(
		$_EXTKEY,
		'web',	 // Make module a submodule of 'web'
		'braintreecopier',	// Submodule key
		'',						// Position
		array(
			'TreeCopier' => 'enter, confirm, execute',
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_braintreecopier.xml',
		)
	);
}

$version = class_exists('t3lib_div')
    ? t3lib_div::int_from_ver(TYPO3_version)
    : \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);

// Defines $icon array()
$pathToExtension = $version >= 6000000 ? \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('braintreecopier') : t3lib_extMgm::extRelPath('braintreecopier');

$icons = array(
    'show_content_from_page' => $pathToExtension .
    'Resources/Public/Icons/show_content_from_page.png',
    'show_content_from_page_hide' => $pathToExtension .
    'Resources/Public/images/icons/show_content_from_page_hide.png',
);
// Gives the $icon array to the sprite manager
if ($version >= 6000000) {
    \TYPO3\CMS\Backend\Sprite\SpriteManager::addSingleIcons($icons, 'braintreecopier');
} else {
    t3lib_SpriteManager::addSingleIcons($icons, 'braintreecopier');
}

// Register userfunc for page icon
$TCA['pages']['ctrl']['typeicon_classes']['userFunc'] =
'Tx_Braintreecopier_Utility_IconUtility->getPageIcon';

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Tree Copier');

?>
