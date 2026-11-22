<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
$allowed=['home','shop','product','cart','checkout','orders','about','contact','login','register','logout','admin'];
$page=(string)($_GET['page']??'home');if(!in_array($page,$allowed,true)){$page='404';http_response_code(404);}
if($page==='logout'){if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);exit('Method not allowed');}verify_csrf();$_SESSION=[];session_regenerate_id(true);redirect();}
if($_SERVER['REQUEST_METHOD']==='POST') require_once __DIR__.'/includes/actions.php';
// Guard routes BEFORE emitting any HTML (redirect headers must still work).
if(in_array($page,['checkout','orders'],true))require_login();
if($page==='admin')require_admin();
if(in_array($page,['login','register'],true)&&auth())redirect('orders');
$names=['home'=>'Home','shop'=>'Shop the menu','product'=>'Product','cart'=>'Your Bag','checkout'=>'Checkout','orders'=>'My Orders','about'=>'Our Story','contact'=>'Contact Us','login'=>'Sign In','register'=>'Create Account','admin'=>'Admin Dashboard','404'=>'Page not found'];$title=$names[$page];
require __DIR__.'/includes/header.php';
require __DIR__.'/pages/'.$page.'.php';
require __DIR__.'/includes/footer.php';
