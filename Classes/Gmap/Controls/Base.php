<?php

class Tx_Listfeusers_Gmap_Controls_Base {

    const POSITION_TOP_CENTER = 'top_center';
    const POSITION_TOP_LEFT = 'top_left';
    const POSITION_TOP_RIGHT = 'top_right';

    const POSITION_LEFT_TOP = 'left_top';
    const POSITION_LEFT_CENTER = 'left_center';
    const POSITION_LEFT_BOTTOM = 'left_bottom';

    const POSITION_RIGHT_TOP = 'right_top';
    const POSITION_RIGHT_CENTER = 'right_center';
    const POSITION_RIGHT_BOTTOM = 'right_bottom';

    const POSITION_BOTTOM_CENTER = 'bottom_center';
    const POSITION_BOTTOM_RIGHT = 'bottom_right';
    const POSITION_BOTTOM_LEFT = 'bottom_left';




    private $display = true;
    private $position;
    private static $positions = array(
        'top_center' => 'google.maps.ControlPosition.TOP_CENTER',
        'top_left' => 'google.maps.ControlPosition.TOP_LEFT',
        'top_right' => 'google.maps.ControlPosition.TOP_RIGHT',
        'bottom_center' => 'google.maps.ControlPosition.BOTTOM_CENTER',
        'bottom_left' => 'google.maps.ControlPosition.BOTTOM_LEFT',
        'bottom_right' => 'google.maps.ControlPosition.BOTTOM_RIGHT',
        'left_center' => 'google.maps.ControlPosition.LEFT_CENTER',
        'left_top' => 'google.maps.ControlPosition.LEFT_TOP',
        'left_bottom' => 'google.maps.ControlPosition.LEFT_BOTTOM',
        'right_center' => 'google.maps.ControlPosition.RIGHT_CENTER',
        'right_top' => 'google.maps.ControlPosition.RIGHT_TOP',
        'right_bottom' => 'google.maps.ControlPosition.RIGHT_BOTTOM',
    );


    /**
     * Set the position of the controls
     * @param String $position Use constant from this class!
     * @return Tx_Listfeusers_Gmap_Controls_Base
     * @throws UnexpectedValueException
     */
    public function setPosition($position){

        if(!isset(self::$positions[$position])){
           // throw new UnexpectedValueException("Unknown position: $position");
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
        $this->display = (boolean)$display;
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