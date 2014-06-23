<?php

 /*
  * Path of PHPCAS librairy
  *
  * You need to specify the path of the CAS.php file.
  * 
  * On Debian (from Wheezy upwards) or Ubuntu, install php-cas package and specify
  * /usr/share/php/CAS.php
  */
define('PHPCAS_PATH','/usr/share/php/CAS.php');

// Loading PHP CAS library
require_once PHPCAS_PATH;

/*
 * CAS Configuration
 */

// Hostname of CAS server (ex: cas.example.com)
define('CAS_HOST','cas.example.com');

// HTTP (or HTTPS) port of CAS Server (ex: 443)
define('CAS_PORT',443);

// URL context of CAS Server (ex: /cas)
define('CAS_CTX','/cas');

// CAS protocol version
// Possible values : CAS_VERSION_1_0 or CAS_VERSION_2_0
define('CAS_VER',CAS_VERSION_2_0);

// SSL certificate path of CAS server
// If empty, CAS server's certificate will not be validated.
define('CAS_CA_CERT_FILE','');

// List user's role
// Roles : QA_USER_LEVEL_BASIC, QA_USER_LEVEL_EDITOR, QA_USER_LEVEL_ADMIN, QA_USER_LEVEL_SUPER
$CAS_USERS_ROLE=array(
	'qauser1' => QA_USER_LEVEL_SUPER,
	'qauser2' => QA_USER_LEVEL_EDITOR,
);

// CAS default user role
define('CAS_DEFAULT_USER_ROLE',QA_USER_LEVEL_BASIC);


//define CAS login/logout paths

define('CAS_SERVICE_LOGIN', '/cas/login');
define('CAS_SERVICE_LOGOUT', '/cas/logout');


/*
 * CAS field mapping
 * 
 * If there is the need of using fullnames and email addresses retrieved by CAS, you should override the settings to match you CAS server.
 * For example, when using rubyCas there might be a config.yml like this to support extra_attributes to be sent.

 authenticator:
  class: CASServer::Authenticators::LDAP
  ldap:
    host: example.org
    ...
  extra_attributes: cn, mail, givenName, sn, dn

 */
  // users email address
  define('CAS_ATTRIBUTE_MAIL', 'mail');

  // users full name
  define('CAS_ATTRIBUTE_FULLNAME', 'cn');

