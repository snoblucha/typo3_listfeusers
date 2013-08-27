<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Petr Šnobl <snoblucha@email.cz>
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
 ***************************************************************/


	// DEFAULT initialization of a module [BEGIN]
unset($MCONF);
require_once('conf.php');
require_once($BACK_PATH.'init.php');
require_once($BACK_PATH.'template.php');
$GLOBALS['LANG']->includeLLFile('EXT:listfeusers/cm1/locallang.xml');
//require_once(PATH_t3lib . 'class.t3lib_scbase.php');
	// ....(But no access check here...)
	// DEFAULT initialization of a module [END]


/**
 * listfeusers module cm1
 *
 * @author	Petr Šnobl <snoblucha@email.cz>
 * @package	TYPO3
 * @subpackage	tx_listfeusers
 */

class tx_listfeusers_cm1 extends t3lib_SCbase {
	/**
	 * Adds items to the ->MOD_MENU array. Used for the function menu selector.
	 *
	 * @return	[type]		...
	 */
	public function menuConfig() {
		$this->MOD_MENU = array(
			'function' => array(
				'1' => $GLOBALS['LANG']->getLL('function1'),
				'2' => $GLOBALS['LANG']->getLL('function2'),
				'3' => $GLOBALS['LANG']->getLL('function3'),
			)
		);
		parent::menuConfig();
	}

	/**
	 * Main function of the module. Write the content to $this->content
	 *
	 * @return void
	 */
	public function main() {

			// Draw the header.
		$this->doc = t3lib_div::makeInstance('mediumDoc');
		$this->doc->backPath = $GLOBALS['BACK_PATH'];
		$this->doc->form = '<form action="" method="post">';

			// JavaScript
		$this->doc->JScode = '
			<script language="javascript" type="text/javascript">
				script_ended = 0;
				function jumpToUrl(URL)	{
					document.location = URL;
				}
			</script>
		';

		$this->pageinfo = t3lib_BEfunc::readPageAccess($this->id,$this->perms_clause);
		$access = is_array($this->pageinfo) ? 1 : 0;
		if (($this->id && $access) || ($GLOBALS['BE_USER']->user['admin'] && !$this->id))	{
			if ($GLOBALS['BE_USER']->user['admin'] && !$this->id)	{
				$this->pageinfo = array(
						'title' => '[root-level]',
						'uid'   => 0,
						'pid'   => 0
				);
			}

			$headerSection = $this->doc->getHeader('pages', $this->pageinfo, $this->pageinfo['_thePath']) . '<br />'
					. $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xml:labels.path') . ': ' . t3lib_div::fixed_lgd_cs($this->pageinfo['_thePath'], -50);

			$this->content.=$this->doc->startPage($GLOBALS['LANG']->getLL('title'));
			$this->content.=$this->doc->header($GLOBALS['LANG']->getLL('title'));
			$this->content.=$this->doc->spacer(5);
			$this->content.=$this->doc->section('',$this->doc->funcMenu($headerSection,t3lib_BEfunc::getFuncMenu($this->id,'SET[function]',$this->MOD_SETTINGS['function'],$this->MOD_MENU['function'])));
			$this->content.=$this->doc->divider(5);

				// Render content
			$this->moduleContent();

				// Shortcut
			if ($GLOBALS['BE_USER']->mayMakeShortcut()) {
				$this->content.=$this->doc->spacer(20).$this->doc->section('',$this->doc->makeShortcutIcon('id',implode(',',array_keys($this->MOD_MENU)),$this->MCONF['name']));
			}
		}
		$this->content.=$this->doc->spacer(10);
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function printContent()	{
		$this->content .= $this->doc->endPage();
		echo $this->content;
	}

	/**
	 * [Describe function...]
	 *
	 * @return	[type]		...
	 */
	function moduleContent()	{
		switch((string) $this->MOD_SETTINGS['function'])	{
			case 1:
				$content = '<div align=center><strong>Hello World!</strong></div><br />
					The "Kickstarter" has made this module automatically, it contains a default framework for a backend module but apart from that it does nothing useful until you open the script "'.substr(t3lib_extMgm::extPath('listfeusers'),strlen(PATH_site)).$pathSuffix.index.php.'" and edit it!
					<hr />
					<br />This is the GET/POST vars sent to the script:<br />'.
					'GET:'.t3lib_div::view_array($_GET).'<br />'.
					'POST:'.t3lib_div::view_array($_POST).'<br />'.
					'';
				$this->content.=$this->doc->section('Message #1:',$content,0,1);
			break;
			case 2:
				$content='<div align=center><strong>Menu item #2...</strong></div>';
				$this->content.=$this->doc->section('Message #2:',$content,0,1);
			break;
			case 3:
				$content='<div align=center><strong>Menu item #3...</strong></div>';
				$this->content.=$this->doc->section('Message #3:',$content,0,1);
			break;
		}
	}
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/cm1/index.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/cm1/index.php']);
}




	// Make instance:
/** @var $SOBE tx_listfeusers_cm1 */
$SOBE = t3lib_div::makeInstance('tx_listfeusers_cm1');
$SOBE->init();


$SOBE->main();
$SOBE->printContent();

?>