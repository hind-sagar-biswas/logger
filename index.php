<?php
require './config.php';

// HEADER
require $HTML . 'header.php';

// BODY
if (isset($_GET['m'])) echo '<i>' . $_GET['m'] . '</i><br/>';

if (!$logger->checkLogin()) require $FORM . 'login.min.php';
else { ?>
    <form action="<?= $INC . 'logger.inc.php' ?>" method="post">
        <lable>You are logged in as: <em><?= $_SESSION['username'] ?></em> </lable><button type="submit" name="logger-type" value="logout" id="logout">Logout</button>
    </form>
<?php }

// FOOTER
require $HTML . 'footer.php';
