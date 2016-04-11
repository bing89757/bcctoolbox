<?php
session_start();
$username = 'admin';
$username2 = 'some';
$password = 'admin';
$password2 = 'one';
if (isset($_POST)) {
    $post_username = $_POST['username'];
    $post_password = $_POST['password'];
    if ($username == $post_username && $password == $post_password) {
        $_SESSION['username']  = $post_username;
        echo $post_username;
    }
    $post_username2 = $_POST['username'];
    $post_password2 = $_POST['password'];
	
    if ($username2 == $post_username2 && $password2 == $post_password2) {
        $_SESSION['username']  = $post_username2;
        echo $post_username2;
    }
}
?>

