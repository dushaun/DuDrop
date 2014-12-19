<?php
require_once 'core/init.php';

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array('required' => true),
            'password' => array('required' => true)
        ));

        if($validation->passed()) {
            $user = new User();

            $remember = (Input::get('remember') === 'on') ? true : false;
            $login = $user->login(Input::get('username'), Input::get('password'), $remember);

            if($login) {
                Redirect::to('index.php');
            } else {
                echo '<p>Sorry, login attempt failed.<br>Please try again.</p>';
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
                        <h1>Sign In!</h1>
                    </div>
                </div>
                <div class="row">
                    <div id="form" class="col-lg-12 text-center">
                        <form action="" method="post" class="form-signin">
                            <h2 class="form-signin-heading">Access Your Storage</h2>
                            <div class="field">
                                <!--<label for="username">Username</label>-->
                                <input class="form-control" placeholder="Username" type="text" name="username" id="username" autocomplete="off">
                            </div>

                            <div class="field">
                                <!--<label for="password">Password</label>-->
                                <input class="form-control" placeholder="Password" type="password" name="password" id="password" autocomplete="off">
                            </div>

                            <div class="field">
                                <label for="remember">
                                    <input type="checkbox" name="remember" id="remember">Remember Me!
                                </label>
                            </div>

                            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                            <button class="btn btn-lg btn-warning btn-block" type="submit" value="Log in">Log In!</button>
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
