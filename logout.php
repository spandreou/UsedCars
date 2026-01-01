<?php
session_start();

// καθαρίζουμε session
session_unset();
session_destroy();

header("Location: index.php");
exit;
