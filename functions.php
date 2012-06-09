<?php

/**
 *
 * Functions.php sample
 *
 * Put this code into your functions.php
 *
 */


//Include the Class.
include('dev-helper/dev_helper.php');

/* Everything is explained in the comments below */
$config = array(
    'show_dev_bar'          => true,
    'imitate_production'    => false,
    'localhost'             => 'wp.local',
    'templ_dir'             => get_bloginfo('template_directory'),
    'scripts'               => array('awesome-plugin.js', 'slider.js', 'custom.js'),
    'js_dir'                => '/js',
    'js_min_dir'            => '/compiled',
    'js_min_file'           => 'compiled.js',
    'jquery'                => true
);

$helper = new dev_helper($config);

/**
Explanation of config.

$config['show_dev_bar']       = true;
    Show the 'compress' button at the top of the webpage when in dev mode.

$config['imitate_production'] = false;
    Quick way to ensure the compressed file is being loaded on live site, just change to true and check.

$config['localhost']          = 'wp.local';
    echo $_SERVER['HTTP_HOST'] on your local machine to get this value. (it could be localhost/wordpress etc.

$config['templ_dir']          = get_bloginfo('template_directory');
    Your theme Directory - don't modify.

$config['scripts']            = array('awesome-plugin.js', 'slider.js', 'custom.js');
    The filenames of your javascripts (uncompressed versions)

$config['js_dir']             = '/js';
    The directory holding your uncompressed javascripts

$config['js_min_dir']         = '/compiled';
    The directory to place your compressed javascript file.

$config['js_min_file']        = 'compiled.js';
    The filename of the compressed javascript

$config['jquery']             = 'false';
    Set to TRUE if you want to include jquery before any of your scripts.  */



