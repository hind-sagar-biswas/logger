<form action="<?= $INC . 'logger.inc.php' ?>" method="post">
    <!-- HIDDEN INpUTS -->

    <!-- HIDDEN INPUTS END -->
    <div>
        <input type="text" name="username" id="username" placeholder="Username" <?php if (isset($_GET['username'])) echo "value='" . urldecode($_GET['username']) . "'" ?> required>
    </div>
    <div>
        <input type="email" name="email" id="email" placeholder="Email address" <?php if (isset($_GET['email'])) echo "value='" . urldecode($_GET['email']) . "'" ?> required>
    </div>
    <div>
        <input type="password" name="password" id="password" placeholder="Password" required>
    </div>
    <div>
        <input type="password" name="re-password" id="re-password" placeholder="Retype password" required>
    </div>
    <div>
        <input type="checkbox" value="" id="remember-me" name="remember-me" checked>
        <label for="remember-me">Remember me</label>
    </div>
    <div>
        <button type="submit" name="logger-type" value="register">Register</button>
    </div>
    <div>Already a member? <a href="<?= $BASE_URI . 'index.php' ?>">login now!</a></div>
</form>