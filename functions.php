<?php

function createDir($currentPath, $rootDir)
{
    $directoryName = $_POST['directoryName'];
    if ($directoryName == '') {
        return 'Enter name for new directory!';
    }
    if (is_dir($rootDir . (isset($_GET['path']) ? $_GET['path'] : '') . '/' . $directoryName)) {
        return 'Directory ' . $directoryName . ' already exists!';
    }
    mkdir($currentPath . '/' . $directoryName);
    return 'Directory ' . $directoryName . ' was succesfuly created!';
}

function deleteFile($currentPath)
{
    $ext = pathinfo($_POST['delete'], PATHINFO_EXTENSION);
    $protectExtensions = array('php', 'css', 'md');
    if (in_array($ext, $protectExtensions) === false) {
        if (empty($_GET['path'])) {
            unlink($_POST['delete']);
            return 'File deleted!';
        }
        unlink($currentPath . '/' . $_POST['delete']);
        return 'File deleted!';
    }
}

function uploadFile($diff)
{
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];

    // check extension (and only permit jpegs, jpgs and pngs)
    $explode = explode('.', $_FILES['image']['name']);
    $end = end($explode);
    $file_ext = strtolower($end);

    $extensions = array('jpeg', 'jpg', 'png');
    if (!in_array($file_ext, $extensions)) {
        $errors[] = 'extension not allowed, please choose a JPEG or PNG file.';
    }
    if ($file_size > 2097152) {
        $errors[] = 'File size must be exactly 2 MB';
    }
    if (empty($errors)) {
        move_uploaded_file($file_tmp, './' . $diff . '/' . $file_name);
        return 'Success';
    }
    return 'extension not allowed, please choose a JPEG or PNG file.';
}

function downloadFile($filePath) {
    ob_clean();
    ob_start();
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename=' . basename($filePath));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    ob_end_flush();

    readfile($filePath);
}
