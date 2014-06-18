<?php

define('QA_VERSION', 'yes');

require_once 'config.php';
//	require_once '/srv/question2answer/var/www/question2answer/qa-include/qa-db-users.php';

//require_once 'qa-external-users.php';

phpCAS::client(CAS_VER, CAS_HOST, CAS_PORT, CAS_CTX);
	

//echo "logged out";

unset($_SESSION['UserInfos']);
session_destroy();
header('Location: '.'../cas/logout');
phpCAS::logout();

?>
