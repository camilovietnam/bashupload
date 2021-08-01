<?php



# Configure
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

# Route
$docs_handler = __DIR__ . '/../../bashupload-docs/index.php';
$action = 'default';

if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
  $action = 'upload';
} elseif ($uri != BASE_WEB) {
    $action = 'file';
}

# Execute routed handler
$accept = explode(',', $_SERVER['HTTP_ACCEPT']);

if (isset($_POST['json']) && $_POST['json'] == 'true') {
    $renderer = 'json';
} elseif (in_array('text/html', $accept)) {
    $renderer = 'html';
} else {
    $renderer = 'txt';
}

$action_handler = __DIR__ . "/../actions/{$action}.php";
if (is_file($action_handler)) {
  include $action_handler;
}

# Render
include __DIR__ . "/../render/{$renderer}.php";
