<?php

########################################################################
# Extension Manager/Repository config file for ext: "rteanchors"
#
# Auto generated 27-01-2009 18:05
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'RTE anchors',
	'description' => 'Adds a tab to RTE link wizard to link to anchors on the page',
	'category' => 'be',
	'author' => 'Dmitry Dulepov',
	'author_email' => 'info@netcreators.com',
	'shy' => '',
	'dependencies' => 'rtehtmlarea',
	'conflicts' => 'tkr_rteanchors',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'Netcreators BV',
	'version' => '0.1.0',
	'constraints' => array(
		'depends' => array(
			'rtehtmlarea' => '',
			'typo3' => '4.2.5-4.99.999',
		),
		'conflicts' => array(
			'tkr_rteanchors' => '',
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:6:{s:9:"ChangeLog";s:4:"7aa0";s:29:"class.tx_rteanchors_hooks.php";s:4:"12fe";s:33:"class.ux_t3lib_parsehtml_proc.php";s:4:"bcd4";s:12:"ext_icon.gif";s:4:"bfee";s:17:"ext_localconf.php";s:4:"aa8b";s:13:"locallang.xml";s:4:"08c8";}',
	'suggests' => array(
	),
);

?>