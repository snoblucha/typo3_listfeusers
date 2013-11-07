<?php

class Tx_Listfeusers_Gmap_Maptype {

    const MAPTYPE_ROAD = 'google.maps.MapTypeId.ROADMAP';
    const MAPTYPE_SATELLITE = 'google.maps.MapTypeId.SATELLITE';
    const MAPTYPE_HYBRID = 'google.maps.MapTypeId.HYBRID';
    const MAPTYPE_TERRAIN = 'google.maps.MapTypeId.TERRAIN';

    private $value = self::MAPTYPE_HYBRID;
    protected static $maptypes = array(
        'road' => self::MAPTYPE_ROAD,
        'satellite' => self::MAPTYPE_SATELLITE,
        'hybrid' => self::MAPTYPE_HYBRID,
        'terrain' => self::MAPTYPE_TERRAIN,
    );

    public function __toString()
    {
        return $this->value;
    }



    public static function ROAD()
    {
        return new Tx_Listfeusers_Gmap_Maptype(self::MAPTYPE_ROAD);
    }

    public static function SATTELITE()
    {
        return new Tx_Listfeusers_Gmap_Maptype(self::MAPTYPE_SATELLITE);
    }

    public static function HYBRID()
    {
        return new Tx_Listfeusers_Gmap_Maptype(self::MAPTYPE_HYBRID);
    }

    public static function TERRAIN()
    {
        return new Tx_Listfeusers_Gmap_Maptype(self::MAPTYPE_TERRAIN);
    }

    /**
     *
     * @param string $type May be one of the 'satellite', 'hybrid', 'terrain', 'roads' or one of Const defined in class
     * @throws UnexpectedValueException
     */
    public function __construct($type)
    {
        if (in_array($type, self::$maptypes))
        {
            $this->value = $type;
        }
        else if (isset(self::$maptypes[$type]))
        {
            $this->value = self::$maptypes[$type];
        }
        else
        {
            throw new UnexpectedValueException($type);
        }
    }

}