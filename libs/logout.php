<?php

session_start();
session_regenerate_id(true);
unset($_SESSION['fb_access_token']);
unset($_SESSION['google_access_token']);
unset($_SESSION['treestories_user_id']);

setcookie('message', 'User successfully logged out.', time() + 10, '/');
header("Location: /cs/list.php");
exit();
?>
