<?php

/* * *************************************************************
 * Copyright notice
 *
 * (c) 2005-2009 Christian Technology Ministries International Inc.
 * All rights reserved
 *
 * This file is part of the Web-Empowered Church (WEC)
 * (http://WebEmpoweredChurch.org) ministry of Christian Technology Ministries
 * International (http://CTMIinc.org). The WEC is developing TYPO3-based
 * (http://typo3.org) free software for churches around the world. Our desire
 * is to use the Internet to help offer new life through Jesus Christ. Please
 * see http://WebEmpoweredChurch.org/Jesus.
 *
 * You can redistribute this file and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This file is distributed in the hope that it will be useful for ministry,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the file!
 * ************************************************************* */
/**
 * Plugin 'Frontend User Map' for the 'listfeusers' extension.
 *
 * @author Lebrija invest s.r.o.<petr.snobl@lebrija.cz>
 */

/**
 * Frontend User Map plugin for displaying all frontend users on a map.
 *
 * @author Lebrija invest s.r.o.<petr.snobl@lebrija.cz>
 * @package TYPO3
 * @subpackage tx_listfeusers
 */
class tx_listfeusers_pi3 extends tslib_pibase {

    var $prefixId = 'tx_wecmap_pi3';  // Same as class name
    var $scriptRelPath = 'pi3/class.tx_wecmap_pi3.php'; // Path to this script relative to the extension dir.
    var $extKey = 'listfeusers'; // The extension key.
    var $pi_checkCHash = TRUE;
    var $sidebarLinks = array();

    /**
     * Map ID
     * @var String
     */
    private $mapName;

    /**
     * Width of the map
     * @var int/string
     */
    private $width;

    /**
     * Height of the map
     * @var int/string
     */
    private $height;

    /**
     * Map object
     * @var Tx_Listfeusers_Gmap
     */
    private $map;

    /**
     *
     * @var array
     */

