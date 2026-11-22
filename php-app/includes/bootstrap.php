<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
 session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off','samesite'=>'Lax']);
 session_start();
}
$configPath=dirname(__DIR__).'/config.php';
if (!is_file($configPath)) {http_response_code(503);exit('Configuration missing. Copy config.example.php to config.php and configure MySQL.');}
$config=require $configPath;
require_once __DIR__.'/functions.php';
set_exception_handler(function(Throwable $e):void { error_log((string)$e); http_response_code(500); echo '<h1>Something went wrong</h1><p>Please try again later.</p>'; });
