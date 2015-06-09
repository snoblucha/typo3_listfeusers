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



/**
 * Class that adds the wizard icon.
 *
 * @author	Petr Šnobl <snoblucha@email.cz>
 * @package	TYPO3
 * @subpackage	tx_listfeusers
 */
class tx_listfeusers_pi2_wizicon {

	/**
	 * Processing the wizard items array
	 *
	 * @param array $wizardItems The wizard items
	 * @return array Modified array with wizard items
	 */
	public function proc(array $wizardItems) {
		$LL = $this->includeLocalLang();

		$wizardItems['plugins_tx_listfeusers_pi2'] = array(
			'icon' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath('listfeusers') . 'pi2/ce_wiz.png',
			'title' => $GLOBALS['LANG']->getLLL('pi2_title', $LL),
			'description' => $GLOBALS['LANG']->getLLL('pi2_plus_wiz_description', $LL),
			'params' => '&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=listfeusers_pi2'
		);

		return $wizardItems;
	}

	/**
	 * Reads the [extDir]/locallang.xml and returns the $LOCAL_LANG array found in that file.
	 *
	 * @return array The array with language labels
	 */
	protected function includeLocalLang() {
		$llFile = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('listfeusers') . 'locallang.xml';
		$version = class_exists('\TYPO3\CMS\Core\Utility\VersionNumberUtility')
				? \TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version)
				: t3lib_div::int_from_ver(TYPO3_version);
		if ($version < 4006000) {
			$LOCAL_LANG = t3lib_div::readLLXMLfile($llFile, $GLOBALS['LANG']->lang);
		} else {
			/** @var $llxmlParser t3lib_l10n_parser_Llxml */
			$llxmlParser = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Localization\Parser\LocallangXmlParser');
			$LOCAL_LANG = $llxmlParser->getParsedData($llFile, $GLOBALS['LANG']->lang);
		}

		return $LOCAL_LANG;
	}
}



if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi2/class.tx_listfeusers_pi2_wizicon.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi2/class.tx_listfeusers_pi2_wizicon.php']);
}

?>