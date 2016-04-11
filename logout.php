<?php
session_start();
unset($_SESSION["username"]);
unset($_SESSION['timeout']);
session_unset();
session_destroy();
header("Location: index.php");
?>
