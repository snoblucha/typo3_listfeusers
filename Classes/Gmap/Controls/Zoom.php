<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Zomm
 *
 * @author snoblucha
 */
class Tx_Listfeusers_Gmap_Controls_Zoom extends Tx_Listfeusers_Gmap_Controls_Base {

    const TYPE_SMALL = 'small';
    const TYPE_LARGE = 'large';
    const TYPE_DEFAULT = 'default';

    private $type;
    private static $zoomTypes = array(
        self::TYPE_SMALL => 'google.maps.ZoomControlStyle.SMALL',
        self::TYPE_LARGE => 'google.maps.ZoomControlStyle.LARGE',
        self::TYPE_DEFAULT => 'google.maps.NavigationControlStyle.DEFAULT',
    );

    function __construct()
    {
        $this->setDisplay(true);
        $this->setPosition(self::POSITION_TOP_LEFT);
        $this->setType(self::TYPE_DEFAULT);
    }

    /**
     * Set the type of the maptype control horizontal|default|dropdown or use const from class TYPE_*
     * @param String $type
     * @return Tx_Listfeusers_Gmap_Controls_Navigation
     * @throws UnexpectedValueException
     */
    public function setType($type)
    {
        if (isset(self::$zoomTypes[$type]))
        {
            $this->type = self::$zoomTypes[$type];
        }
        else
        {
//            throw new UnexpectedValueException("Zoom $type is not recognized");
        }
        return $this;
    }

    public function getOptions()
    {
        $res = parent::getOptions();
        $res['type'] = $this->type;
        return $res;
    }

}