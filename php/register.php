<?php
require_once 'core/init.php';

//var_dump(Token::check(Input::get('token')));

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'username' => array(
                'required' => true,
                'min' => 2,
                'max' => 20,
                'unique' => 'users'
            ),
            'password' => array(
                'required' => true,
                'min' => 6
            ),
            'password_again' => array(
                'required' => true,
                'matches' => 'password'
            ),
            'email_address' => array(
                'required' => true,
                'min' => 2,
                'max' => 50,
                'email' => 'email_address'
            ),
            'firstname' => array(
                'required' => true,
                'min' => 2,
                'max' => 30
            ),
            'lastname' => array(
                'required' => true,
                'min' => 2,
                'max' => 30
            )
        ));

        // Need to create the full inputs for full user data i.e. all columns in the user table

        if ($validation->passed()) {
            $user = new User();
            $newDir = new FileDirectory();

            $salt = Hash::salt(32);

            try {

                $user->create(array(
                    'username' => Input::get('username'),
                    'password' => Hash::make(Input::get('password'), $salt),
                    'email_address' => Input::get('email_address'),
                    'salt' => $salt,
                    'firstname' => Input::get('firstname'),
                    'lastname' => Input::get('lastname'),
                    'joined' => date('Y-m-d H:i:s'),
                    'group' => 1
                ));

                $newDir->createDirectory(Input::get('username'));

                Session::flash('home', 'You have been successfully registered and can now Log In!');
                Redirect::to('index.php');

            } catch(Exception $e) {
                die($e->getMessage());
                // Create an "Oops, couldn't register you" page later on.
            }
        } else {
            foreach ($validation->errors() as $error) {
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

    <!-- Register Div -->
    <section id="top" class="register">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Register!</h1>
                </div>
            </div>
            <div class="row">
                <div id="form" class="col-lg-12 text-center">
                    <form action="" method="post" class="form-signin">
                        <h2 class="form-signin-heading">Register Your Details Dude!</h2>
                        <div class="field">
                            <!--<label for="username">Username</label>-->
                            <input class="form-control" placeholder="Enter a Username" type="text" name="username" id="username" value="<?php echo escape(Input::get('username'));?>" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Enter a Password" type="password" name="password" id="password" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Enter the Password again" type="password" name="password_again" id="password_again" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Enter a Valid Email Address" type="text" name="email_address" id="email_address" value="<?php echo escape(Input::get('email_address'));?>" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Enter your Firstname" type="text" name="firstname" id="firstname" value="<?php echo escape(Input::get('firstname'));?>" autocomplete="off">
                        </div>

                        <div class="field">
                            <!--<label for="password">Password</label>-->
                            <input class="form-control" placeholder="Enter your Lastname" type="text" name="lastname" id="lastname" value="<?php echo escape(Input::get('lastname'));?>" autocomplete="off">
                        </div>

                        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                        <button class="btn btn-lg btn-warning btn-block" type="submit" value="Register">Register!</button>
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

<!--<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?php// echo escape(Input::get('username'));?>" autocomplete="off">
    </div>

    <div class="password">
        <label for="password">Choose a password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <div class="field">
        <label for="email_address">Enter your Email Address</label>
        <input type="text" name="email_address" value="<?php// echo escape(Input::get('email_address'));?>" id="email_address">
    </div>

    <div class="field">
        <label for="firstname">Enter your Firstname</label>
        <input type="text" name="firstname" value="<?php// echo escape(Input::get('firstname'));?>" id="firstname">
    </div>

    <div class="field">
        <label for="lastname">Enter your Lastname</label>
        <input type="text" name="lastname" value="<?php// echo escape(Input::get('lastname'));?>" id="lastname">
    </div>

    <input type="hidden" name="token" value="<?php// echo Token::generate();?>">

    <input type="submit" value="Register">
</form> -->
