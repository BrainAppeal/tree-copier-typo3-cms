<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "braintreecopier".
 *
 * Auto generated 17-06-2014 11:44
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Tree Copier',
	'description' => 'Create copies of a page tree without copying the content elements. Copies all subpages of a given source page to a destination page, sets the copied pages to \\"show content of {original} page\\". Source and target page id are configurable in a back-end module.',
	'category' => 'module',
	'author' => 'Brain Appeal GmbH',
	'author_email' => 'info@brain-appeal.com',
	'author_company' => 'Brain Appeal GmbH',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '1.0.3',
	'constraints' => array(
		'depends' => array(
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '4.5.0-6.2.99',
			'php' => '5.3-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:28:{s:12:"ext_icon.gif";s:4:"65ca";s:17:"ext_localconf.php";s:4:"baf4";s:14:"ext_tables.php";s:4:"257c";s:21:"ExtensionBuilder.json";s:4:"db86";s:43:"Classes/Controller/TreeCopierController.php";s:4:"7ec4";s:35:"Classes/Domain/Model/TreeCopier.php";s:4:"4fb0";s:31:"Classes/Utility/IconUtility.php";s:4:"4770";s:37:"Classes/Utility/TreeCopierUtility.php";s:4:"57a7";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"dd7c";s:32:"Configuration/TCA/TreeCopier.php";s:4:"a0fd";s:38:"Configuration/TypoScript/constants.txt";s:4:"344e";s:34:"Configuration/TypoScript/setup.txt";s:4:"c6a8";s:46:"Resources/Private/Backend/Layouts/Default.html";s:4:"48da";s:50:"Resources/Private/Backend/Partials/FormErrors.html";s:4:"3939";s:59:"Resources/Private/Backend/Templates/TreeCopier/Confirm.html";s:4:"f299";s:57:"Resources/Private/Backend/Templates/TreeCopier/Enter.html";s:4:"c105";s:59:"Resources/Private/Backend/Templates/TreeCopier/Execute.html";s:4:"cd38";s:40:"Resources/Private/Language/locallang.xml";s:4:"e951";s:56:"Resources/Private/Language/locallang_braintreecopier.xml";s:4:"9637";s:55:"Resources/Private/Language/locallang_csh_tt_content.xml";s:4:"f332";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"96be";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:49:"Resources/Public/Icons/show_content_from_page.png";s:4:"a65a";s:54:"Resources/Public/Icons/show_content_from_page_hide.png";s:4:"03c5";s:37:"Resources/Public/Icons/tt_content.gif";s:4:"4e5b";s:50:"Tests/Unit/Controller/TreeCopierControllerTest.php";s:4:"7337";s:42:"Tests/Unit/Domain/Model/TreeCopierTest.php";s:4:"ed68";s:14:"doc/manual.sxw";s:4:"219c";}',
);

?>