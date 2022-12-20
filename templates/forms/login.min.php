<form action="<?= $INC . 'logger.inc.php' ?>" method="post">
    <input type="text" name="name-or-mail" id="name-or-mail" placeholder="Username or email address" required>
    <input type="password" name="password" id="password" placeholder="password" required>
    <button type="submit" name="logger-type" value="login">Login</button>
    <span>Not a member yet? <a href="<?= $BASE_URI . 'register.php' ?>">Register now!</a></span>
</form>