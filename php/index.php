<?php
require_once 'core/init.php';

if(Session::exists('home')) {
    echo '<p>'. Session::flash('home') .'</p>';
}

$user = new User();

$dir = new FileDirectory();
if($user->isLoggedIn()) {
    $username = $user->data()->username;
    ?>
    <html>
    <head>
        <meta name="theme-color" content="#84aca4">

        <link rel="shortcut icon" href="images/favicon.ico">

        <link href="bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="bootstrap/css/stylesheet.css" type="text/css" rel="stylesheet">
        <link href="jTable/jquery.bootgrid.css" type="text/css" rel="stylesheet">
        <link href="jTable/jquery.bootgrid.min.css" type="text/css" rel="stylesheet">

        <script src="jTable/jquery.js" type="text/javascript"></script>
        <script src="jTable/jquery.bootgrid.js" type="text/javascript"></script>
        <script src="jTable/jquery.bootgrid.min.js" type="text/javascript"></script>
    </head>
    <body>
    <div id="wrap">
        <!-- Navigation -->
        <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="glyphicon glyphicon-th"></i></a>
        <nav id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="glyphicon glyphicon-remove"></i></a>
                <li class="sidebar-brand">
                    <a href="#top"><strong>DuDrop</strong></a>
                </li>
                <li>
                    <a href="profile.php?user=<?php echo escape($user->data()->username)?>">Hello! <?php echo escape($user->data()->username)?>!</a>
                </li>
                <li>
                    <a href="logout.php">Log out</a>
                </li>
                <li>
                    <a href="update.php">Update Details</a>
                </li>
                <li>
                    <a href="changepassword.php">Change Your Password</a>
                </li>
                <li>
                    <a href="uploadfile.php">Upload a File</a>
                </li>
                <li>
                    <a href="#footer">Extended Menu</a>
                </li>
            </ul>
        </nav>

        <!-- Table Section -->
        <section id="top" class="dudrop-table">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h1>"<?php echo escape($user->data()->username)?>'s" Stuff!</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <table id="grid" class="table table-condensed table-hover table-striped">
                            <thead>
                            <tr>
                                <th data-column-id="filename" data-order="desc" data-formatter="filename" data-identifier="true" data-sortable="false">Filename</th>
                                <th data-column-id="type">Type</th>
                                <th data-column-id="size">Size</th>
                                <th data-column-id="date-modified">Date Modified</th>
                                <th data-column-id="delete">Delete File?</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php


                            // Adds pretty filesizes
                            function pretty_filesize($file) {
                                $size = filesize($file);
                                if($size < 1024){$size = $size." Bytes";}
                                elseif(($size<1048576)&&($size>1023)){$size=round($size/1024, 1)." KB";}
                                elseif(($size<1073741824)&&($size>1048575)){$size=round($size/1048576, 1)." MB";}
                                else{$size=round($size/1073741824, 1)." GB";}
                                return $size;
                            }



                            // Checks to see if veiwing hidden files is enabled
                            if($_SERVER['QUERY_STRING']=="hidden")
                            {$hide="";
                                $ahref="./";
                                $atext="Hide";}
                            else
                            {$hide=".";
                                $ahref="./?hidden";
                                $atext="Show";}



                            // Opens directory
                            $path = "storage/{$username}/";
                            //$myDirectory=opendir("$path");
                            $myDirectory=opendir("storage/{$username}/");

                            // Gets each entry
                            while($entryName=readdir($myDirectory)) {
                                $dirArray[]=$entryName;
                            }
                            //var_dump($dirArray);
                            // Closes directory
                            closedir($myDirectory);

                            // Counts elements in array
                            $indexCount=count($dirArray);


                            // Sorts files
                            sort($dirArray);

                            // Loops through the array of files
                            for($index=0; $index < $indexCount; $index++) {


                                // Decides if hidden files should be displayed, based on query above.
                                if(substr("$dirArray[$index]", 0, 1)!=$hide) {

                                    // Resets Variables
                                    $favicon="";
                                    $class="file";

                                    // Gets File Names
                                    //$name = $path.$dirArray[$index];
                                    $name = $dirArray[$index];
                                    $namehref = $path.$dirArray[$index];

                                    // Gets Date Modified
                                    $modtime=date("M j Y g:i A", filemtime($path.$dirArray[$index]));
                                    $timekey=date("YmdHis", filemtime($path.$dirArray[$index]));


                                    // Separates directories, and performs operations on those directories
                                    if(is_dir($dirArray[$index]))
                                    {
                                        $extn="&lt;Directory&gt;";
                                        $size="&lt;Directory&gt;";
                                        $sizekey="0";
                                        $class="dir";

                                        // Gets favicon.ico, and displays it, only if it exists.
                                        if(file_exists("$namehref/favicon.ico"))
                                        {
                                            $favicon=" style='background-image:url($namehref/favicon.ico);'";
                                            $extn="&lt;Website&gt;";
                                        }

                                        // Cleans up . and .. directories
                                        if($name=="."){$name=". (Current Directory)"; $extn="&lt;System Dir&gt;"; $favicon=" style='background-image:url($namehref/.favicon.ico);'";}
                                        if($name==".."){$name=".. (Parent Directory)"; $extn="&lt;System Dir&gt;";}
                                    }

                                    // File-only operations
                                    else{
                                        // Gets file extension
                                        $extn=pathinfo($dirArray[$index], PATHINFO_EXTENSION);

                                        // Prettifies file type
                                        switch ($extn){
                                            case "png": $extn="PNG Image"; break;
                                            case "jpg": $extn="JPEG Image"; break;
                                            case "jpeg": $extn="JPEG Image"; break;
                                            case "svg": $extn="SVG Image"; break;
                                            case "gif": $extn="GIF Image"; break;
                                            case "ico": $extn="Windows Icon"; break;

                                            case "txt": $extn="Text File"; break;
                                            case "log": $extn="Log File"; break;
                                            case "htm": $extn="HTML File"; break;
                                            case "html": $extn="HTML File"; break;
                                            case "xhtml": $extn="HTML File"; break;
                                            case "shtml": $extn="HTML File"; break;
                                            case "php": $extn="PHP Script"; break;
                                            case "js": $extn="Javascript File"; break;
                                            case "css": $extn="Stylesheet"; break;

                                            case "pdf": $extn="PDF Document"; break;
                                            case "xls": $extn="Spreadsheet"; break;
                                            case "xlsx": $extn="Spreadsheet"; break;
                                            case "doc": $extn="Microsoft Word Document"; break;
                                            case "docx": $extn="Microsoft Word Document"; break;

                                            case "zip": $extn="ZIP Archive"; break;
                                            case "htaccess": $extn="Apache Config File"; break;
                                            case "exe": $extn="Windows Executable"; break;

                                            default: if($extn!=""){$extn=strtoupper($extn)." File";} else{$extn="Unknown";} break;
                                        }

                                        // Gets and cleans up file size
                                        $size=pretty_filesize($path.$dirArray[$index]);
                                        $sizekey=filesize($path.$dirArray[$index]);
                                    }

                                    $varlocation = $name;
                                    //echo 'is the table being displayed?';
                                    // Output
                                    echo("
                                    <tr class='$class'>
                                        <td><a href='./$namehref'$favicon class='name'>$name</a></td>
                                        <td>$extn</td>
                                        <td sorttable_customkey='$sizekey'>$size</td>
                                        <td sorttable_customkey='$timekey'>$modtime</td>
                                        <td><a href='deletefile.php?file=".$name."'><i class='glyphicon glyphicon-trash'></i></a></td>
                                    </tr>");
                                }
                            }
                            ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left footer-row">
                        <p>2014 &copy; DuShaun Alderson-Claeys</p>
                    </div>
                    <div class="pull-right footer-row">
                        <p><a href="#top" title="Scroll to top" class="page-scroll pull-right"><i class="glyphicon glyphicon-chevron-up"></i></a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="bootstrap/js/jquery-latest.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/javascript.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
    </html>
    <?php

    if($user->hasPermission('admin')) {
        //echo "<p>You're an Admin dude!</p>";
        Redirect::to('admin.php');
    }

} else {
    ?>
    <html>
    <head>
        <meta name="theme-color" content="#84aca4">

        <link rel="shortcut icon" href="images/favicon.ico">

        <link href="bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <link href="bootstrap/css/stylesheet.css" type="text/css" rel="stylesheet">
        <link href="jTable/jquery.bootgrid.css" type="text/css" rel="stylesheet">
        <link href="jTable/jquery.bootgrid.min.css" type="text/css" rel="stylesheet">

        <script src="jTable/jquery.js" type="text/javascript"></script>
        <script src="jTable/jquery.bootgrid.js" type="text/javascript"></script>
        <script src="jTable/jquery.bootgrid.min.js" type="text/javascript"></script>
    </head>
    <body>
    <div id="wrap">
        <!-- Navigation -->
        <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="glyphicon glyphicon-th"></i></a>
        <nav id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="glyphicon glyphicon-remove"></i></a>
                <li class="sidebar-brand">
                    <a href="#top"><strong>DuDrop</strong></a>
                </li>
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="#footer">Extended Menu</a>
                </li>
            </ul>
        </nav>

        <!-- Option Panel -->
        <section class="option-panel">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2>You gotta do ONE of TWO things dude!</h2>
                    </div>
                </div>
                <div class="row text-center button-one">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <a href="login.php"><button class="btn btn-lg btn-primary btn-block">Log In!</button></a>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="row text-center button-two">
                    <div class="col-lg-3"></div>
                    <div class="col-lg-6">
                        <a href="register.php"><button class="btn btn-lg btn-primary btn-block">Register!</button></a>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
            </div>
        </section>

    </div>

    <footer id="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-left footer-row">
                        <p>2014 &copy; DuShaun Alderson-Claeys</p>
                    </div>
                    <div class="pull-right footer-row">
                        <p><a href="#top" title="Scroll to top" class="page-scroll pull-right"><i class="glyphicon glyphicon-chevron-up"></i></a></p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="bootstrap/js/jquery-latest.min.js" type="text/javascript"></script>
    <script src="bootstrap/js/javascript.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.js" type="text/javascript"></script>
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    </body>
    </html>
    <?php
}

