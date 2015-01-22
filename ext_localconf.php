<?php
if (TYPO3_MODE === 'BE') {
    $tsIncludeConstants = "<INCLUDE_TYPOSCRIPT: source=FILE:EXT:$_EXTKEY/Configuration/TypoScript/constants.txt>";
    $tsIncludeSetup = "<INCLUDE_TYPOSCRIPT: source=FILE:EXT:$_EXTKEY/Configuration/TypoScript/setup.txt>";

    $version = class_exists('t3lib_div')
        ? t3lib_div::int_from_ver(TYPO3_version)
        : \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);

    // Gives the $icon array to the sprite manager
    if ($version < 6000000) {
        t3lib_extMgm::addTypoScript($_EXTKEY, 'constants', $tsIncludeConstants);
        t3lib_extMgm::addTypoScript($_EXTKEY, 'setup', $tsIncludeSetup);
    }
}
