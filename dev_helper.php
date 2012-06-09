<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Shane
 * Date: 06/06/12
 * Time: 18:29
 * To change this template use File | Settings | File Templates.
 */
class dev_helper {
    /**
     * The array of Javascript Files..
     * @var array
     */
    private $scripts = array();

    /**
     * @var string
     */
    public $jquery = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";

    /**
     * @var null|string|void
     */
    public $theme_dir = null;

    /**
     * @var bool
     */
    public $is_dev = false;

    public $scripts_dir = null;

    /**
     *
     * @param $scripts
     * @param bool $jquery
     *
     */
    function __construct( $scripts, $scripts_dir, $jquery = false ){


        //set the directory where the scripts will live.
        $this->scripts_dir = $scripts_dir;

        //load scripts to object;
        $this->scripts = $scripts;

        // load jquery
        if ($jquery)
            self::enq_script('jquery', $this->jquery);

    }

    /**
     * @param $handle
     * @param $file
     * @param bool $in_footer
     *
     */
    public static function enq_script($handle, $file, $in_footer = true){

        wp_register_script($handle, $file, '','', $in_footer);
        wp_enqueue_script($handle);

    }
    public function load_scripts(){

        foreach ($this->scripts as $i => $script){

            $script_url = $this->scripts_dir . '/' . $script;
            self::enq_script('script_' . $i, $script_url);

        }
    }

    public function compile_js( $compiled_js ){

        include('min/JSMin.php');

        $js = null;
        foreach($this->scripts as $file) {
           $file = $this->scripts_dir . '/' . $file;

            $js .= JSMin::minify(file_get_contents($file));
            file_put_contents($compiled_js, $js);
//            file_put_contents($compiled_js, $js);
        }
    }
}