<?php

# Download file handler

# file stats
$file = extractFileData();

$filePath = STORAGE . '/' . md5($file['path']);
$file['size'] = is_file($filePath) ? filesize($filePath) : 0;

# download stats
$downloadMarkFile = $filePath . '.delete';

if (is_file($downloadMarkFile)) {
  $downloads = (int)file_get_contents($downloadMarkFile);
} else {
  $downloads = 0;
}

# title for rendering info
$title = htmlspecialchars($file['name']) . ' / download from scuti.vn';

if (!$_GET['download'] && $renderer == 'html') {
	# render
	$sorry = !$file['size'];
} else if ($file['size']) {
	# direct download
	setHeaders($file);
	file_put_contents($downloadMarkFile, $downloads + 1); 
	readfile($filePath);
	exit;
} else {
	# no file found
	display404();
}

function setHeaders($file): void
{
	header('Content-type: ' . system_extension_mime_type($file['name']));
	header('Content-Disposition: attachment; filename=' . $file['name']); 
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . $file['size']);
	header('X-Accel-Redirect: /bashupload/files/' . md5($file['path']));
} 

function display404(): void
{
	header('HTTP/1.0 404 Not Found');
	exit;
}

function extractFileData(): array
{
	$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$file = explode('/', trim($uri, BASE_FILES));
	
	return [
		'id' => $file[0],
		'name' => $file[1],
		'path' => '/' . $file[0] . '-' . $file[1],
		'extension' => strtolower(pathinfo($file[1], PATHINFO_EXTENSION))
	];
}