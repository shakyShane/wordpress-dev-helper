<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Shane
 * Date: 06/06/12
 * Time: 18:29
 * To change this template use File | Settings | File Templates.
 */
class dev_helper {

    public $localhost;
    public $templ_dir;
    public $scripts;
    public $js_dir;
    public $js_min_dir;
    public $js_min_file;
    public $jquery;
    public $jquery_url = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";

    public $theme_dir = null;

    public $is_dev = false;

    public $scripts_dir = null;

    /**
     *
     * @param $scripts
     * @param bool $jquery
     *
     */
    function __construct( $config ){

        //Set Object Properties
        foreach ($config as $k => $v)
            $this->$k = $v;

        //Setup Jquery
        if ($this->jquery)
            $this->enq_script('jquery', $this->jquery_url );

        if ($_SERVER['HTTP_HOST'] == $config['localhost']){ // it's a DEV env

            $this->is_dev = true;
            $this->load_scripts();

            echo '<p><strong>DEV MODE : </strong> <a href="?devhelper=cjs">Compress JS</a></p> ';

            if ($_GET['devhelper']){
                $action = $_GET['devhelper'];
                $precomp = dirname(dirname(__DIR__)) . $this->js_dir . $this->js_min_dir . '/' . $this->js_min_file;
                if ( $action == 'cjs' ){

                   if( $this->compile_js($precomp))
                       echo '<strong>Single JavaScript File Created at :</strong> ' . $this->templ_dir . $this->js_dir . $this->js_min_dir . '/' . $this->js_min_file;

                }
            }
        } else  //it's production - do nothing except load the compressed file.
            dev_helper::enq_script('min', $this->templ_dir . $this->js_dir . $this->js_min_dir . '/' . $this->js_min_file); //URL
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

            $script_url = $this->templ_dir . $this->js_dir . '/' . $script;
            self::enq_script('script_' . $i, $script_url);
        }
    }

    public function compile_js( $compiled_js ){

        include('min/JSMin.php');

        $js = null;
        foreach($this->scripts as $file) {

            $file = $this->templ_dir . $this->js_dir . '/' . $file;

            $js .= JSMin::minify(file_get_contents($file));
            file_put_contents($compiled_js, $js);
        }
        return true;
    }
}