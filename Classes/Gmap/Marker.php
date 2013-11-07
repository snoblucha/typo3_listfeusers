<?php

class Tx_Listfeusers_Gmap_Marker extends Tx_Listfeusers_Gmap_Object{

    private $lat;
    private $lng;

    private $title;
    private $icon;
    private $content;

    /**
     * Create marker.
     * @param string/int $id
     * @param float $lat
     * @param float $lng
     * @return \Tx_Listfeusers_Gmap_Marker
     */
    public static function factory($id, $lat, $lng)
    {
        return new Tx_Listfeusers_Gmap_Marker($id, $lat, $lng);
    }

    public function __construct($id, $lat, $lng)
    {
        parent::__construct($id);
        $this->lat = $lat;
        $this->lng = $lng;
        $this->id = $id;
        $this->title = $id;
    }

    /**
     * Path to icon.
     * @param string $icon
     * @return Tx_Listfeusers_Gmap_Marker
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Set the content to be displayed on click on the marker
     * @param string $content Content of the popup window
     * @return Tx_Listfeusers_Gmap_Marker
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }



    public function getOptions(){
        $res = parent::getOptions() + array(
            'lat' => $this->getLat(),
            'lng' => $this->getLng(),
            'title' => $this->title,
        );
        if($this->icon) {
            $res['icon'] = $this->getIcon();
        }
        if($this->content) {
            $res['content'] =  $this->getContent();
        }
        return $res;
    }


    public function getLat()
    {
        return $this->lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setLat($lat)
    {
        $this->lat = $lat;
        return $this;
    }

    public function setLng($lng)
    {
        $this->lng = $lng;
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function setPos($lat, $lng)
    {
        $this->setLat($lat);
        $this->setLng($lng);
    }

}