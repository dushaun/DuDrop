<?php
require_once 'core/init.php';

$user = new User();
$username = $user->data()->username;
$upload = new FileDirectory();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
?>

<html>
<head>
    <meta name="theme-color" content="#84aca4">

    <link rel="shortcut icon" href="images/favicon.ico">

    <link href="bootstrap/css/bootstrap.css" type="text/css" rel="stylesheet">
    <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
    <link href="bootstrap/css/stylesheet.css" type="text/css" rel="stylesheet">
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

    <!-- Login Div -->
    <section id="top" class="signin">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Sign In!</h1>
                </div>
            </div>
            <div class="row">
                <div id="form" class="col-lg-12 text-center">
                    <form action="Upload.php" method="post" class="form-signin" enctype="multipart/form-data">
                        <h2 class="form-signin-heading">Upload A File</h2>

                        <span class="btn btn-lg btn-warning btn-block">
                            <i class='glyphicon glyphicon-plus'></i>
                            <span>Select Files...</span>
                            <input type="file" name="<?php echo$username?>file"/>
                        </span>

                        <button class="btn btn-lg btn-warning btn-block" type="submit" value="submit">Start Upload!</button>
                    </form>
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


    <form method="post" action="Upload.php" enctype="multipart/form-data">
        <p><label for="name">Select file</label><br />
            <input type="file" name="<?php echo$username?>file"/></p>
        <p><input type="submit" name="submit" value="Start upload" /></p>
    </form>