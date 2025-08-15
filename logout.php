<?php
session_start();
session_unset();
session_destroy();
header('Location: authentication-login.php'); // If login is in LMS_1 folder
exit();
session_start();
session_unset();
session_destroy();
header('Location: authentication-login.php');
exit;
