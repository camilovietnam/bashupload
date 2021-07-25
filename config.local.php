<?php


# Load local config file (ignored in GIT) if available
if ( (basename(__FILE__) != 'config.local.php') && is_file(__DIR__ . '/config.local.php') ) {
  require __DIR__ . '/config.local.php';
  return;
}


# what is the main uri of the WEB folder?
define('BASE_WEB', '/bashupload/web/');

# where files will recide (make sure it has writable permissions)
define('STORAGE', '/home/ec2-user/bashupload/files');

# should we redirect user to SSL version of the website (only on GET requests)
define('FORCE_SSL', false);

# How many days should we keep files?
define('EXPIRE_DAYS', 3);

# How many downloads should we allow?
define('MAX_DOWNLOADS', 1);

# Our website host
define('HOST', @$_SERVER['HTTP_HOST']);

# that's just to reset css/js cache on changes
define('STATIC_VERSION', 7);

# is this available on the web? (will add meta tags and logo)
define('WEB', true);
