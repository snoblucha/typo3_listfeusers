<?php

class Tx_Listfeusers_Gmap_Polygon extends Tx_Listfeusers_Gmap_Polyline{
    private $options = array();

    function __construct($id)
    {
        parent::__construct($id);
    }

    /**
     *
     * @param hex $color
     * @return Tx_Listfeusers_Gmap_Polygon
     */
    public function setFillColor($color){
        $this->options['fillColor'] = $color;
        return $this;
    }

    /**
     *
     * @param float between 0.0 and 1.0
     * @return Tx_Listfeusers_Gmap_Polygon
     */
    public function setFillOpacity($color){
        $this->options['fillColor'] = $color;
        return $this;
    }

    public function getOptions(){
        return parent::getOptions() + $this->options;
    }




}
