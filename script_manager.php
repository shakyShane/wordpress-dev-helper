<?php

include('dev_helper.php');

$temp_dir   = get_bloginfo('template_directory');

/* Config */
$scripts     = array (
                'html5.js',
                'showcase.js',
                'custom.js'
    );
$dir = '/javascripts';
$scripts_dir   = $temp_dir . $dir;
$precompile =   dirname(__DIR__) . '/' . $dir; //talking to the filesystems
$compiled_dir  = $scripts_dir; //URLS
$compiled_js  = 'compiled.js'; //URLS

$localhost   = 'tuts.local';

/* Config END */


//Load scripts individually if in DEV mode.
if ($_SERVER['HTTP_HOST'] == $localhost){ // it's a DEV env

    $helper = new dev_helper( $scripts, $scripts_dir, true );
    $helper->is_dev = true;
    $helper->load_scripts();
    echo '<p><strong>DEV MODE : </strong> <a href="?devhelper=cjs">Compress JS</a> - <a href="">Compress CSS</a></p> ';

    if ($_GET['devhelper']){

        $action = $_GET['devhelper'];
        $helper->compile_js($precompile . '/' .$compiled_js);
    }

} else { //it's production - do nothing except load the compressed file.

    dev_helper::enq_script('compiled', $compiled_dir . '/' .$compiled_js); //URL
}



