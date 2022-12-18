<?php
require __DIR__ . '/../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Create LOGGER object
    $logger = new Logger();

    // Check type of action
    if (isset($_POST['logger-type'])) $type = $_POST['logger-type'];
    else $type = 'login';

    // If register user
    if ($type == 'register') {
        $password = $_POST['password'];
        $rePassword = $_POST['re-password'];

        if ($password != $rePassword) redirect_to('register', 't=0&m=Retyped%20password%20does%20match');

        $user = [
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'rememberMe' => $_POST['rememberMe']
        ];

        // Initiate registration
        $register = $logger->register($user);
        $message = urlencode($register[1]);

        // Redirect with message
        if ($register[0]) redirect_to('register', "t=0&m=$message");
        redirect_to('root', "t=1&m=$message");
    }

    // LOGIN PART
    // 
    // Check targetpage of action
    if (isset($_POST['redirect-to'])) $redirectTarget = $_POST['redirect-to'];
    else $redirectTarget = 'root';

    $login = $logger->login(trim($_POST['name-or-mail']), $_POST['password'], $_POST['remember-me']);
    $message = urldecode($login[1]);

    if ($login) redirect_to($redirectTarget, "t=1&m=$message");
    redirect_to($redirectTarget, "t=0&m=$message");

} else redirect_to('root');
