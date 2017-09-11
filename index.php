<?php 

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

//validate login and password.
$app->get('/admin/login', function() {

	User::login($_POST["login"], $_POST["password"]);
	header("Locarion: /admin");
	exit;

});
//Ends the login page routes. 
/*==============================*/

$app->run();

 ?>