    /**
     * Draws a Google map containing all frontend users of a website.
     *
     * @param	array		The content array.
     * @param	array		The conf array.
     * @return	string	HTML / Javascript representation of a Google map.
     */
    function main($content, $conf)
    {


        $this->conf = $conf;
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        $this->init();

        if (empty($this->conf['marker.']['title']) && empty($this->conf['marker.']['description']))
        {
            $out = 'Please set the marker template. You can do this by including a static template or define it manually';
            return $out;
        }

        $centerLat = $this->conf['centerLat'];
        $centerLong = $this->conf['centerLong'];

        $zoomLevel = (int) $this->conf['zoomLevel'];

        $mapName = $this->conf['mapName'];
        if (empty($mapName))
        {
            $mapName = 'map-' . $this->cObj->data['uid'];
        }

        $this->mapName = $mapName;
        /* Create the Map object */
        $this->map = t3lib_div::makeInstance('tx_Listfeusers_Gmap', $this->mapName);
        $this->map->setCenter($centerLat, $centerLong);
        $this->map->setSize($this->width, $this->height);
        $this->map->setZoom($zoomLevel);


        $this->initControls();

        /* Select all frontend users */
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_users', $this->getUserFilter());

        $groups = $this->getGroups();

        $markers = array();

        while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)))
        {

            $markers[] = $row;
            $iconId = $singleConf['icon']['iconID'];
            $belongs_to = explode(',', $row['usergroup']);
            $icon = null;
            foreach ($belongs_to as $group)
            {
                $group = trim($group);
                if (!isset($groups[$group]['markers']))
                {
                    $groups[$group]['markers'] = array();
                }
                $groups[$group]['markers'][] = $row['uid'];
                if (isset($groups[$group]['icon']))
                {
                    $iconId = $groups[$group]['icon']['iconID'];
                    //return print_r($groups[$group]['icon'], true);
                    $icon = $groups[$group]['icon']['imagepath'];
                }
            }


            // make title and description

            $title = $this->render($row, $this->conf['marker.']['title.']);
            $description = $this->render($row, $this->conf['marker.']['description.']);

            if ($row['GPS'])
            {
                $GPS = t3lib_div::trimExplode(',', $row['GPS']);
                $marker = Tx_Listfeusers_Gmap_Marker::factory($row['uid'], $GPS[0], $GPS[1]);
                $marker->setTitle($title)->setContent($description)->setIcon($icon);
                $this->map->addMarker($marker);
            }
            else
            {
                $geocode = Tx_Listfeusers_Gmap_Geocode::geocode($row['uid'], "{$row['address']}, {$row['city']}");
                $geocode->setTitle($title)->setContent($description)->setIcon($icon);
                if ($geocode->lookup())
                {
                    $row['GPS'] = $geocode->getLat() . ',' . $geocode->getLng();
                    $db = $GLOBALS['TYPO3_DB'];
                    $db->exec_UPDATEquery('fe_users', "uid={$row['uid']}", $row);
                    $this->map->addMarker($geocode);
                }
                else
                {
                    $this->map->addMarker($geocode);
                }



                //$marker = $this->map->addMarkerByAddress( $title, $description, $singleConf['minzoom'], $singleConf['maxzoom'], $iconId            );
            }
            $this->map->autoCenter();


            $row['info_title'] = $title;
            $row['info_description'] = $description;
            $this->addSidebarItem($marker, $row);
        }



        // run all the content pieces through TS to assemble them
        $output = $this->map->render();
        //return print_r($content, true);
        return $this->pi_wrapInBaseClass($output . $this->renderGroups($groups));
    }

    /**
     * Init values from Form
     */
    function init()
    {
        /* Initialize the Flexform and pull the data into a new object */
        $this->pi_initPIflexform();
        $piFlexForm = $this->cObj->data['pi_flexform'];

        // get config from flexform or TS. Flexforms take precedence.
        $this->width = $this->pi_getFFvalue($piFlexForm, 'mapWidth', 'default');
        empty($this->width) ? $this->width = $this->conf['width'] : null;

        $this->height = $this->pi_getFFvalue($piFlexForm, 'mapHeight', 'default');
        empty($this->height) ? $this->height = $this->conf['height'] : null;
        $this->height = $this->height;

        $this->userGroups = $this->pi_getFFvalue($piFlexForm, 'userGroups', 'default');
        empty($this->userGroups) ? $this->userGroups = $this->conf['userGroups'] : null;

        $this->pid = $this->pi_getFFvalue($piFlexForm, 'pid', 'default');
        empty($this->pid) ? $this->pid = $this->conf['pid'] : null;

        $this->mapControlSize = $this->pi_getFFvalue($piFlexForm, 'mapControlSize', 'mapControls');
        (empty($this->mapControlSize) || $this->mapControlSize == 'none') ? $this->mapControlSize = $this->conf['controls.']['mapControlSize'] : null;

        $this->overviewMap = $this->pi_getFFvalue($piFlexForm, 'overviewMap', 'mapControls');
        empty($this->overviewMap) ? $this->overviewMap = $this->conf['controls.']['showOverviewMap'] : null;

        $this->mapType = $this->pi_getFFvalue($piFlexForm, 'mapType', 'mapControls');
        empty($this->mapType) ? $this->mapType = $this->conf['controls.']['showMapType'] : null;



        $this->initialMapType = $this->pi_getFFvalue($piFlexForm, 'initialMapType', 'default');
        empty($this->initialMapType) ? $this->initialMapType = $this->conf['initialMapType'] : null;

        $this->scale = $this->pi_getFFvalue($piFlexForm, 'scale', 'mapControls');
        empty($this->scale) ? $this->scale = $this->conf['controls.']['showScale'] : null;

        $this->private = $this->pi_getFFvalue($piFlexForm, 'privacy', 'default');
        empty($this->private) ? $this->private = $this->conf['private'] : null;

        $this->showDirs = $this->pi_getFFvalue($piFlexForm, 'showDirections', 'default');
        empty($this->showDirs) ? $this->showDirs = $this->conf['showDirections'] : null;

        $this->showWrittenDirs = $this->pi_getFFvalue($piFlexForm, 'showWrittenDirections', 'default');
        empty($this->showWrittenDirs) ? $this->showWrittenDirs = $this->conf['showWrittenDirections'] : null;

        $this->prefillAddress = $this->pi_getFFvalue($piFlexForm, 'prefillAddress', 'default');
        empty($this->prefillAddress) ? $this->prefillAddress = $this->conf['prefillAddress'] : null;

        $this->showSidebar = $this->pi_getFFvalue($piFlexForm, 'showSidebar', 'default');
        empty($this->showSidebar) ? $this->showSidebar = $this->conf['showSidebar'] : null;
    }

    /**
     * adds a sidebar item corresponding to the given marker.
     * Does so only if the sidebar is enabled.
     *
     * @return void
     * */
    function addSidebarItem(&$marker, $data)
    {
        if (!($this->showSidebar && is_object($marker)))
            return;
        $data['onclickLink'] = $marker->getClickJS();
        $this->sidebarLinks[] = tx_wecmap_shared::render($data, $this->conf['sidebarItem.']);
    }

    function getAddressForm()
    {
        $out = tx_wecmap_shared::render(array('map_id' => $this->mapName), $this->conf['addressForm.']);
        return $out;
    }

    function getDirections()
    {
        $out = tx_wecmap_shared::render(array('map_id' => $this->mapName), $this->conf['directions.']);
        return $out;
    }

    function getSidebar()
    {
        if (empty($this->sidebarLinks))
            return null;

        $c = '';

        foreach ($this->sidebarLinks as $link)
        {
            $c .= $link;
        }
        $out = tx_wecmap_shared::render(array('map_height' => $this->height, 'map_id' => $this->mapName, 'content' => $c), $this->conf['sidebar.']);

        return $out;
    }

    private function getUserFilter()
    {
        // start where clause
        $where = '1=1';

        // if a user group was set, make sure only those users from that group
        // will be selected in the query
        if ($this->userGroups)
        {
            $where .= $this->listQueryFromCSV('usergroup', $this->userGroups, 'fe_users', 'OR');
        }

        // if a storage folder pid was specified, filter by that
        if ($this->pid)
        {
            $where .= $this->listQueryFromCSV('pid', $this->pid, 'fe_users', 'OR');
        }

        // filter out records that shouldn't be shown, e.g. deleted, hidden
        $where .= $this->cObj->enableFields('fe_users');

        return $where;
    }

    function listQueryFromCSV($field, $values, $table, $mode = 'AND')
    {
        $where = ' AND (';
        $csv = t3lib_div::trimExplode(',', $values);
        for ($i = 0; $i < count($csv); $i++)
        {
            if ($i >= 1)
            {
                $where .= ' ' . $mode . ' ';
            }
            $where .= $GLOBALS['TYPO3_DB']->listQuery($field, $csv[$i], $table);
        }

        return $where . ')';
    }

    /**
     * Initializes Controls based on config
     */
    function initControls()
    {
        if ($this->mapType)
        {
            $this->map->getControls()->getMaptype()->setDisplay($this->maptype);
        }
        if ($this->initialMapType)
        {
            $this->map->setMaptype(new Tx_Listfeusers_Gmap_Maptype($this->initialMapType));
        }
    }

    /**
     * Get the list of groups wanted to be shown
     * @return array
     */
    private function getGroups()
    {
        $where = '';
        if ($this->userGroups)
        {
            $groups = explode(',', $this->userGroups);
            $where .= " uid IN ('" . implode("','", $groups) . "') ";
        }
        $result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', 'fe_groups', $where);
        $res = array();
        while (($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)))
        {
            $res[$row['uid']] = $row;
        }

        $this->loadGroupIcons($res);

        return $res;
    }

    /**
     * Set the groups icons
     * @param type $groups
     */
    private function loadGroupIcons(&$groups)
    {
        foreach ($groups as $key => $group)
        {
            $icon = $this->conf['groups.'][$group['uid'] . '.']['icon.'];
            if ($icon)
            {
                $groups[$key]['icon'] = $icon;
            }
        }
    }

    private function renderGroups($groups)
    {
        $on_line = $this->conf['groups.']['groups_per_line'];
        $res = '<div class="group-togglers"><ul>';
        $index = 0;
        foreach ($groups as $group)
        {
            $class = $index == 0 ? 'first' : ($index == count($groups) - 1 ? 'last' : '');
            $class .= ( ($index + 1) % $on_line == 0) ? 'row-last' : '';
            $res.= '<li class="group-toggler ' . $class . '"><a href="#" class="active group-toggle" id="group-' . $group['uid'] . '">'
                    . (isset($group['icon']) ? '<img src="' . $group['icon']['imagepath'] . '" alt="' . $group['title'] . '" title="' . $group['title'] . '" />' : '')
                    . $group['title'] . ' [ ' . count($group['markers']) . ' ] '
                    . '</a></li>';
            $index++;
        }
        $res.='</ul></div>' . $this->renderGroupsScript($groups);
        return $res;
    }

    private function renderGroupsScript($groups)
    {
        $res = array();
        $res[] = '<script type="text/javascript">';
        $res[] = 'var groups = {};';
        foreach ($groups as $group)
        {
            $res[] .= "groups[{$group[uid]}] = " . json_encode($group['markers']) . ';';
        }

        $res[] = '';
        $res[] = '';
        $res[] = '
                function removeMarkers(id){
                     id = id.split("-")[1];
                     if(groups[id] == null || !groups[id]) return;
                     for (var i = 0; i < groups[id].length; i++) {
                        gmaps["' . $this->mapName . '"].markers[groups[id][i]].setMap(null);
                     }
                    }
                    function showMarkers(id){
                     id = id.split("-")[1];
                     if(groups[id] == null || !groups[id]) return;
                     for (var i = 0; i < groups[id].length; i++) {
                        gmaps["' . $this->mapName . '"].markers[groups[id][i]].setMap(gmaps["' . $this->mapName . '"].map);
                     }
                    }
                    jQuery(function(){
                        jQuery(".group-toggle").click(function(e){
                            if(jQuery(this).hasClass("active")){
                                removeMarkers(jQuery(this).attr("id"));
                                jQuery(this).removeClass("active");
                            } else {
                                showMarkers(jQuery(this).attr("id"));
                                jQuery(this).addClass("active");
                            }
                            e.preventDefault();
                            return false;
                        });
                  });';
        $res[] = '</script>';
        return implode("\n", $res);
    }

    /**
     *
     * @param array $data
     * @param COA $conf
     * @param type $table
     * @return string
     */
    function render($data, $conf, $table = '')
    {

        $local_cObj = t3lib_div::makeInstance('tslib_cObj'); // Local cObj.
        $local_cObj->start($data, $table);
        $output = $local_cObj->cObjGet($conf);
        return $output;
    }

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/listfeusers/pi3/class.tx_wecmap_pi3.php'])
{
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/listfeusers/pi3/class.tx_wecmap_pi3.php']);
}

