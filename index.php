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
require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';
//require_once ('models/validate.php');
//require_once ('classes/user.php');
//require_once ('classes/member.php');
//require_once ('controllers/controller.php');
//var_dump(Validate3::validPassword('Abcd13457', 'Abcd13456'));

$f3 = Base::instance();
$con = new Controller3($f3);
$dataLayer = new DataLayer3();

// home page
$f3->route('GET /', function()
{
    $GLOBALS['con']->home();
});

//Sign Up form
$f3->route('GET|POST /sign-up', function($dbh)
{
    $GLOBALS['con']->signUp($dbh);
});

//Sign In form
$f3->route('GET|POST /sign-in', function()
{
    $GLOBALS['con']->signIn();
});

// sign out
$f3->route('GET /sign-out', function()
{
    $GLOBALS['con']->signOut();
});

//Contact Us form
$f3->route('GET|POST /contact', function()
{
    $GLOBALS['con']->contactUs();
});

// Plant library
$f3->route('GET /library', function()
{
    $GLOBALS['con']->plantLibrary();
});

// Water Plant
$f3->route('GET /water', function()
{
    $GLOBALS['con']->waterPlant();
});

// Add Plant
$f3->route('GET|POST /add', function()
{
    $GLOBALS['con']->addPlant();
});

// View Plant
$f3->route('GET /view', function()
{
    $GLOBALS['con']->viewPlant();
});

// Plant Dictionary
$f3->route('GET /dictionary', function()
{
    $GLOBALS['con']->plantDictionary();
});

//Run Fat-Free
$f3->run();