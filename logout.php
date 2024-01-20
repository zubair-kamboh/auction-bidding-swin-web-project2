<!-- Student Name: Zubair Ali -->
<!-- Student ID: 104823405 -->

<!-- LOG OUT SIGNNED IN USERS -->
<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: login.php");
exit;
?>
