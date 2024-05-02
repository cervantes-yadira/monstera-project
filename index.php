<?php

//This is my controller

//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Require
require_once ('vendor/autoload.php');

//Instantiate the F3 Base Class
$f3 = Base::instance();

//Define a default route
//https://kcheng.greenriverdev.com/328/hello-fat-free/
$f3->route('GET /', function(){
    //echo below is used for testing before executing the template
    echo '<h1>Hello Team Monstera</h1>';

    //Render a view page
    $view = new Template();
    echo $view->render('views/home.html');
});

////Order Page
//$f3->route('GET|POST /order', function($f3){
//    //Check if the form has been posted
//    if($_SERVER['REQUEST_METHOD'] == 'POST') {
//
//        //get the data
//        $pet = $_POST['pet'];
//        $color = $_POST['color'];
//
//        echo "post method";
//        //validate the data
//        if (empty($pet)) {
//            echo "Please supply a pet type";
//        } else {
//            echo "get method";
//            $f3->set('SESSION.pet', $pet);
//            $f3->set('SESSION.color', $color);
//
//            $f3->reroute('summary');
//
//        }
//    }
//
//    //Render a view page
//    $view = new Template();
//    echo $view->render('views/pet-order.html');
//});
//
//$f3->route('GET /summary', function(){
//    //echo below is used for testing before executing the template
////    echo '<h1>Hello Pets 2</h1>';
//
//    //Render a view page
//
//    var_dump($_POST['color']);
//    var_dump($_POST['pet']);
//    $view = new Template();
//    echo $view->render('views/order-summary.html');
//});

//Run Fat-Free
$f3->run();