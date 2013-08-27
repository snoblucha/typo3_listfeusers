<?php

/* * *************************************************************
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
 * ************************************************************* */

// require_once(PATH_tslib . 'class.tslib_pibase.php');

/**
 * Plugin 'List users' for the 'listfeusers' extension.
 *
 * @author	Petr Šnobl <snoblucha@email.cz>
 * @package	TYPO3
 * @subpackage	tx_listfeusers
 */
class tx_listfeusers_pi2 extends tslib_pibase {

    public $prefixId = 'tx_listfeusers_pi2';  // Same as class name
    public $scriptRelPath = 'pi2/class.tx_listfeusers_pi2.php'; // Path to this script relative to the extension dir.
    public $extKey = 'listfeusers'; // The extension key.
    public $pi_checkCHash = TRUE;
    public $lConf = array();

    /**
     * The main method of the Plugin.
     *
     * @param string $content The Plugin content
     * @param array $conf The Plugin configuration
     * @return string The content that is displayed on the website
     */
    public function main($content, array $conf)
    {
        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();

        //$GLOBALS['TSFE']->additionalHeaderData[$this->extKey] = '<link rel="profile" href="http://microformats.org/profile/hcard" />';

        if (!isset($this->conf['user.']))
        {
             return "Include template file in page template!";
        }

        $user = $GLOBALS['TSFE']->fe_user->user;
        $user['date_of_birth'] = date('d.m.Y', $user['date_of_birth']);

        $local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
        $local_cObj->start($user, '');
        $content = $local_cObj->cObjGet($this->conf['user.']);
        //$content .= print_r($user, true);

        return $this->pi_wrapInBaseClass($content);
    }

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi2/class.tx_listfeusers_pi2.php']))
{
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi2/class.tx_listfeusers_pi2.php']);
}
?>