<?php

class Tx_Listfeusers_Gmap_Controls_Navigation extends Tx_Listfeusers_Gmap_Controls_Base {

    const TYPE_SMALL = 'small';
    const TYPE_ZOOM_PAN = 'zoom_pan';
    const TYPE_ANDROID = 'android';
    const TYPE_DEFAULT = 'default';

    private $type;

    private static $navigations = array(
        self::TYPE_SMALL => 'google.maps.NavigationControlStyle.SMALL',
        self::TYPE_ZOOM_PAN => 'google.maps.NavigationControlStyle.ZOOM_PAN',
        self::TYPE_ANDROID => 'google.maps.NavigationControlStyle.ANDROID',
        self::TYPE_DEFAULT => 'google.maps.NavigationControlStyle.DEFAULT',
    );

    function __construct()
    {
        $this->setDisplay(true);
        $this->setType(self::TYPE_DEFAULT);
        $this->setPosition(self::POSITION_TOP_RIGHT);
    }

    /**
     * Set the type of the maptype control horizontal|default|dropdown or use const from class TYPE_*
     * @param String $type
     * @return Tx_Listfeusers_Gmap_Controls_Navigation
     * @throws UnexpectedValueException
     */
    public function setType($type)
    {
        if (isset(self::$navigations[$type]))
        {
            $this->type = self::$navigations[$type];
        }
        else
        {
            throw new UnexpectedValueException("Navigation $type is not recognized");
        }
        return $this;
    }

    public function getOptions(){
        $res = parent::getOptions();
        $res['type'] = $this->type;
        return $res;
    }

}