<?php

define('QA_VERSION', 'yes');

require_once 'config.php';

phpCAS::client(CAS_VER, CAS_HOST, CAS_PORT, CAS_CTX);
	
unset($_SESSION['UserInfos']);
session_destroy();
header('Location: '.CAS_SERVICE_LOGOUT);
phpCAS::logout();

?>
