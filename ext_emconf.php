<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "listfeusers".
 *
 * Auto generated 18-07-2013 13:31
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'List frontend users(fe_users) in FE.',
	'description' => 'Three frontend plugins to generate list of frontend users (fe_users). Display list of users. Display logged user. Display map of users.',
	'category' => 'fe',
	'author' => 'Lebrija invest s.r.o.',
	'author_email' => 'petr.snobl@lebrija.cz',
	'shy' => '',
	'dependencies' => 'felogin',
	'conflicts' => '',
	'priority' => '',
	'module' => 'cm1',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'fe_groups,fe_users',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => 'Lebrija invest s.r.o.',
	'version' => '0.9.9',
	'constraints' => array(
		'depends' => array(
			'felogin' => '',
			'cms' => '',
			'typo3' => '6.2.0-7.2.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:22:{s:9:"ChangeLog";s:4:"835d";s:28:"class.tx_listfeusers_cm1.php";s:4:"f784";s:12:"ext_icon.gif";s:4:"1bdc";s:17:"ext_localconf.php";s:4:"06fe";s:14:"ext_tables.php";s:4:"67c2";s:13:"locallang.xml";s:4:"dc6a";s:16:"locallang_db.xml";s:4:"c6ab";s:10:"README.txt";s:4:"ee2d";s:13:"cm1/clear.gif";s:4:"cc11";s:15:"cm1/cm_icon.gif";s:4:"8074";s:12:"cm1/conf.php";s:4:"5e1a";s:13:"cm1/index.php";s:4:"f756";s:17:"cm1/locallang.xml";s:4:"8782";s:19:"doc/wizard_form.dat";s:4:"a24f";s:20:"doc/wizard_form.html";s:4:"b396";s:14:"pi1/ce_wiz.gif";s:4:"02b6";s:32:"pi1/class.tx_listfeusers_pi1.php";s:4:"0685";s:40:"pi1/class.tx_listfeusers_pi1_wizicon.php";s:4:"9d8a";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"1c3d";s:20:"static/constants.txt";s:4:"d41d";s:16:"static/setup.txt";s:4:"4633";}',
);

