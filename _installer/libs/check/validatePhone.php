<?php

$myPath = realpath(dirname(__FILE__));
//$msg =$myPath;

include_once ($myPath."/../init.php");
//pp($_POST);
$checkPhone = alt_isset($_POST["phone"]);
validate_phone($checkPhone);

$msg = empty($cfg['msg_alert']) ? true : $cfg['msg_alert'];

echo json_encode($msg);
exit();

