<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Pan
 *
 * @author snoblucha
 */
class Tx_Listfeusers_Gmap_Controls_Pan extends Tx_Listfeusers_Gmap_Controls_Base {

       function __construct()
    {
        $this->setDisplay(true);
        $this->setPosition(self::POSITION_TOP_LEFT);

    }

}