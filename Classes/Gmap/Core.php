<?php



/**
 * Contains the Google Map class.
 *
 * @package    gmap
 * @author     Leonard Fischer <leonard.fischer@sn4ke.de>
 * @copyright  (c) 2011 Leonard Fischer
 * @version    1.3
 */
class Tx_Listfeusers_Gmap_Core {

    private $id;

    /**
     * Center lat
     * @var float
     */
    private $lat;
    private $lng;

    /**
     * Zoom in range:
     * @var int
     */
    private $zoom;
    private $width;
    private $height;

    /**
     * Sensor
     * @var boolean
     */
    private static $sensor = false;
    private static $enabled = false;
    protected static $instances = array();
    private $markers = array();
    private $polylines = array();
    private $polygons = array();

    /**
     *
     * @var Tx_Listfeusers_Gmap_Bounds
     */
    private $bounds;

    /**
     *
     * @var Tx_Listfeusers_Gmap_Controls
     */
    private $controls;

    /**
     *
     * @var Tx_Listfeusers_Gmap_Maptype
     */
    private $type;

    /**
     *
     * @var Array of Gmap_Geocode
     */
    private $geocode_request = array();
    protected $view = 'gmap';

    /**
     * The factory method for instant method-chaining.
     *
     * @param String $id Id of the map. Singleton.
     * @return Gmap
     */
    public static function factory($id = null)
    {
        if (!isset(self::$instances[$id]))
        {
            self::$instances[$id] = new Gmap($id);
        }
        return self::$instances[$id];
    }

