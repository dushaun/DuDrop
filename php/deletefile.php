<?php
require_once 'core/init.php';

$user = new User();
if($user->isLoggedIn()) {
    $username = $user->data()->username;
    $path = "storage/{$username}/";

    $filename = $_GET['file']; //get the
    unlink($path.DIRECTORY_SEPARATOR.$filename); //delete it
    Redirect::to('index.php');
} else {
    echo "You're not allows to delete this file";
}

