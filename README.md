qa-external-cas
###############

A custom single sign-on with CAS for Question2Answer.
Unlike [qa-external-casldap]: https://github.com/brenard/qa-external-casldap this external authentication plugin **does not use ldap**, but instead stores created and updated user data directly in the Q2A database.

The functionality of this plugin is not based on qa-external-casldap, but the phpCAS part should behave quite similar.
On the contrary, configurations of both these plugins match (except the ldap part of course) to support exchangeability and reusability. Big thanks to brenard for that plugin.


Dependencies
------------
**phpCAS**

You can get [the code](https://github.com/Jasig/phpCAS) here and
read [the documentation](https://wiki.jasig.org/display/casc/phpcas) here.


INSTALL
=======

  * make sure that phpCAS is existing
  * copy the qa-external-cas folder in you app root (i.e. "question2answer")
  * consult the qa-config.php and set external user to true: ` define('QA_EXTERNAL_USERS', true);`
  * use a symlink to point to the external user plugin of your choice: `ln -s qa-external-cas qa-external`
  * add your configuration
    * copy qa-external-cas/config.example.php to qa-external-cas/config.php: `cp qa-external-cas/config.example.php qa-external-cas/config.php`
    * configure it.

  
Configuration
-------------

This is based on qa-external-casldap and enhanced with custom field names for the CAS user data (e.g. "additional attributes" when configured with rubyCAS/LDAP)

  * **CAS_HOST** : Hostname of CAS server (ex : _cas.example.com_)
  * **CAS_PORT** : HTTP (or HTTPS) port of CAS server (ex : _443_)
  * **CAS_CTX** : URL context path of CAS server (ex: /cas)
  * **CAS_VER** : CAS protocol version. Possible values :  *CAS_VERSION_1_0* or *CAS_VERSION_2_0*
  * **CAS_CA_CERT_FILE** : SSL certificate path of CAS server. If empty, the SSL certificate will not be validated.
  * **$CAS_USERS_ROLE** : PHP array listing specific user role. Users's role must be define using Q2A constant : _QA_USER_LEVEL_BASIC, QA_USER_LEVEL_EDITOR, QA_USER_LEVEL_ADMIN, QA_USER_LEVEL_SUPER_ (ex : _array('user1' => QA_USER_LEVEL_SUPER,'user2' => QA_USER_LEVEL_EDITOR)_)
  * **CAS_DEFAULT_USER_ROLE** : User default role define using Q2A constant (see _$CAS_USERS_ROLE_)

Additional configuration:

  * **CAS_SERVICE_LOGIN** : relative, absolute path or FQDN url to you Cas login service
  * **CAS_SERVICE_LOGOUT** : relative, absolute path or FQDN url to you Cas logout service
  * **CAS_ATTRIBUTE_MAIL** : defines the name of the mail attribute retrieved by CAS. See your CAS server configuration and the example.config for more info. Defaults to "mail"
  * **CAS_ATTRIBUTE_FULLNAME** : defines the name of the "full name" attribute retrieved by CAS. Defaults to "cn"


Question2Answer
===============


[qa-external-casldap]: https://github.com/brenard/qa-external-casldap
[Question2Answer]: http://www.question2answer.org/
[CONTRIBUTING]: https://github.com/q2a/question2answer/blob/master/CONTRIBUTING.md
=======
A custom single sign-on via CAS for Question2Answer.



