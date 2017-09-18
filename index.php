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
$app->get('/', function()
{
    
    $page = new Page();
    $page->setTpl("index");

});
//End the Ecommerce site route. 

/*==============================*/

//Starts admin site route.
$app->get('/admin', function() 
{
    
    User::verifyLogin();
    $page = new PageAdmin();
    $page->setTpl("index");

});
//Ends the admin site route. 

/*==============================*/

//Starts the login page routes.
$app->get('/admin/login', function()
{
    
    $page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);
    $page->setTpl("login");
    exit;
});

//Method Post - Validate login and password.

$app->post('/admin/login', function()
{

	User::login($_POST["login"], $_POST["password"]);
	header("Location: /admin");
	exit;

});
//Ends the login page routes. 

//Logout the admin site route.
$app->get('/admin/logout', function()
{

	User::logout();
	header("Location: /admin/login");
	exit;

});
/*==================================*/

// =========== Starts user CRUD rotes ============= //
//Start the route user page.
$app->get("/admin/users", function()
{

	User::verifyLogin();
	$users = User::listAll();
	$page = new PageAdmin();
	$page->setTpl("users", array(
		"users"=>$users
	));

});

// Criate User.
$app->get("/admin/users/create", function()
{

	User::verifyLogin();
	$page = new PageAdmin();
	$page->setTpl("users-create");

});

// Delete User
$app->get("/admin/users/:iduser/delete", function($iduser)
{
	
	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$user->delete();
	header("Location: /admin/users");
	exit;

});

//Update User.
$app->get("/admin/users/:iduser", function($iduser)
{

	User::verifyLogin();
	$user = new User();
	$user->get((int)$iduser);
	$page = new PageAdmin();
	$page->setTpl("users-update", array(
		"user"=>$user->getValues()		
	));

});

// Insert User
$app->post("/admin/users/create", function()
{

	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->setData($_POST);
	$user->save();
	header("Location: /admin/users");
	exit;

});

// To save User
$app->post("/admin/users/:iduser", function($iduser)
{
	
	User::verifyLogin();
	$user = new User();
	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;
	$user->get((int)$iduser);
	$user->setData($_POST);
	$user->update();

	header("Location: /admin/users");
	exit;

});

$app->get("/admin/forgot", function()
{

	$page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);
    $page->setTpl("forgot");
    exit;

});

$app->post("/admin/forgot", function()
{

	$user = User::getForgot($_POST["email"]);
	header("Location: /admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function()
{

	$page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);
    $page->setTpl("forgot-sent");
    exit;

});

$app->get("/admin/forgot/reset", function()
{
	
	$user = User::validForgotDecrypt($_GET["code"]);

	$page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);

    $page->setTpl("forgot-reset", array(
    	"name"=>$user["desperson"],
    	"code"=>$_GET["code"]
    ));

});

$app->post("/admin/forgot/reset", function()
{

	$forgot = User::validForgotDecrypt($_POST["code"]);
	User::setForgotUser($forgot["idrecovery"]);
	$user = new User();
	$user->get((int)$forgot["iduser"]);
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, 
	[
		"cost"=>12
	]);
	$user->setPassword($password);

	$page = new PageAdmin([
    	"header"=>false,
    	"footer"=>false
    ]);

    $page->setTpl("forgot-reset-success");

});

$app->run();

 ?>