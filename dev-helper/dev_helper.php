<?php
/**
 *
 * This is a tool I built for personal use.
 * When working on Wordpress websites, I wanted a quicker way to develop with normal JS files,
 * and deploy only compressed and minified files.
 *
 * Well this class will make the whole process possible in ONE click.
 *
 * Develop away on your local machine and it will include all the JS files seperately.
 * Click 'Compress' at any time and it will do a fresh join/compress.
 *
 * No need to modify anything else, the class will do the lifting for you and when the site is
 * live it will only ever show the single compressed file.
 * 
 *
 */
class dev_helper {
    /**
     * @var bool
     */
    public $show_dev_bar;

    /**
     * @var bool
     */
    public $imitate_production;

    /**
     * @var string
     * Used to determine Dev Env
     */
    public $localhost;

    /**
     * @var string
     * Stores a reference to the WP Theme Directory to prevent duplicate function calls.
     */
    public $templ_dir;
    /**
     * @var array
     * An array of file names only, NO path info. E.G 'html5.js'
     */
    public $scripts;

    /**
     * @var string
     * The directory that holds all the JavaScript files.
     */
    public $js_dir;

    /**
     * @var string
     * The Directory that will hold the Compressed output File.
     */
    public $js_min_dir;

    /**
     * @var string
     * The filename ONLY of the compressed output file. E.G 'compressed.js'
     */
    public $js_min_file;

    /**
     * @bool
     * Should we handle the jQuery include?
     */
    public $jquery;

    /**
     * @var string
     * jQuery from Googles CDN
     */
    public $jquery_url = "http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js";

    /**
    * @var string
    * jQuery UI from CDN 
    */
    public $jquery_ui = "https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js";

    /**
     * @var bool
     */
    public $is_dev = false;

    /**
     * 
     * @param $config array - specified in functions.php
     */
    function __construct( $config ){

        //Set Object Properties
        foreach ($config as $k => $v)
            $this->$k = $v;

        //Allow an easier override to 'imitate production'
        if ( $_GET['env'] && $_GET['env'] =='prod' ) $this->imitate_production = true;

        //Setup jQuery & jQuery if requested.
        if ($this->jquery)  $this->enq_script('jquery_cdn', $this->jquery_url );
        if ($this->jquery_ui)  $this->enq_script('jquery_ui', $this->jquery_ui );

        //Are we in Dev Mode?
        if ($_SERVER['HTTP_HOST'] == $config['localhost'] && $this->imitate_production === false){ // it's a DEV env

            $this->is_dev = true;

            //Load files individually
            $this->load_scripts();

            //Does the user want to see the Dev Bar at the top of the page?
            if ($this->show_dev_bar)
                echo '<p><strong>DEV MODE : </strong> <a href="?devhelper=cjs">Compress JS</a></p>';

            //Has the 'compress js' button been clicked?
            if ($_GET['devhelper']){

                //Check the 'action' - it's got 1 functionality right now, but who knows, could use it for CSS too?
                $action = $_GET['devhelper'];
                if ( $action == 'cjs' ){

                    //Run the Compiler - bool returned
                    if( $this->compile_js()){
                        echo '<strong>Single JavaScript File Created at :</strong> ' . $this->get_output_file();
                        $wpurl = get_bloginfo('wpurl');
                        echo "<p><a href='$wpurl'>Continue</a></p>";
                    } // _TODO - Handle error here.
                }
            }
        } else //it's a live site - Don't do anything except link to the single Compressed JS file.
            dev_helper::enq_script('custom-min', $this->get_output_file()); //URL
    }

    /**
     * Helper to reduce code repetition.
     * Using variables set in config, determine the final output file.
     * @return string
     */
    public function get_output_file(){

        return $this->templ_dir . $this->js_dir . $this->js_min_dir . '/' . $this->js_min_file;
    }

    /**
     * Use Wordpress's functions to correctly enqueue our JavaScripts
     *
     * @param $handle
     * @param $file (full file path)
     * @param bool $in_footer
     */
    public static function enq_script($handle, $file, $in_footer = true){

        wp_register_script($handle, $file, '','', $in_footer);
        wp_enqueue_script($handle);
    }

    /**
     * In DEV MODE, this will list each JS file individually to help with your fascinating debugging tasks!.
     */
    public function load_scripts(){

        foreach ($this->scripts as $i => $script){

            if( $this->does_file_exist($script) ){
                $script_url = $this->templ_dir . $this->js_dir . '/' . $script; //create the correct HTTP style file path.
                self::enq_script('script_' . $i, $script_url);
            } else {
                echo "<strong>Can't access : " . $script . " :</strong> Ignored for now - fix it before you compress though!";
            }
        }
    }

    /**
    * Does the file actually exist on the filesystem?
    * @param string $file - Filename only e.g 'custom.js'
    */
    public function does_file_exist($file){

        if (file_exists(dirname(__DIR__) . $this->js_dir . '/' . $file))
            return true;
    }

    /**
     * Join and Compress * Using min/JSMin.php
     *
     * Goes through each file in the $this->scripts array
     * Check along the way that the file actually exists!
     *
     * NOTE: if you have specified incorrect paths/filenames, the whole thing will breakdown. And cry.
     *
     * @return bool
     *
     */
    public function compile_js(){

        include('min/JSMin.php'); //minify script

        $js = null;
        foreach($this->scripts as $file) {

            if ( $this->does_file_exist($file) ){ //check the file exists on the filesystem, (can't use HTTP here :( )

                //set the full file path for the COMPRESSED JS file output
                $compressed_js = dirname(__DIR__) . $this->js_dir . $this->js_min_dir . '/' . $this->js_min_file;

                $full_file_path = $this->templ_dir . $this->js_dir . '/' . $file; //set the full path of each JS file for use in file_get_contents()
                $file_source = file_get_contents($full_file_path);
                $js .= JSMin::minify($file_source);
                file_put_contents($compressed_js, $js);
            }
            else { //If we get here, you have errors in your config. Either the Directory is wrong or your filenames are.
                echo '<strong>There was a problem accessing the file</strong> : ' . $file . ' - Process aborted.<br />';
                echo "Check your config in 'functions.php'... Something got screwed up!";
                return false;
            }
        }
    }
}