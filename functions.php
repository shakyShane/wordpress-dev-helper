<?php

/**
 *
 * Functions.php sample
 *
 * Put this code into your functions.php
 *
 */
include('dev-helper/dev_helper.php');

$config = array(
    'show_dev_bar' => false,
    'imitate_production' => true,
    'localhost' => 'tuts.local',
    'templ_dir' => get_bloginfo('template_directory'),
    'scripts' => array('html5.js', 'showcase.js', 'custom.js'),
    'js_dir' => '/js',
    'js_min_dir' => '/compiled',
    'js_min_file' => 'compiled.js',
    'jquery' => true
);

$helper = new dev_helper($config);
