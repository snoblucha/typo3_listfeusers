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
class tx_listfeusers_pi1 extends tslib_pibase {

    public $prefixId = 'tx_listfeusers_pi1';  // Same as class name
    public $scriptRelPath = 'pi1/class.tx_listfeusers_pi1.php'; // Path to this script relative to the extension dir.
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
        $this->init();

        $GLOBALS['TSFE']->additionalHeaderData[$this->extKey] = '<link rel="profile" href="http://microformats.org/profile/hcard" />';

        if (!isset($this->conf['user.']))
        {
             return "Include template file in page template!";
        }

        $result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_users', $this->getUserFilter(),'','name');

        $content = '';



        while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)))
        {
            $local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
            $local_cObj->start($row, '');
            $content .= $local_cObj->cObjGet($this->conf['user.']);
        }


        return $this->pi_wrapInBaseClass($content);
    }

    function init()
    {
        $this->pi_initPIflexForm(); // Init and get the flexform data of the plugin
        $this->lConf = array(); // Setup our storage array...
        // Assign the flexform data to a local variable for easier access
        $piFlexForm = $this->cObj->data['pi_flexform'];
        // Traverse the entire array based on the language...
        // and assign each configuration option to $this->lConf array...
        foreach ($piFlexForm['data'] as $sheet => $data)
        {
            foreach ($data as $lang => $value)
            {
                foreach ($value as $key => $val)
                {
                    $this->lConf[$key] = $this->pi_getFFvalue($piFlexForm, $key, $sheet);
                }
            }
        }
    }

    private function getUserFilter()
    {
        // start where clause
        $where = '1=1';

        // if a user group was set, make sure only those users from that group
        // will be selected in the query
        if ($this->lConf['userGroups'])
        {
            $values = $this->lConf['userGroups'];
            $where .= ' AND (';
            $csv = t3lib_div::trimExplode(',', $values);
            for ($i = 0; $i < count($csv); $i++)
            {
                if ($i >= 1)
                {
                    $where .= ' OR ';
                }
                $where .= $GLOBALS['TYPO3_DB']->listQuery('usergroup', $csv[$i], 'fe_users');
            }
            $where .= ')';
            //$where .= "usergroup in ('')";tx_wecmap_shared::listQueryFromCSV('usergroup', $this->userGroups, 'fe_users', 'OR');
        }



        // filter out records that shouldn't be shown, e.g. deleted, hidden
        $where .= $this->cObj->enableFields('fe_users');


        return $where;
    }

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi1/class.tx_listfeusers_pi1.php']))
{
    include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/listfeusers/pi1/class.tx_listfeusers_pi1.php']);
}