    /**
     * Constructor for the Google-Map class.
     *
     * @param array $options
     */
    public function __construct($id = null)
    {
        if ($id === null)
        {
            $id = uniqid();
        }
        $this->id = $id;

        //set the values from config
        $this->setMaptype(Tx_Listfeusers_Gmap_Maptype::HYBRID());
        /*$this->setCenter($config->get('lat'), $config->get('lng'));
        $this->setSize($config->get('width'), $config->get('height'));

        $this->setView($config->get('view'));
        $this->setZoom($config->get('zoom'));
        $this->setSize($config->get('width'), $config->get('width'));

        self::setSensor($config->get('sensor'));
        self::setEnabled($config->get('disableAutoEnable'));*/


        $this->controls = new Tx_Listfeusers_Gmap_Controls();
        $this->bounds = new Tx_Listfeusers_Gmap_Bounds();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getView()
    {
        return $this->view;
    }

    /**
     * Get the conrols of the map
     * @return Tx_Listfeusers_Gmap_Controls
     */
    public function getControls()
    {
        return $this->controls;
    }

    public function getPixelValue($value)
    {

        if (strpos($value, '%'))
        {
            $value = 1024 * substr($value, -1) / 100;
        }
        else
        {
            $value = substr($value, 0, -2); // else just remove px form the end
        }
        return $value;
    }

    /**
     * Get the boundaries of the objects on the map
     * @return Tx_Listfeusers_Gmap_Bounds
     */
    public function getBounds()
    {
        return $this->bounds;
    }

    /**
     * Auto zoom map
     * @param int $min_zoom minimal zoom to keep. If not set, the already set value is used. (From config or set before)
     * @return Gmap
     */
    public function autoZoom($min_zoom = null)
    {
        if ($min_zoom === null)
        {
            $min_zoom = $this->zoom;
        }

        $bounds = $this->getBounds();

        $width = $this->getPixelValue($this->getWidth());
        $height = $this->getPixelValue($this->getHeight());

        $top = $bounds->getTop();
        $bottom = $bounds->getBottom();

        $dlat = abs($top->getLat() - $bottom->getLat());
        $dlon = abs($top->getLng() - $bottom->getLng());

        //single bound
        if ($dlat == 0 && $dlon == 0)
        {
            $this->zoom = $min_zoom; //keep set, or passed in
        }
        else
        {

            // Center latitude in radians
            $clat = pi() * ($bottom->getLat() + $top->getLat()) / 360.;

            $C = 0.0000107288;
            $z0 = ceil(log($dlat / ($C * $height)) / log(2));
            $z1 = ceil(log($dlon / ($C * $width * cos($clat))) / log(2));
            $this->zoom = 16 - (($z1 > $z0) ? $z1 : $z0);
        }

        return $this;
    }

    /**
     * Set the center of the map. It is calculated from all objects
     * @return Gmap
     */
    public function autoCenter()
    {
        $center = $this->bounds->getCenter();
        if($center->getLat() && $center->getLng()) {
            $this->setCenter($center->getLat(), $center->getLng());
        }
        return $this;
    }

    /**
     *
     * @return Tx_Listfeusers_Gmap_Maptype
     */
    public function getMaptype()
    {
        return $this->type;
    }

    /**
     * Renders the google-map template.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Add a marker to the map.
     * @param Tx_Listfeusers_Gmap_Marker $marker Marker to add
     * @return Gmap
     */
    public function addMarker(Tx_Listfeusers_Gmap_Marker $marker)
    {
        $this->markers[$marker->getId()] = $marker;
        $this->bounds->addMarker($marker);
        return $this;
    }

    /**
     * Add geocode request to map.
     * @param Tx_Listfeusers_Gmap_Geocode $geocode
     * @return Gmap_Core
     */
    public function addGeocode(Tx_Listfeusers_Gmap_Geocode $geocode)
    {
        $this->geocode_request[$geocode->getId()] = $geocode;
        return $this;
    }

    /**
     * Add a polygon to the map.
     * @param Tx_Listfeusers_Gmap_Polygon $polygon Polygon
     * @return Gmap
     */
    public function addPolygon(Tx_Listfeusers_Gmap_Polygon $polygon)
    {
        $this->polygons[$polygon->getId()] = $polygon;
        $this->bounds->addPolyline($polygon);
        return $this;
    }

    /**
     * Add a polyline to the map.
     *
     * @param Tx_Listfeusers_Gmap_Polyline $polyline Polyline
     * @return Gmap
     */
    public function addPolyline(Tx_Listfeusers_Gmap_Polyline $polyline)
    {
        $this->polylines[$polyline->getId()] = $polyline;
        $this->bounds->addPolyline($polyline);
        return $this;
    }

    /**
     * Cleanes the JSON strings by removing the quotes from google-variables.
     *
     * @param string $str
     * @return string
     */
    public static function clean_json_string($str)
    {
        return preg_replace('~"(google\.(.*?))"~', '$1', $str);
    }

    /**
     * Renders the google-map template.
     *
     * @uses Text::random()
     * @uses Arr::merge()
     * @param string $view Defines a view for rendering.
     * @return string
     */
    public function render($view = '')
    {
        // Bind the necessary variables.
        $this->view = Tx_Listfeusers_View::factory($view ? $view : $this->view)
                ->set('markers', $this->markers)
                ->set('polylines', $this->polylines)
                ->set('polygons', $this->polygons)
                ->set('geocode_requests', $this->geocode_request)
                ->set('instance', $this);

        // Render the view.
        $result = Tx_Listfeusers_Gmap::enable() . $this->view->render();
        self::$enabled = true;

        return $result;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set a size for the rendered Google-Map.
     * You may set a CSS attribute like for example "500px", "50%" or "10em".
     * If you just set an integer, "px" will be used.
     *
     * @param mixed $width May be a CSS attribute ("500px", "50%", "10em") or an int.
     * @param mixed $height May be a CSS attribute ("500px", "50%", "10em") or an int.
     * @return Gmap
     */
    public function setSize($width = NULL, $height = NULL)
    {
        if ($width != NULL)
        {
            $this->width = (is_numeric($width)) ? $width . 'px' : $width;
        }
        if ($height != NULL)
        {
            $this->height = (is_numeric($height)) ? $height . 'px' : $height;
        }
        return $this;
    }

    /**
     * Set another map-type. Possible types are 'road', 'satellite', 'hybrid' and 'terrain'.
     *
     * @param Tx_Listfeusers_Gmap_Maptype $maptype
     * @return Gmap
     */
    public function setMaptype(Tx_Listfeusers_Gmap_Maptype $maptype)
    {
        $this->type = $maptype;
        return $this;
    }

    /**
     * Set a new position to show, when starting up the map.
     *
     * @param float $lat
     * @param float $lng
     * @return Gmap
     */
    public function setCenter($lat = NULL, $lng = NULL)
    {
        if ($lat == null && $lng == null)
        {
            $this->autoCenter();
        }
        else
        {
            if ($lat != NULL)
            {
                $this->lat = Tx_Listfeusers_Gmap::validate_latitude($lat);
            }
            if ($lng != NULL)
            {
                $this->lng = Tx_Listfeusers_Gmap::validate_longitude($lng);
            }
        }
        return $this;
    }

    /**
     * Is sensor set?
     * @return boolean
     */
    public static function getSensor()
    {
        return self::$sensor;
    }

    /**
     * Set the sensor-parameter for the google-api.
     *
     * Static method. Can be called before some map render or Gmap::enable();
     * setting parameter for inclusion of the map script;
     *
     * @param boolean $sensor
     */
    public static function setSensor($sensor)
    {
        if (!is_bool($sensor))
        {
            throw new Exception('The parameter must be boolean.');
        } // if

        self::$sensor = $sensor;
    }

// function

    /**
     * Set the view for displaying the Google-map.
     *
     * @param string $view
     * @return Gmap
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

// function

    /**
     * Set the zoom level for the Google-map.
     *
     * @param int $zoom
     * @return Gmap
     */
    public function setZoom($zoom)
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function getOptions()
    {
        $res = array(
            'id' => $this->getId(),
            'lat' => $this->lat,
            'lng' => $this->lng,
            'zoom' => $this->zoom,
            'maptype' => (string) $this->type,
            'controls' => $this->getControls()->getOptions(),
        );

        return $res;
    }

    /**
     * Validate, if the latitude is in bounds.
     *
     * @param float $lat
     * @return float
     */
    protected static function validate_latitude($lat)
    {
        if ($lat > 180 OR $lat < -180)
        {
            throw new Exception('Latitude has to lie between -180.0 and 180.0! Set to '.$lat);
        } // if

        return $lat;
    }

// function

    /**
     * Validate, if the longitude is in bounds.
     *
     * @param float $lng
     * @return float
     */
    protected static function validate_longitude($lng)
    {
        if ($lng > 90 OR $lng < -90)
        {
            throw new Exception('Longitude has to lie between -90.0 and 90.0! Set to :lng');
        } // if

        return $lng;
    }

    /**
     * Returns the script line for including Map::api
     *
     * this method is called on first render of any script.
     * If once enabled you can get the string by setting $force_enable param to true
     *
     * @uses self::$sensor For setting the sensor in link
     * @param boolean $force_enable get the string despite the enabled static flag
     * @return string
     */
    public static function enable($force_enable = false)
    {
        if (!self::$enabled || $force_enable)
        {
            self::$enabled = true; //set the enabled flag
            $GLOBALS['TSFE']->additionalHeaderData['google-map'] = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor='.(Tx_Listfeusers_Gmap::getSensor() ? 'true' : 'false').'"></script>';
            $GLOBALS['TSFE']->additionalHeaderData['listfeusers-map'] = '<script src="' . t3lib_extMgm::siteRelPath('listfeusers') . 'js/gmap.js" type="text/javascript"></script>';
        }
        else
        {
            return '';
        }
    }

    /**
     * Set the $enabled flag to class.
     * @param boolean $state new value. True by default
     */
    public static function setEnabled($state = true)
    {
        self::$enabled = $state;
    }

}

