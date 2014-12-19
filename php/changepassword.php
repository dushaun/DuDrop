<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'password_current' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new_again' => array(
                'required' => true,
                'min' => 6,
                'matches' => 'password_new'
            )
        ));

        if($validation->passed()) {
            if(Hash::make(Input::get('password_current'), $user->data()->salt) !== $user->data()->password) {
                echo 'Your current password is wrong dude!';
            } else {
                $salt = Hash::salt(32);
                $user->update(array(
                    'password' => Hash::make(Input::get('password_new'), $salt),
                    'salt' => $salt
                ));
                Session::flash('home', 'Your password has been changed!');
                Redirect::to('index.php');
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
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
                    <h1>Change Your Password!</h1>
                </div>
            </div>
            <div class="row">
                <div id="form" class="col-lg-12 text-center">
                    <form action="" method="post" class="form-signin">
                        <h2 class="form-signin-heading">Change your Password here</h2>
                        <div class="field">
                            <!--<label for="username">Username</label>-->
                            <input class="form-control" placeholder="Write your Current Password" type="password" name="password_current" id="password_current" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Write your New Password" type="password" name="password_new" id="password_new" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Write your New Password again" type="password" name="password_new_again" id="password_new_again" autocomplete="off">
                        </div>

                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <button class="btn btn-lg btn-warning btn-block" type="submit" value="Change">Change Your Password!</button>
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
<!--
<form action="" method="post">
    <div class="field">
        <label for="password_current">Current Password</label>
        <input type="password" name="password_current" id="password_current">
    </div>

    <div class="field">
        <label for="password_new">New Password</label>
        <input type="password" name="password_new" id="password_new">
    </div>

    <div class="field">
        <label for="password_new_again">New Password</label>
        <input type="password" name="password_new_again" id="password_new_again">
    </div>

    <input type="hidden" name="token" value="<?php// echo Token::generate(); ?>">
    <input type="submit" value="Change">
</form>-->