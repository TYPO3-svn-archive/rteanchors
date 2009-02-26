<?php
if (!defined('TYPO3_MODE')) {
	die(__FILE__ . ': no TYPO3_MODE!');
}

// A hook to add a tab for the "Anchor" generation
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ext/rtehtmlarea/mod3/class.tx_rtehtmlarea_browse_links.php']['browseLinksHook'][$_EXTKEY] =
		'EXT:rteanchors/class.tx_rteanchors_hooks.php:&tx_rteanchors_hooks';

// XCLASS for parsehtml_proc
$GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['t3lib/class.t3lib_parsehtml_proc.php'] = t3lib_extMgm::extPath($_EXTKEY).'class.ux_t3lib_parsehtml_proc.php';

?>