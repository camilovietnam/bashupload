<?php

# Upload file(s) handler

# First, let's check raw input data
if ($f = fopen('php://input', 'r')) {
    readFileData($f);
}

# Next, let's move uploaded files to the storage
$id = generateId();

function readFileData($f): void
{
    // @todo: Remove if the code works (aug 1)
//    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
//    $name = trim($uri, '/');
//
//    if (!$name) {
//        $name = uniqid();
//    }

    $name = uniqid();

    $tmp = tempnam('/var/files/tmp', 'upload');

    $ftmp = fopen($tmp, 'w');
    while (!feof($f)) {
        fputs($ftmp, fgets($f));
    }

    fclose($f);
    fclose($ftmp);

    if (filesize($tmp)) {
        $_FILES[] = [
            'tmp_name' => $tmp,
            'name' => $name
        ];
    }
}

uploadFiles($id, $rewrite_id);

function uploadFiles($id, $rewriteID)
{
    foreach($_FILES as $keyFile => $file) {
        # Upload and register data
        $uploads[] = uploadFile($id, $file, $rewriteID, $keyFile);
    }
}

function uploadFile($id, $file, $rewriteId, $keyFile): array
{
    # make file name safe
    $file['name'] = str_replace(['/', '-'], '_', trim($file['name'], '/'));

    # if the file name is too long, let's just replace it with random short ID
    if (strpos($file['name'], ' ') || strlen($file['name']) > 15) {
        $file['name'] = generateId() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    }

    # move file to a final location
    $destination = STORAGE . '/' . md5('/' . $id . '-' . $file['name']);
    rename($file['tmp_name'], $destination);

    return [
        'id' => ($rewriteId ? : $id),
        'name' => $file['name'],
        'path' => $destination,
        'size' => filesize($destination),
        'upload_name' => $keyFile,
        'is_rewritten' => $rewriteId,
    ];
}