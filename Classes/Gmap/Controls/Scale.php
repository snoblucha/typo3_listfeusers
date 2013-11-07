<?php

class Tx_Listfeusers_Gmap_Controls_Scale extends Tx_Listfeusers_Gmap_Controls_Base {
    function __construct()
    {
        $this->setDisplay(true);
        $this->setPosition( self::POSITION_TOP_RIGHT);
    }

}