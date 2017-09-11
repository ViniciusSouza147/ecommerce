<?php 

require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;

$app = new Slim();

$app->config('debug', true);
/*==============================*/
// Start Route site Ecommerce
$app->get('/', function() {
    
    $page = new Page();
    $page->setTpl("index");

});
//End Route site Ecommerce 

/*==============================*/

//Start Route site Admin
$app->get('/admin', function() {
    
    $page = new PageAdmin();
    $page->setTpl("index");

});
//End Route site Admin 
/*==============================*/

$app->run();

 ?>