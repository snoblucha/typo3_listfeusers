<?php

class Tx_Listfeusers_View {

    private $vars = array();
    private $path;

    /**
     * Static function to consturct views
     * @param string $view relative path to view without extension  ex: folder/item  => .../Views/folder/item.php
     * @return \Tx_Maklerportallog_View
     */
    public static function factory($view)
    {
        $v = new Tx_Listfeusers_View($view);
        return $v;
    }

    function __construct($view)
    {
        $this->setView($view);
    }

    /**
     *
     * @param string $view
     * @return tx_maklerportallog_pi2
     */
    public function setView($view)
    {
        $this->path = t3lib_div::getFileAbsFileName("EXT:listfeusers/Views/$view.php");
        return $this;
    }

    /**
     *
     * @param String $name
     * @param mixed $value
     * @return View
     */
    public function set($name, $value)
    {
        $this->vars[$name] = $value;
        return $this;
    }

    /**
     *
     * @return String
     */
    public function render()
    {
        ob_start();
        foreach ($this->vars as $name => $value)
        {
            $$name = $value;
        }
        include $this->path;
        return ob_get_clean();
    }

    public function __toString()
    {
        $this->render();
    }

}
