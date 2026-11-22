<?php
// Run from the command line: php scripts-create-admin.php admin@example.com 'your-long-password'
if(PHP_SAPI!=='cli') {http_response_code(403);exit('CLI only');}
require __DIR__.'/includes/bootstrap.php';
$email=$argv[1]??'';$password=$argv[2]??'';
if(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($password)<12){fwrite(STDERR,"Usage: php scripts-create-admin.php email@example.com password-at-least-12-chars\n");exit(1);}
$stmt=db()->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?) ON DUPLICATE KEY UPDATE password_hash=VALUES(password_hash),role=VALUES(role)');
$stmt->execute(['Store Administrator',$email,password_hash($password,PASSWORD_DEFAULT),'admin']);
echo "Admin account created/updated.\n";
