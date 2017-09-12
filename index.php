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

//Logout the admin site route.
$app->get('/admin/logout', function(){

	User::logout();
	header("Location: /admin/login");
	exit;

});
/*==================================*/

// =========== Starts user CRUD rotes ============= //
//Start the route user page.
$app->get("/admin/users", function(){

	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));

});

// Criate User.
$app->get("/admin/users/create", function(){

	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");

});

// Delete User
$app->get("/admin/users/:iduser/delete", function($iduser){
	User::verifyLogin();
});

//Update User.
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-update");

});

// Insert User
$app->post("/admin/users/create", function(){
	User::verifyLogin();
});

// To save User
$app->post("/admin/users/:iduser", function($iduser){
	User::verifyLogin();
});

$app->run();

 ?>