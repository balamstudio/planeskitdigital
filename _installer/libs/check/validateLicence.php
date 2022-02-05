<?php

$myPath = realpath(dirname(__FILE__));

include_once ($myPath."/../init.php");
$redeemCode = alt_isset($_POST["redeem_code"]);
$licence = validate_redeem_code($redeemCode);

if (empty($licence)){
	$result["status"] = false;
	$result['msg'] = $lng['invalid_redeem_code'];
} else {

	if ( isset($licence['id_order']) && !is_null($licence['id_order'])){
		$result["status"] = false;
		$result['msg'] = $lng['already_redeemed'].$lng['recover_licences'] ;
	} else {
		$result["status"] = true;
		// if multi-device
		if ( $licence[0]['type'] == "multi-device"){
			$result["multi_device"] = true;
			$result["devices"] = $licence[0]["devices"];
			$html = ""; $total = count($licence);
			foreach ($licence as $l){
				$html .= osInput($total, $l["OS"], $result["devices"]);
			}
			$result["html"] = $html."<br class='clear'><br><div id='error_devices' class='alert alert-warning' role='alert'>Elija un total de <u>".$result["devices"]."</u> dispositivos...</div>";
		}
	} 
}

function osInput($total, $os, $max){
	$icon = strtolower($os);
	if($icon == "ios" || $icon == "mac"){
		$icon = "apple";
	}
	return "<div class='col-md-".(12/$total)."'>
	<label for='basic-url'>$os</label>
		<div class='input-group'>
	  		<span class='input-group-addon' id='$os-os'><i class='fa fa-$icon'></i></span>
	  		<input type='number' name='os[$os]' max='$max' min='0' data-min='0' required data-max='$max' value='0' class='form-control os-input' aria-describedby='$os-os'>
		</div>
	</div>";
}

echo json_encode($result);
exit();

