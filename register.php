<?php
require './config.php';

// HEADER
require $HTML . 'header.php';

// BODY
if (isset($_GET['m'])) echo '<i>' . $_GET['m'] . '</i><br/>';

if (!$logger->checkLogin()) require $FORM . 'register.php';
else redirect_to('root');

// FOOTER
require $HTML . 'footer.php';
