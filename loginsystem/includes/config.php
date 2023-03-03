<?php
define('DB_SERVER','localhost');
define('DB_USER','root');
define('DB_PASS' ,'Ranosys@123');
define('DB_NAME', 'usermanagement_system');
define('SITE_URL', 'http://localhost/template/User-Registration-and-login-System-with-admin-panel/loginsystem/');
define('ADMIN_URL', 'http://localhost/template/User-Registration-and-login-System-with-admin-panel/loginsystem/admin/');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);

// Check connection
if (mysqli_connect_errno())
{
echo "Failed to connect to MySQL: " . mysqli_connect_error();
 }

?>

