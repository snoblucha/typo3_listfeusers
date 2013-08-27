<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1'] = 'layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:listfeusers/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:listfeusers/locallang_db.xml:tt_content.list_type_pi2',
	$_EXTKEY . '_pi2',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.gif'
),'list_type');

t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:listfeusers/pi1/flexform_ds.xml');


if (TYPO3_MODE === 'BE') {
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_listfeusers_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'pi1/class.tx_listfeusers_pi1_wizicon.php';
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_listfeusers_pi2_wizicon'] = t3lib_extMgm::extPath($_EXTKEY) . 'pi2/class.tx_listfeusers_pi2_wizicon.php';
}

//t3lib_extMgm::addStaticFile($_EXTKEY,'static//', '');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi1/static/','List frontend users');
t3lib_extMgm::addStaticFile($_EXTKEY,'pi2/static/','List single frontend user');


if (TYPO3_MODE === 'BE')	{
	$GLOBALS['TBE_MODULES_EXT']['xMOD_alt_clickmenu']['extendCMclasses'][] = array(
		'name' => 'tx_listfeusers_cm1',
		'path' => t3lib_extMgm::extPath($_EXTKEY).'class.tx_listfeusers_cm1.php'
	);

        $TCA['fe_groups']['columns']['fe_pid'] = array(
                'label' => 'LLL:EXT:listfeusers/locallang.xml:fe_pid',
                //'type' => 'select',
                'config' => array(
                   'type' => 'select',
                    'foreign_table' => 'pages',
                    'foreign_table_where' => "AND pages.title LIKE '%###PAGE_TSCONFIG_STR###%'",
                    'size' => 1,
                    'minitems' => 0,
                    'maxitems' => 1
                ),
        );

        t3lib_extMgm::addToAllTCAtypes('fe_groups', 'fe_pid;;;;1-1-1', '', 'after:TSconfig');
}
?>