<?php
class FileDirectory {
    public function createDirectory($username) {
        mkdir("storage/{$username}");
    }

    public function viewDirectory($username) {
        $dir = "storage/{$username}"; // Directory where files are stored

        if ($dir_list = opendir($dir))
        {
            while(($filename = readdir($dir_list)) != false)
            {
                ?>
                <p><a href="<?php echo $filename; ?>"><?php echo $filename;
                        ?></a></p>
            <?php
            }
            closedir($dir_list);
        }
    }

    public function uploadFile($username) {
        $uploadLocation = "storage/{$username}";
        $message = "";

        if(!file_exists($uploadLocation)) {
            Redirect::to(404);
        } else {
            $valid_file=false;

            //if they DID upload a file...
            if ($_FILES["{$username}file"]['name']) {
                //if no errors...
                if (!$_FILES["{$username}file"]['error']) {
                    //now is the time to modify the future file name and validate the file
                    $new_file_name = strtolower($_FILES["{$username}file"]['tmp_name']); //rename file
                    if ($_FILES["{$username}file"]['size'] > (1024000)) //can't be larger than 1 MB
                    {
                        $valid_file = false;
                        $message = 'Oops!  Your file\'s size is to large.';
                    }

                    //if the file has passed the test
                    if ($valid_file) {
                        //move it to where we want it to be
                        move_uploaded_file($_FILES["{$username}file"]['tmp_name'], $uploadLocation . $new_file_name);
                        $message = 'Congratulations!  Your file was accepted.';
                    }
                } //if there is an error...
                else {
                    //set that to be the returned message
                    $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['photo']['error'];
                }
            }
        }
    }

    public function deleteFile() {

    }

    public function renameFile() {
        
    }
}