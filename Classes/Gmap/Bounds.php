<?php

class Tx_Listfeusers_Gmap_Bounds {

    private $top_lat = -1000;
    private $top_lng = -1000;
    private $bottom_lat = 1000;
    private $bottom_lng = 1000;

    function __construct()
    {

    }

    /**
     *
     * @return \Tx_Listfeusers_Gmap_Marker
     */
    public function getTop()
    {
        return new Tx_Listfeusers_Gmap_Marker('top', $this->top_lat, $this->top_lng);
    }

    /**
     *
     * @return \Tx_Listfeusers_Gmap_Marker
     */
    public function getBottom()
    {
        return new Tx_Listfeusers_Gmap_Marker('bottom', $this->bottom_lat, $this->bottom_lng);
    }

    /**
     *
     * @param Tx_Listfeusers_Gmap_Marker $marker
     * @return Tx_Listfeusers_Gmap_Bounds
     */
    public function addMarker(Tx_Listfeusers_Gmap_Marker $marker)
    {
        $this->addLatLng($marker->getLat(), $marker->getLng());
        return $this;
    }

    /**
     *
     * @param Tx_Listfeusers_Gmap_Polyline $polyline
     * @return Tx_Listfeusers_Gmap_Bounds
     */
    public function addPolyline(Tx_Listfeusers_Gmap_Polyline $polyline)
    {
        $this->addLatLng($polyline->getBounds()->getTop()->getLat(), $polyline->getBounds()->getTop()->getLng());
        $this->addLatLng($polyline->getBounds()->getBottom()->getLat(), $polyline->getBounds()->getBottom()->getLng());
        return $this;
    }

    public function addLatLng($lat, $lng)
    {
        $this->top_lat = $this->top_lat < $lat ? $lat : $this->top_lat;
        $this->top_lng = $this->top_lng < $lng ? $lng : $this->top_lng;
        $this->bottom_lat = $this->bottom_lat > $lat ? $lat : $this->bottom_lat;
        $this->bottom_lng = $this->bottom_lng > $lng ? $lng : $this->bottom_lng;
    }

    /**
     *
     * @return \Tx_Listfeusers_Gmap_Marker
     */
    public function getCenter(){
        return new Tx_Listfeusers_Gmap_Marker('center', 0.5 * ($this->top_lat + $this->bottom_lat), 0.5 * ($this->top_lng + $this->bottom_lng));
    }

}
