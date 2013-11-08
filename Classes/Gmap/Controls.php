<?php
class Tx_Listfeusers_Gmap_Controls {
    /**
     *
     * @var Tx_Listfeusers_Gmap_Controls_Maptype
     */
    private $maptype;

    /**
     *
     * @var Tx_Listfeusers_Gmap_Controls_Navigation
     */
    private $navigation;


    /**
     *
     * @var Gmap_Maptype_Scale
     */
    private $scale;

    /**
     *
     * @return Tx_Listfeusers_Gmap_Controls_Maptype
     */
    public function getMaptype()
    {
        return $this->maptype;
    }



    /**
     *
     * @return Tx_Listfeusers_Gmap_Controls_Navigation
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     *
     * @return Tx_Listfeusers_Gmap_Controls_Scale
     */
    public function getScale()
    {
        return $this->scale;
    }


    function __construct()
    {
        $this->maptype = new Tx_Listfeusers_Gmap_Controls_Maptype();
        $this->scale = new Tx_Listfeusers_Gmap_Controls_Scale();
        $this->navigation = new Tx_Listfeusers_Gmap_Controls_Navigation();

    }

    public function getOptions(){
       return array(
            'scale' => $this->scale->getOptions(),
            'navigation' => $this->navigation->getOptions(),
            'maptype' => $this->maptype->getOptions(),
        );

        

    }



}