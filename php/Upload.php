<?php
require_once "core/init.php";

$user = new User();
$username = $user->data()->username;

$target_dir = "storage/{$username}/";
$target_file = $target_dir . basename($_FILES["{$username}file"]["name"]);
$uploadOk = 1;
$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

if (file_exists($target_file)) {
    echo "Sorry, file already exists. <br>";
    $uploadOk = 0;
}

if ($_FILES["{$username}file"]["size"] > 1024000) {
    echo "Sorry, your file is too large. Maximum size is 1MB. <br>";
    $uploadOk = 0;
}

if($fileType != 'jpg' && $fileType != 'png' && $fileType != 'jpeg' && $fileType != 'gif' &&
    $fileType != 'doc' && $fileType != 'docx' && $fileType != 'ppt' && $fileType != 'pptx' &&
    $fileType != 'xls' && $fileType != 'xlsx' && $fileType != 'pdf' && $fileType != 'avi' &&
    $fileType != 'mp4' && $fileType != 'mov' && $fileType != 'wmv' && $fileType != 'mp3' &&
    $fileType != 'wav' && $fileType != 'wma' && $fileType != 'txt') {
    echo "Sorry, that file type isn't allowed on our systems dude! <br>";
    $uploadOk = 0;
}

if($uploadOk == 0){
    echo "Sorry, your file wasn't uploaded bro! <br>";
    echo "<p><a href='uploadfile.php'>Click Here!</a> to try again.</p>";
} else {
    if (move_uploaded_file($_FILES["{$username}file"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["{$username}file"]["name"]). " has been uploaded." . "<br>";
        echo "Redirecting you back to your home page bro!";
        sleep(5);
        Redirect::to('index.php');
        //echo "<p><a href='index.php'>Click Here!</a> to get back home.</p>";
    } else {
        echo "Sorry, there was an error uploading your file. <br>";
        echo "<p><a href='uploadfile.php'>Click Here!</a> to try again.</p>";
    }
}
