<?php

$myPath = realpath(dirname(__FILE__));

include_once ($myPath."/../init.php");
//echo $_POST['password'];
$password = hash('sha512', $_POST['password']);
//echo " --> ".$password;
if (login('username', $_SESSION['user_access']['login']['username'], $password, false) === true) {
        // Login success
    $msg = true;
} else {
    $msg = "Wrong password";
}

echo json_encode($msg);
exit();