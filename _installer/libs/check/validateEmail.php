<?php

$myPath = realpath(dirname(__FILE__));
//$msg =$myPath;

include_once ($myPath."/../init.php");
//pp($_POST);
$checkEmail = alt_isset($_POST["email"]);
validate_email($checkEmail);

$msg = empty($cfg['msg_alert']) ? true : $cfg['msg_alert'];

echo json_encode($msg);
exit();

