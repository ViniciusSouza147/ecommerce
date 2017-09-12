<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use \Hcode\Model\User;


$app = new Slim();

$app->config('debug', true);
/*==============================*/
// Starts the ecommerce site route.
$app->get('/', function() {
    
    $page = new Page();
    $page->setTpl("index");

});
//End the Ecommerce site route. 

/*==============================*/

//Starts admin site route.
$app->get('/admin', function() {
    
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("index");

});
//Ends the admin site route. 

/*==============================*/

//Starts the login page routes.
$app->get('/admin/login', function() {
    
    $page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);
    $page->setTpl("login");
    exit;
});

//Method Post - Validate login and password.

$app->post('/admin/login', function() {

	User::login($_POST["login"], $_POST["password"]);
	header("Location:/admin");
	exit;

});
//Ends the login page routes. 

/*==============================*/

//Logout the admin site route.
$app->get('/admin/logout', function(){

	User::logout();
	header("Location: /admin/login");
	exit;

});

$app->run();

 ?>