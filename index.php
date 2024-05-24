<?php
/**
 * @author Luke Cheng
 * File: index.php
 *
 * This page sets up the Fat-Free Framework, includes error reporting configurations and rendering of views.
 */

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once ('vendor/autoload.php');

$f3 = Base::instance();

$f3->route('GET /', function(){
//    echo '<h1>Hello Team Monstera</h1>';

    //Render a view page
    $view = new Template();
    echo $view->render('views/home.html');
});

//Contact Us form
$f3->route('GET|POST /contact', function($f3){
    $view = new Template();
    echo $view->render('views/contact-form.html');
});

// Plant library
$f3->route('GET /library', function(){
    $view = new Template();
    echo $view->render('views/plant-library.html');
});

// Plant Dictionary
$f3->route('GET /dictionary', function(){
    $view = new Template();
    echo $view->render('views/plant-dictionary.html');
});


//Run Fat-Free
$f3->run();