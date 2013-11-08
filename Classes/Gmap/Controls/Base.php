<?php

class Tx_Listfeusers_Gmap_Controls_Base {

    const POSITION_TOP = 'top';
    const POSITION_LEFT = 'left';
    const POSITION_RIGHT = 'right';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_BOTTOM_RIGHT = 'bottom_right';
    const POSITION_BOTTOM_LEFT = 'bottom_left';
    const POSITION_TOP_LEFT = 'top_left';
    const POSITION_TOP_RIGHT = 'top_right';



    private $display = true;
    private $position;
    private static $positions = array(
        'top' => 'google.maps.ControlPosition.TOP',
        'top_left' => 'google.maps.ControlPosition.TOP_LEFT',
        'top_right' => 'google.maps.ControlPosition.TOP_RIGHT',
        'bottom' => 'google.maps.ControlPosition.BOTTOM',
        'bottom_left' => 'google.maps.ControlPosition.BOTTOM_LEFT',
        'bottom_right' => 'google.maps.ControlPosition.BOTTOM_RIGHT',
        'left' => 'google.maps.ControlPosition.LEFT',
        'right' => 'google.maps.ControlPosition.RIGHT',
    );


    /**
     * Set the position of the controls
     * @param String $position Use constant from this class!
     * @return Tx_Listfeusers_Gmap_Controls_Base
     * @throws UnexpectedValueException
     */
    public function setPosition($position){
        if(!isset(self::$positions[$position])){
            throw new UnexpectedValueException("Unknown position: $position");
        } else {
            $this->position = self::$positions[$position];
        }
        return $this;
    }

    /**
     * Is visible?
     * @param boolean $display
     * @return Tx_Listfeusers_Gmap_Controls_Base Description
     */
    public function setDisplay($display)
    {
        $this->display = $display;
        return $this;
    }


    public function getDisplay()
    {
        return $this->display;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getOptions(){
        return array(
            'display' => $this->display,
            'position' => $this->position,
        );
    }




}