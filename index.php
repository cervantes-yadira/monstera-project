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
require_once ('models/validate.php');
require_once ('classes/user.php');
require_once ('classes/member.php');
require_once ('controllers/controller.php');
//var_dump(Validate3::validPassword('Abcd13457', 'Abcd13456'));

$f3 = Base::instance();
$con = new Controller3($f3);

$f3->route('GET /', function(){
    $GLOBALS['con']->home();
});

//Sign Up form
$f3->route('GET|POST /sign-up', function(){
    $GLOBALS['con']->signUp();
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