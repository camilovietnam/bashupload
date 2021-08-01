<?php

# Default page handler
# redirect to SSL if necessary (only GET requests)
if  (needsRedirectToHTTPS()) {
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: ' . 'https://' . HOST . $_SERVER['REQUEST_URI']);
      exit;
}

function needsRedirectToHTTPS(): bool
{
    return FORCE_SSL
        && $_SERVER['REQUEST_METHOD'] === 'GET'
        && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off");
}