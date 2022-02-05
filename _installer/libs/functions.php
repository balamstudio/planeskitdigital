<?

function alt_isset(&$check, $alternate = false) {
    if (empty($alternate)) {
        if (is_array($check) OR is_array($alternate)) {
            $alternate = array();
        } else {
            if (is_numeric($check)) {
                $alternate = 0;
            } else {
                $alternate = '';
            }
        }

        //pp($alternate);
    }

    return (isset($check)) ? ( empty($check) ) ? (is_string($alternate) ? trim($alternate) : $alternate ) : ( is_string($check) ? trim($check) : $check )  : (is_string($alternate) ? trim($alternate) : $alternate );
}

function z_print($var)
{
    global $localDev;
    if (is_dev())
    {
        echo '<br /><pre>';
        print_r($var);
        echo '</pre>';
    }
}

function is_dev() {
    global $localDev;  //in ../cnf...
    if ($_SESSION['dev']!=1) {

        $ip = get_client_ip();

        return in_array($ip,$localDev);
    }

    return true;
}

function pp($var) { z_print($var);}

function pe($string)  // echo
{
    if (is_dev())
    {
        echo '<br />'.$string;
    }
}

function pd($msg = "")  // echo
{
    if (is_dev())
    {
        die($msg);
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_REAL_IP'))
        $ipaddress = getenv('HTTP_X_REAL_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function old($key)
{
    if ( !empty($_REQUEST[$key]) )
    {
//            pe($key."-->".htmlspecialchars($_REQUEST[$key]));
        return htmlspecialchars($_REQUEST[$key]);
    }

    return '';
}

function redirectTo($destination, $code =''  )
{

    static $http = array (
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out"
    );


    if (in_array($code, $http))
    {
        header($http[$code]);
    }
    header('Location: ' . $destination);
}

function validate_email($email){

    global $cfg, $lng;
    $result = true;
//pe($email);
    if (spamcheck($email))
    {
        $exp = "/^[[:alnum:]][a-z0-9_.+-]*@[a-z0-9\.-]+\.[a-z]{2,4}$/i";
        $email = strtolower(trim($email));

        if(preg_match($exp,$email))
        {
            $chk_mail_host = explode("@",$email);
            $chk_host = array_pop($chk_mail_host);
            //echo '<br />'.$chk_host;
            if(checkdns_email_format($chk_host,"MX"))
            {
//                       pe("mx ok");
                //$msg_alert = $lng['msg_domain_looks_valid'];
            }
            else
            {
                $result = false;
//                                pe('mx KO');
                $cfg['msg_alert'] = $lng['msg_domain_looks_invalid'];
            }
        }
        else
        {
            $result = false;
            $cfg['msg_alert'] = $email.$lng['email_not_seems_valid'];
        }
    } else {
        $result = false;
        $cfg['msg_alert'] = $email.$lng['email_not_seems_valid'];
    }
    //pe($cfg['msg_alert']);
    return $result;
}// end validate email

function validate_phone($phone)
{
    global $cfg, $lng;
    $result = true;

    //echo '<br />phone'.$phone;

    /*	$clean = preg_replace('`[^0-9\(\)\s\.\/-]`', '', $phone);
        echo '<br />clean='.$clean; */

    $exp = "/^(\(?\s?(\+|00)?[0-9]{1,3}+\s?\)?\.?)?([ \(\.-]?[0-9]{1,15}){1,5}((\s*(#|x|(ext))\.?\s*)\d{1,5})?$/";

    /*
    932189615
0040264243974
(+34)932189615
( +34 ) 932189615
93.218.96.15
93-218-98-15
1.415.252.8508
1.415.252.8508
+1 415.252.8508
+34 627.16.25.37
+34 627162537
(+34) 627162537
(+40) 0752.618.495
+1 1234-1234
+1 123-1234 x123
123-1234 ext 123
(999)-188-1234
(123)-188-123
(123)123-1234 x123
(123) 123-1234 x123
(123)-123-1234 x123
12 12 12 12 12
+12 1234 1234
+12 12 12 1234
+12 1234 5678
+12 12345678
01362 851694
4401531650396
+966-0505818284
+44 7752 123456
+353 1 234 5678
07969 123456
087 123 4567
0044 123 456 789
+44 123 456 789
(+258) 24193856
(+258) 820611165
(+351) 212 251 162
(+351) 212 258 5 07
    */
    if (!(preg_match($exp,$phone))){
        $result = false;
        $cfg['msg_alert'] = "phone KO";
    }
    //echo '<br/>'.$cfg['msg_alert'];
    return $result;
}

function checkdns_email_format($host, $type=''){
    if(!empty($host)){
        $type = (empty($type)) ? 'MX' :  $type;
//		pe($host);
        if (func_enabled('escapeshellcmd') && func_enabled('exec')){
            $cmd= 'nslookup -type='.$type.' '.escapeshellcmd($host);
//			pe($cmd);
            exec($cmd, $result);
            $it = new ArrayIterator($result);
            foreach(new RegexIterator($it, '~^'.$host.'~', RegexIterator::GET_MATCH) as $result){
                if($result){
                    return true;
                }
            }
        } else {
            return true;
        }

    }
    return false;
}

function spamcheck($field)
{
    //filter_var() sanitizes the e-mail
    //address using FILTER_SANITIZE_EMAIL
    $field=filter_var($field, FILTER_SANITIZE_EMAIL);

    //filter_var() validates the e-mail
    //address using FILTER_VALIDATE_EMAIL
    if(filter_var($field, FILTER_VALIDATE_EMAIL))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function func_enabled($phpFunction) {
    $disabled = explode(',', ini_get('disable_functions'));
    return !in_array($phpFunction, $disabled);
}

function record_order_in_DB($data)
{
    global $cfg, $db, $lng, $status;

    $query = "INSERT INTO `orders` SET
				`id_order` = NULL,";

    foreach ($data as $key => $value){
        $query .= " `{$key}` = '" . $db->escape($value) . "',";
    }
    $query .= " `date_added` = '".date("Y-m-d H:i:s")."',
				`last_modified` = '".date("Y-m-d H:i:s")."';";
    //pe($query);die;
    $SQL = $db->query($query,'add order error  > '.$lng['operation_ko']);

    $idOrder = $db->insert_id();
//		pe('idOrder ='.$idOrder);
    $status = $lng['operation_ok'];
    return alt_isset($idOrder,0);

}

function record_in_DB($data,$table,$chkifexist=0,$chk_column='',$sqlExtra='')
{
    global $cfg, $db, $lng, $status;
    $execute = true;
    $query = $start_query = $end_query = "";
    $end_query = ";";

    if ( intval($chkifexist)>0){
        // check if not registered already

        if (empty($chk_column)) {
            $status = $lng['db_query_error'];
            return false;
        }

        $sql_query = "SELECT * FROM `{$table}` WHERE `{$chk_column}` ='".$db->escape($data['{$chk_column}'])."' ".$sqlExtra;
        /* 	    pe($sql_query); */
        $result = $db->query($sql_query, false);
        /* 	    pe($db->affected_rows()); */

        if ($db->affected_rows() >= intval($chkifexist)){
            $status = $lng['register_already_in_DB'].'<br />'.$lng['hp_support'];
            return false;
        }
    }

    $colsOrders = getColumnsFromTable($table);
    /* 	pp($colsOrders); */

    $start_query = "INSERT INTO `{$table}` SET ";


    foreach ($colsOrders as $value) {
        if (!empty(alt_isset($data[$value]))) $query .= " `" . $value . "` = '" . $db->escape(alt_isset($data[$value])) . "', ";
    }

    $query = trim($query, ", ");
    $sql_query = $start_query.$query.$end_query;
    // pd($sql_query);
    $SQL = $db->query($sql_query, 'error saving data into DB');

    $idUsr = $db->insert_id();
    $idUsr = alt_isset($idUsr,0);
    /* 	pe('idUsr ='.$idUsr); */
    if ($idUsr>0) {
        $status = $lng['operation_ok'];
        return $idUsr;
    } else {
        $status = $lng['db_query_not_inserted'];
        return false;
    }

}

function newMail($to='',$subject='',$body='',$headers='',$from=false, $fromName=false,$attachments=array(), $CCmail=array(), $BCCmail=array(),$attchAsString=false)
{
    global $cfg, $lng;
    /*
    echo '<pre>';
    print_r ($lng);
    echo '</pre>';

    echo '<br /><b>BAU</b>:'.$lng['email']['sending_email'] ;
    */
    require_once ($_SERVER["DOCUMENT_ROOT"].'/libs/phpMailer/PHPMailerAutoload.php');

    unset($mail);

    $mail=new PHPMailer();

    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 3;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    //Set the hostname of the mail server
    $mail->Timeout = 600;

    $mail->IsSMTP();

    $mail->Host=$cfg['mail_host'];
    $mail->Port = $cfg['mail_SMTP_port'];
    $mail->SMTPSecure = $cfg['mail_SMTP_secure'];
    $mail->SMTPAuth = $cfg['mail_SMTP_auth'];
    $mail->Username = $cfg['mail_user'];
    $mail->Password = $cfg['mail_password'];

    $mail->CharSet  = "UTF-8";

    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );


    if ($from!==false) $mail->From=$from;
    else $mail->From = $lng['email']['sending_email'] ;

    if ($fromName!==false) $mail->FromName=$fromName;
    else $mail->FromName=$lng['cfg']['mail_from'];

    $mail->IsHTML(true);

    $mail->AddAddress($to);

    if(count($CCmail) > 0){
        foreach ($CCmail as $mailCC){
            $mail->AddCC($mailCC);
            //pe('mailCC='.$mailCC);
        }
    }

    if(count($BCCmail) > 0){
        foreach ($BCCmail as $mailBCC){
            $mail->AddBCC($mailBCC);
            //pe('mailBCC='.$mailBCC);
        }
    }

    $mail->Subject=$subject;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

    $body=str_replace("[\]",'',$body);

    $mail->Body=$body;
    $mail->AltBody = $lng['mail_txt_no_HTML'];

    if ($attchAsString) {
        $mail->AddStringAttachment($attchAsString['string'],$attchAsString['filename'],'base64',$attchAsString['filetype']);
    }

    foreach ($attachments as $att)
    {
        $mail->AddAttachment($att);
    }

    if ($headers) $mail->AddCustomHeader($headers);

    //send the message, check for errors
    if (!$mail->Send()) {

        $cfg['mail_error'] = $mail->ErrorInfo;

        // writeLog($_SERVER['DOCUMENT_ROOT'].'/zmail_err.log', '<hr/>'.var_dump($mail).'<hr/>');
    } else {
        $cfg['mail_error'] = $lng['mail']['response_OK'];
    }
    //pe($cfg['mail_error']);
    unset($mail);

    return $cfg['mail_error'];
}

function validate_redeem_code($redeemCode)
{
    global $cfg, $db;

    $query = "SELECT * ,`rc`.`id_product` as productID
					FROM `redeem_codes`AS `rc` 
					INNER JOIN `redeem_codes_history` AS `rch` USING ( `id_history` )
					INNER JOIN `products` AS `p` ON `rc`.`id_product` =  `p`.`id_product`
					LEFT JOIN `multi-device` AS `md` ON `rc`.`id_product` = `md`.`id_product`
					WHERE `redeem_code` = '".$db->escape($redeemCode)."'";
    //		pe($query);
    return get_results($query);
}

function generateCode($length=12,$strength ='AaN@', $split=0, $prefix='',$table='',$column='',$extraSQL='')
{
    global $code;

    $code=$prefix;

    $sw=1;

    $tries=0;

    $cut=array(1=>1);
    $currCut=1;

    $alphabets = range('a','z');
    $alphabetsUp = range('A','Z');
    $numbers = range('0','9');
    $additional_characters = array('.','-','+','<','>','#','@','$','?','!','%','_','*','~','^','[',']','(',')','{','}');

    $final_array = array();
    if (preg_match('/A/', $strength)) $final_array = array_merge($final_array,$alphabetsUp);
    if (preg_match('/a/', $strength)) $final_array = array_merge($final_array,$alphabets);
    if (preg_match('/N/', $strength)) $final_array = array_merge($final_array,$numbers);
    if (preg_match('/@/', $strength)) $final_array = array_merge($final_array,$additional_characters);

    shuffle($final_array);

    while ($sw)
    {
        while (strlen($code)<$length)
        {
            $key = array_rand($final_array);
            $code .= $final_array[$key];
        }

        $code1 = $code;
        if ($split != 0){
            $code1 = trim(strrev(chunk_split (strrev($code), $split,'-')),'-');
        }

        $long = strlen($code);

        if ($table&&$column)
        {
            $SQL="SELECT * FROM `".$table."` WHERE `".$column."` LIKE '".$code1."'".$extraSQL;
//			pe($SQL);
            $exAd=  get_result($SQL);

            if (is_array($exAd))
            {
                $currCut=1;
                while ($long<$cut[$currCut])
                {
                    $cut[$currCut]=0;
                    $currCut++;
                }

                $code=substr($code,0,$long-$currCut);
                if (!isset($cut[$currCut])) { $cut[$currCut]=1;} else { $cut[$currCut]++; }
            }
            else
            {
                $sw=0;
            }
        }
        else $sw=0;
    }

    if ($split != 0){
        $code = trim(strrev(chunk_split (strrev($code), $split,'-')),'-');
    }

    return $code;
}

function split_on($string, $num) {
    $length = strlen($string);
    $output[0] = substr($string, 0, $num);
    $output[1] = substr($string, $num, $length );
    return $output;
}

function split_trim($splitOn, $myString){
    $cleanArray =  array_map('trim', explode($splitOn, $myString));
    return array_filter($cleanArray);
}

function string_2_array ($separator, $string){
    return array_filter(array_map('trim', explode($separator, $string)));
}

function cleanExportData(&$str)
{
    // escape tab characters
    $str = preg_replace("/\t/", "\\t", $str);

    // escape new lines
    $str = preg_replace("/\r?\n/", "\\n", $str);

    // convert 't' and 'f' to boolean values
    if($str == 't') $str = 'TRUE';
    if($str == 'f') $str = 'FALSE';

    // force certain number/date formats to be imported as strings
    if(preg_match("/^0/", $str) || preg_match("/^\+?\d{8,}$/", $str) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) {
        $str = "'$str";
    }

    // escape fields that include double quotes
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
}

function sanitizeTxt($cadena, $charset = 'utf-8', $txtCase = 'samecase', $alowed = '-_') { //lowercase/uppercase/samecase
    global $zit_lang, $SanitClean;

// Remove multiple spac
// es
    $clean = preg_replace('/\s+/', ' ', trim(strip_tags($cadena)));
    //echo '<br />clean='.$clean;
// Remove special chars
    $lower_search = array("á", "à", "â", "ǎ", "ă", "ã", "ả", "ȧ", "ạ", "ä", "å", "ḁ", "ā", "ą", "ⱥ", "ȁ", "ấ", "ầ", "ẫ", "ẩ", "ậ", "ắ", "ằ", "ẵ", "ẳ", "ặ", "ǻ", "ǡ", "ǟ", "ǟ", "ȃ", "ɑ", "æ", "ǽ", "ǣ", "œ",
        "ḃ", "ḅ", "ḇ", "ƀ", "ɓ", "ƃ", "ᵬ",
        "ć", "ĉ", "č", "ċ", "ç", "ḉ", "ȼ", "ƈ",
        "ḋ", "ḑ", "ḍ", "ḓ", "ḏ", "đ", "ɖ", "ƌ",
        "é", "è", "ê", "ḙ", "ě", "ĕ", "ẽ", "ḛ", "ẻ", "ė", "ë", "ē", "ȩ", "ę", "ɇ", "ȅ", "ế", "ề", "ễ", "ể", "ḝ", "ḗ", "ḕ", "ȇ", "ẹ", "ệ",
        "ḟ", "ƒ",
        "ǵ", "ğ", "ĝ", "ǧ", "ġ", "ģ", "ḡ", "ǥ", "ɠ",
        "ĥ", "ȟ", "ḧ", "ḣ", "ḩ", "ḥ", "ḫ", "ħ", "ⱨ",
        "ì", "í", "ĭ", "î", "ǐ", "ï", "ḯ", "ĩ", "į", "ī", "ỉ", "ȉ", "ị", "ḭ", "ɨ",
        "ĵ", "ɉ",
        "ḱ", "ǩ", "ķ", "ḳ", "ḵ", "ƙ", "ⱪ",
        "ĺ", "ļ", "ḷ", "ḹ", "ḽ", "ḻ", "ł", "ŀ", "ƚ", "ⱡ", "ɫ",
        "ḿ", "ṁ", "ṃ",
        "ń", "ǹ", "ň", "ñ", "ṅ", "ņ", "ṇ", "ṋ", "ṉ", "ɲ", "ƞ", "ŋ",
        "ó", "ò", "ŏ", "ô", "ố", "ồ", "ỗ", "ổ", "ǒ", "ö", "ȫ", "ő", "õ", "ṍ", "ṏ", "ȭ", "ȯ", "ȱ", "ø", "ǿ", "ǫ", "ǭ", "ō", "ṓ", "ṑ", "ỏ", "ȍ", "ȏ", "ơ", "ớ", "ờ", "ỡ", "ở", "ợ", "ọ", "ộ", "ɵ", "ɔ",
        "ṕ", "ṗ", "ᵽ", "ƥ",
        "ɋ", "ƣ",
        "ŕ", "ř", "ṙ", "ŗ", "ȑ", "ȓ", "ṛ", "ṝ", "ṟ", "ɍ", "ɽ",
        "ś", "ṥ", "ŝ", "š", "ṧ", "ş", "ṣ", "ṩ", "ș", "s",
        "ť", "ṫ", "ţ", "ṭ", "ț", "ṱ", "ṯ", "ŧ", "ⱦ", "ƭ", "ʈ",
        "ú", "ù", "ŭ", "û", "ǔ", "ů", "ü", "ǘ", "ǜ", "ǚ", "ǖ", "ű", "ũ", "ṹ", "ų", "ū", "ṻ", "ủ", "ȕ", "ȗ", "ư", "ứ", "ừ", "ữ", "ử", "ự", "ụ", "ṳ", "ṷ", "ṵ", "ʉ",
        "ṽ", "ṿ", "ʋ",
        "ẃ", "ẁ", "ŵ", "ẅ", "ẇ", "ẉ", "ⱳ",
        "ẍ", "ẋ",
        "ý", "ỳ", "ŷ", "ÿ", "ỹ", "ẏ", "ȳ", "ỷ", "ỵ", "ɏ", "ƴ",
        "ź", "ẑ", "ž", "ż", "ẓ", "ẕ", "ƶ", "ȥ", "ⱬ"
    );


    $lower_replace = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "ae", "ae", "oe",
        "b", "b", "b", "b", "b", "b", "b",
        "c", "c", "c", "c", "c", "c", "c", "c",
        "d", "d", "d", "d", "d", "d", "d", "d",
        "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
        "f", "f",
        "g", "g", "g", "g", "g", "g", "g", "g", "g",
        "h", "h", "h", "h", "h", "h", "h", "h", "h",
        "i", "i", "i", "i", "i", "i", "i", "i", "i", "i", "i", "i", "i", "i", "i",
        "j", "j",
        "k", "k", "k", "k", "k", "k", "k",
        "l", "l", "l", "l", "l", "l", "l", "l", "l", "l", "l",
        "m", "m", "m",
        "n", "n", "n", "n", "n", "n", "n", "n", "n", "n", "n", "n",
        "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
        "p", "p", "p", "p",
        "q", "q",
        "r", "r", "r", "r", "r", "r", "r", "r", "r", "r", "r",
        "s", "s", "s", "s", "s", "s", "s", "s", "s", "s",
        "t", "t", "t", "t", "t", "t", "t", "t", "t", "t", "t",
        "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
        "v", "v", "v",
        "w", "w", "w", "w", "w", "w", "w",
        "x", "x",
        "y", "y", "y", "y", "y", "y", "y", "y", "y", "y", "y",
        "z", "z", "z", "z", "z", "z", "z", "z", "z"
    );

    $lower_search_plus = array("ɗ", "ᵭ", "ᶁ", "ᶑ", "ȡ", "ȡ", "ᶒ", "ᵮ", "ᶂ", "ᶃ", "ᵫ", "Ȋ", "ǰ", "ʝ", "ɟ", "ʄ", "ᶄ", "ɬ", "ᶅ", "ᵯ", "ᶆ", "ɱ", "ᵰ", "ᶇ", "ɳ", "ȵ", "ᵱ", "ᶈ", "ʠ", "ᵲ", "ᶉ", "ɼ", "ɾ", "ᵳ", "ẛ", "ᵴ", "ᶊ", "ʂ", "ȿ", "ṡ", "ẗ", "ᵵ", "ƫ", "ȶ", "ᵾ", "ᶙ", "ᶌ", "ⱱ", "ⱴ", "ẘ", "ᶍ", "ʏ", "ẙ", "ᵶ", "ᶎ", "ʐ", "ʑ", "ɀ"
    );
    $lower_replace_plus = array("d", "d", "d", "d", "d", "d", "e", "f", "f", "g", "ue", "i", "j", "j", "j", "j", "k", "l", "l", "m", "m", "m", "n", "n", "n", "n", "p", "p", "q", "r", "r", "r", "r", "r", "r", "s", "s", "s", "s", "s", "t", "t", "t", "t", "u", "u", "v", "v", "v", "w", "x", "y", "y", "z", "z", "z", "z", "z"
    );

    $upper_search = array("Á", "À", "Â", "Ǎ", "Ă", "Ã", "Ả", "Ȧ", "Ạ", "Ä", "Å", "Ḁ", "Ā", "Ą", "Ⱥ", "Ȁ", "Ấ", "Ầ", "Ẫ", "Ẩ", "Ậ", "Ắ", "Ằ", "Ẵ", "Ẳ", "Ặ", "Ǻ", "Ǡ", "Ǟ", "Ȁ", "Ȃ", "Ɑ", "Æ", "Ǽ", "Ǣ", "Œ",
        "Ḃ", "Ḅ", "Ḇ", "Ƀ", "Ɓ", "Ƃ", "ß",
        "Ć", "Ĉ", "Č", "Ċ", "Ç", "Ḉ", "Ȼ", "Ƈ",
        "Ḋ", "Ḑ", "Ḍ", "Ḓ", "Ḏ", "Ð", "Ɗ", "Ƌ",
        "É", "È", "Ê", "Ḙ", "Ě", "Ĕ", "Ẽ", "Ḛ", "Ẻ", "Ė", "Ë", "Ē", "Ȩ", "Ę", "Ɇ", "Ȅ", "Ế", "Ề", "Ễ", "Ể", "Ḝ", "Ḗ", "Ḕ", "Ȇ", "Ẹ", "Ệ",
        "Ḟ", "Ƒ",
        "Ǵ", "Ğ", "Ĝ", "Ǧ", "Ġ", "Ģ", "Ḡ", "Ǥ", "Ɠ",
        "Ĥ", "Ȟ", "Ḧ", "Ḣ", "Ḩ", "Ḥ", "Ḫ", "Ħ", "Ⱨ",
        "Ì", "Í", "Ĭ", "Î", "Ǐ", "Ï", "Ḯ", "Ĩ", "Į", "Ī", "Ỉ", "Ȉ", "Ị", "Ḭ", "Ɨ",
        "Ĵ", "Ɉ",
        "Ḱ", "Ǩ", "Ķ", "Ḳ", "Ḵ", "Ƙ", "Ⱪ",
        "Ĺ", "Ļ", "Ḷ", "Ḹ", "Ḽ", "Ḻ", "Ł", "Ŀ", "Ƚ", "Ⱡ", "Ɫ",
        "Ḿ", "Ṁ", "Ṃ",
        "Ń", "Ǹ", "Ň", "Ñ", "Ṅ", "Ņ", "Ṇ", "Ṋ", "Ṉ", "Ɲ", "Ƞ", "Ŋ",
        "Ó", "Ò", "Ŏ", "Ô", "Ố", "Ồ", "Ỗ", "Ổ", "Ǒ", "Ö", "Ȫ", "Ő", "Õ", "Ṍ", "Ṏ", "Ȭ", "Ȯ", "Ȱ", "Ø", "Ǿ", "Ǫ", "Ǭ", "Ō", "Ṓ", "Ṑ", "Ỏ", "Ȍ", "Ȏ", "Ơ", "Ớ", "Ờ", "Ỡ", "Ở", "Ợ", "Ọ", "Ộ", "Ɵ", "Ɔ",
        "Ṕ", "Ṗ", "Ᵽ", "Ƥ",
        "Ɋ", "Ƣ",
        "Ŕ", "Ř", "Ṙ", "Ŗ", "Ȑ", "Ȓ", "Ṛ", "Ṝ", "Ṟ", "Ɍ", "Ɽ",
        "Ś", "Ṥ", "Ŝ", "Š", "Ṧ", "Ş", "Ṣ", "Ṩ", "Ș", "S",
        "Ť", "Ṫ", "Ţ", "Ṭ", "Ț", "Ṱ", "Ṯ", "Ŧ", "Ⱦ", "Ƭ", "Ʈ",
        "Ú", "Ù", "Ŭ", "Û", "Ǔ", "Ů", "Ü", "Ǘ", "Ǜ", "Ǚ", "Ǖ", "Ű", "Ũ", "Ṹ", "Ų", "Ū", "Ṻ", "Ủ", "Ȕ", "Ȗ", "Ư", "Ứ", "Ừ", "Ữ", "Ử", "Ự", "Ụ", "Ṳ", "Ṷ", "Ṵ", "Ʉ",
        "Ṽ", "Ṿ", "Ʋ",
        "Ẃ", "Ẁ", "Ŵ", "Ẅ", "Ẇ", "Ẉ", "Ⱳ",
        "Ẍ", "Ẋ",
        "Ý", "Ỳ", "Ŷ", "Ÿ", "Ỹ", "Ẏ", "Ȳ", "Ỷ", "Ỵ", "Ɏ", "Ƴ",
        "Ź", "Ẑ", "Ž", "Ż", "Ẓ", "Ẕ", "Ƶ", "Ȥ", "Ⱬ"
    );
    $upper_replace = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "AE", "AE", "AE",
        "B", "B", "B", "B", "B", "B", "B",
        "C", "C", "C", "C", "C", "C", "C", "C",
        "D", "D", "D", "D", "D", "D", "D", "D",
        "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
        "F", "F",
        "G", "G", "G", "G", "G", "G", "G", "G", "G",
        "H", "H", "H", "H", "H", "H", "H", "H", "H",
        "I", "I", "I", "I", "I", "I", "I", "I", "I", "I", "I", "I", "I", "I", "I",
        "J", "J",
        "K", "K", "K", "K", "K", "K", "K",
        "L", "L", "L", "L", "L", "L", "L", "L", "L", "L", "L",
        "M", "M", "M",
        "N", "N", "N", "N", "N", "N", "N", "N", "N", "N", "N", "N",
        "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
        "P", "P", "P", "P",
        "Q", "Q",
        "R", "R", "R", "R", "R", "R", "R", "R", "R", "R", "R",
        "S", "S", "S", "S", "S", "S", "S", "S", "S", "S",
        "T", "T", "T", "T", "T", "T", "T", "T", "T", "T", "T",
        "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
        "V", "V", "V",
        "W", "W", "W", "W", "W", "W", "W",
        "X", "X",
        "Y", "Y", "Y", "Y", "Y", "Y", "Y", "Y", "Y", "Y", "Y",
        "Z", "Z", "Z", "Z", "Z", "Z", "Z", "Z", "Z"
    );


    switch ($txtCase) {
        case 'lowercase':
            // echo '<br />lowercase';
            $clean = str_replace($upper_search, $lower_search, $clean);
            //echo '<br />caseLow ='.$clean;
            $clean = str_replace($lower_search, $lower_replace, $clean);
            //echo '<br />clean low normal ='.$clean;  
            $clean = str_replace($lower_search_plus, $lower_replace_plus, $clean);
            //echo '<br />clean low plus ='.$clean;
            $clean = strtolower($clean);
            //echo '<br />clean all low ='.$clean;

            break;

        case 'uppercase':

            $clean = str_replace($lower_search, $upper_search, $clean);
            //echo '<br />caseUp ='.$clean;
            $clean = str_replace($upper_search, $upper_replace, $clean);
            //echo '<br />clean up normal ='.$clean;
            $clean = strtoupper($clean);
            //echo '<br />clean all up ='.$clean;

            break;

        case 'samecase':

            $clean = str_replace($lower_search, $lower_replace, $clean);
            //echo '<br />clean low normal ='.$clean;
            $clean = str_replace($lower_search_plus, $lower_replace_plus, $clean);
            //echo '<br />clean low plus ='.$clean;
            $clean = str_replace($upper_search, $upper_replace, $clean);
            //echo '<br />clean up normal ='.$clean; 

            break;
    }

// IMPORTANT set and cofigure setlocale(LC_ALL, $zit_lang.'_'.strtoupper($zit_lang).'.UTF8');
    // $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $clean);
    //echo '<br />clean iconv='.$clean;
// Replace spaces
    $clean = preg_replace('/\s/', '-', $clean);
    //echo '<br />clean='.$clean;
// Remove other characters
    // challet : I modified the replacement to follow former rules used to generate slugs in SFR JT
    // it was [^0-9a-z-_] , replacing by ''

    switch ($txtCase) {
        case 'lowercase':
            $clean = preg_replace('`[^0-9a-z' . $alowed . ']`', '', $clean);
            break;
        case 'uppercase':
            $clean = preg_replace('`[^0-9A-Z' . $alowed . ']`', '', $clean);
            break;
        default:
            $clean = preg_replace('`[^0-9a-zA-Z' . $alowed . ']`', '', $clean);
            break;
    }
    //echo '<br />'.$txtCase.' ->title_nochars='.$clean;	
// Remove double --
    $clean = preg_replace('`(--)+`', '-', $clean);
    //echo '<br />clean double -- ='.$clean;	
// Remove double __
    $clean = preg_replace('`(__)+`', '_', $clean);
    //echo '<br />clean double __ ='.$clean;		
    //echo '<br />final_nochange ='.$clean;

    $SanitClean = $clean;
    return $SanitClean;
}

function safe_alias($cadena, $charset = 'utf-8', $alowed = '-_', $short = 0) { // 60 chars seo
    global $zit_lang, $SanitClean;

    //echo $charset;
    //echo $alowed;

    sanitizeTxt($cadena, $charset, 'lowercase', $alowed);

    //echo '<br />clean fin ='.$SanitClean;	
// Replace _ with -
    $clean = preg_replace('`(_)+`', '-', $SanitClean);
    //echo '<br />clean _ ='.$clean;	
//sanitize for seo shortening the url
    if ($short > 0) {
        if (strlen($clean) > $short) { //scurtat la 60 chars pt seo
            $clean = substr($clean, 0, $short);
            //echo '<br />short='.$clean;
        }
    }
    //echo $clean;

    return $clean;
}

function getDomainFromEmail($email)
{
    // Get the data after the @ sign
    $domain = substr(strrchr($email, "@"), 1);

    return $domain;
}

function loadTemplateFile($filename, $params = [], $include = false) {
    global $cfg;
    if ($include) {
        $path = realpath(dirname(__FILE__))."/../language/{$filename}.php";
    } else {
        $path = $cfg["web_url"]."language/{$filename}.php";
    }
    //echo $path;
    //if ( !file_exists($path)) { die("no template found");}
    // echo $path; die;
    // writeLog($_SERVER["DOCUMENT_ROOT"] . '/zzzzzzzz.log', "****** loadTemplateFile path " . $path . "***********");
    // writeLog($_SERVER["DOCUMENT_ROOT"] . '/zzzzzzzz.log', "****** loadTemplateFile PARAM " . print_r($params, true) . "***********");

    return file_get_contents_curl($path, 5, $params, $include);
}

function file_get_contents_curl($url, $retries = 5, $params = [], $include = false) {
    $ua = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36';
    $data = http_build_query($params);

    if (extension_loaded('curl') === true) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); // The URL to fetch. This can also be set when initializing a session with curl_init().
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // The number of seconds to wait while trying to connect.
        curl_setopt($ch, CURLOPT_USERAGENT, $ua); // The contents of the "User-Agent: " header to be used in a HTTP request.
        curl_setopt($ch, CURLOPT_FAILONERROR, TRUE); // To fail silently if the HTTP code returned is greater than or equal to 400.
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE); // To follow any "Location: " header that the server sends as part of the HTTP header.
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE); // To automatically set the Referer: field in requests where it follows a Location: redirect.
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // The maximum number of seconds to allow cURL functions to execute.
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // The maximum number of redirects
        curl_setopt($ch, CURLOPT_POST, 1);
        //   writeLog($_SERVER["DOCUMENT_ROOT"] . '/responsePaypal.log', "****** curl post PARAM \n" . print_r($params, true) . "***********");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $result = trim(curl_exec($ch));

        curl_close($ch);
    } else {

        $opts = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                    . "Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            )
        );
        //   pp($opts);
        $context = stream_context_create($opts);
        //   pp($context);die;

        $result = file_get_contents($url, $include, $context);
    }

    if (empty($result) === true) {
        $result = false;

        if ($retries >= 1) {
            sleep(1);
            return file_get_contents_curl($url, $retries--);
        }
    }

    return $result;
}

function writeLog($logFile, $message) {
    if (!$logFile) {
        return;
    }

    file_put_contents($logFile, $message . "\n", FILE_APPEND);
}

// perfect date convertor
function dateConversion($date2convert, $fromFormat, $toFormat) {
    if (!empty($date2convert) && ($date2convert != '0000-00-00 00:00:00')) {

//         pe('<hr />');	
//         pe('Date='.$date2convert);
//         pe('fromFormat='.$fromFormat);
//         pe('toFormat='.$toFormat);
        $fecha = date_create_from_format($fromFormat, $date2convert);
        $newDate = date_format($fecha, $toFormat);
//         pe ($newDate);

    } else {
        $newDate = '';
    }
    return $newDate;
}

function get_info_from($fromTable, $col, $value,$limit=null) {

    global $db, $iInfo;
//$iInfo = array();

    $query = "SELECT * 
		  FROM `" . $fromTable . "`
		  WHERE `" . $col . "` = '" . $db->escape($value) . "'";
    if (!is_null($limit)){
        $query .= " LIMIT $limit";
    }
    //pe($query);
    $iInfo = $db->query_first($query);

    return $iInfo;
}

function get_result($query) {  //1 articol
    global $db, $iInfo, $lng;

    //echo '<br />'.$query.'<br />';

    $resultSQL = $db->query($query);

    $iInfo = array();

    $iInfo = $db->fetch_array($resultSQL);
    /*
      echo '<pre>';
      print_r($iInfo);
      echo '</pre>';
     */
    return $iInfo;
}

function get_results($query) {  // many results
    global $db, $aInfo, $lng;

    //echo '<br />'.$query.'<br />';

    $SQL = $db->query($query);

    $aInfo = array();

    while ($all_info = $db->fetch_array($SQL)) {
        $aInfo[] = $all_info;
    }

    //pp($aInfo);

    return $aInfo;
}

function getColumnsFromTable($table_name) {

    global $cfg, $lng, $db, $database_name;

    //writeLog($_SERVER["DOCUMENT_ROOT"] . '/responsePaypal.log', "****** cfg db " . $cfg['db_name'] . "***********");
    $query = "SELECT  `COLUMN_NAME` 
                FROM  `INFORMATION_SCHEMA`.`COLUMNS` 
                WHERE  `TABLE_SCHEMA` =  '" . $db->escape($database_name) . "' 
                AND  `TABLE_NAME` = '" . $db->escape($table_name) . "'  
                ";
    //writeLog($_SERVER["DOCUMENT_ROOT"] . '/responsePaypal.log', "******RESPONSE GEt cols " . $query . "***********");

    $SQL = $db->query($query);

    $aInfo = array();

    while ($all_info = $db->fetch_array($SQL)) {
        $aInfo[] = $all_info['COLUMN_NAME'];
    }

    return $aInfo;
}

function save_data($table, $column, $colValue, $post_data)
{
    global $cfg, $db, $lng, $status, $itemId;
    $query = '';

    $datetimeNow = date($cfg['sql_date']);

    $colsOrders = getColumnsFromTable($table);

    // check if already exists in db
    $insert = already_registered($table,$column,$colValue);
    // pe($insert);
    if(!$insert){
        $start_query = "INSERT INTO `{$table}` SET ";
        $end_query =  ";";
        $post_data['created'] = $datetimeNow;
        //  pe("INSERT :) "); dd();
    } else {
        $updatedId = $itemId;
        $start_query = "UPDATE `{$table}` SET ";
        $end_query =  " WHERE `{$column}` = '" . $db->escape(alt_isset($colValue)) . "';";
    }

    $post_data['updated_on'] = time();
    $post_data['updated'] = $datetimeNow;

    //pp($colsOrders);
    //pp($post_data);

    foreach ($colsOrders as $value) {
        if (!empty(alt_isset($post_data[$value]))) $query .= " `" . $value . "` = '" . $db->escape(alt_isset($post_data[$value])) . "', ";
    }

    $query = trim($query, ", ");
    $sql_query = $start_query.$query.$end_query;

//	pe($sql_query); //dd();

    $SQL = $db->query($sql_query,'error saving data into DB');

    if(!$insert) {
        return $db->insert_id();
    } else {
        return $updatedId;
    }

}

function already_registered($table, $col, $value)
{
    global $cfg, $db, $itemId;

    $sql_query = "SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'";
    $primaryKey = $db->query_first($sql_query);
    $primaryColumn = $primaryKey['Column_name'];

    $query = "SELECT `{$primaryColumn}`";

    if  ($primaryColumn != $col) {
        $query .= ",`{$col}` ";
    }
    $query .= " FROM `{$table}` 
				WHERE `{$col}` = '".$db->escape($value)."' 
				LIMIT 1";
//    pe($query); //dd();
    $result = $db->query_first($query, false);
    $itemId = $result[$primaryColumn];
//    pp($result);//dd();
    $counter = $db->affected_rows();
//    pe($counter); //dd();
    if ($counter > 0) { return true; } else { return false;	}
}

function isTableInDB($table_name) {
    global $cfg, $lng, $db, $database_name;

    $query = "SELECT  `COLUMN_NAME` 
                FROM  `INFORMATION_SCHEMA`.`COLUMNS` 
                WHERE  `TABLE_SCHEMA` =  '" . $db->escape($database_name) . "' 
                AND  `TABLE_NAME` = '" . $db->escape($table_name) . "'  
                LIMIT 1    
                ";
    //writeLog($_SERVER["DOCUMENT_ROOT"] . '/responsePaypal.log', "******RESPONSE GEt cols " . $query . "***********");

    $SQL = $db->query($query);
    $nrRows = $db->num_rows($SQL);
    $nr = alt_isset($nrRows, 0);
    //pe('numRows ='.$nr);
    if ($nr > 0) {
        return true;
    } else {
        return false;
    }
}

function get_bundle($id){
    global $db, $lng;

    $query = "SELECT `b`.`id_item`, `b`.`quantity`, `p`.*
                FROM `bundle` AS `b`
                INNER JOIN `products` AS `p` ON `b`.`id_item` = `p`.`id_product`
                WHERE `b`.`id_product` = '" . $db->escape($id) . "'";
//    pe($query);

    return get_results($query);

}

function get_devices($id){
    global $db, $lng;

    $query = "SELECT `m`.`id_item` ,  `m`.`OS`, `p`.*
                FROM `multi-device` AS `m`
                INNER JOIN `products` AS `p` ON `m`.`id_item` = `p`.`id_product`
                WHERE `m`.`id_product` = '" . $db->escape($id) . "'";
    // pe($query);

    return get_results($query);
}

function get_all_active_products(){
    global $db, $aInfo, $lng;
    $query = "SELECT `id_product`, `catalog_part`, `name`, `stock`
                FROM `products` 
                WHERE `status` = 1
                AND `type` = 'stand-alone' 
                ORDER BY `name` ASC  ";
    return get_results($query);
}

function get_product_from_order($idOrder){
    global $db, $aInfo, $lng;
    $query = "SELECT `i`.`id_product` ,  `i`.`type` ,  `i`.`geo` ,  `i`.`catalog_part` ,  `i`.`name` ,  `i`.`devices`,  `i`.`stock` ,  `i`.`stock_alert`,  `i`.`redeem_stock` , `i`.`mail_tpl`, `i`.`status` ,  `i`.`last_modified` 
FROM `orders`
INNER JOIN `redeem_codes` AS `rc` USING (`redeem_code`)
INNER JOIN `products` AS `i` ON `i`.`id_product`= `rc`.`id_product`
WHERE `orders`.`id_order` = '" . $db->escape($idOrder) . "'
LIMIT 1";
    return get_result($query);
}


function delete_product($idPrds, $type='stand-alone') {
    global $db, $cfg, $lng;

    /*
        if ($type != 'stand-alone'){
        // remove prds from bundle
            $query_del = "DELETE FROM `{$type}` WHERE `id_product` = '".$db->escape($idPrds)."'";
    //      echo '<br />query_del='.$query_del;
            $res_del =  $db->query($query_del,'DEL prds from {$type} > '.$lng['operation_ko']);
        }
    */

    // delete product
    $query_del = "DELETE FROM `products` WHERE `id_product` = '".$db->escape($idPrds)."'";
//      echo '<br />query_del='.$query_del;
    $res_del =  $db->query($query_del,'DEL prds from products > '.$lng['operation_ko']);

}

function incrementStockProducts($id_sel_product, $posted = array()) {
    global $db, $cfg, $lng, $now_date_time;
    //pp($posted);

    $uploaded_csv_file = (isset($_FILES['uploaded_file']['name']) ? $_FILES['uploaded_file']['name'] : '');

    //check if is csv file
    $content = file_get_contents($_FILES['uploaded_file']['tmp_name']);

    preg_match_all('/("?)([a-zA-Z0-9\-+_.:\s ]+)("?)((,|;)?)/', $content, $matches);
    $badRows = $noRows = 0;
    // pp($matches[2]); die;
    foreach ($matches[2] as $key => $license_key) {
        $license_key = trim($license_key);
        $license_key = trim($license_key, ".");
        $license_key = trim($license_key, ",");
        if (is_unique('software_licences', 'licence_key', $license_key)){
            $SQL = "INSERT INTO `software_licences` SET 
						`id_licence` = NULL,  
						`id_product` = '" . $db->escape($id_sel_product) . "', 
						`licence_key` = '" . $db->escape($license_key) . "',  
                        `date_added` = '".$db->escape($now_date_time)."',
                        `last_modified` = '".$db->escape($now_date_time)."';
						";

            //pe($SQL);

            $result = $db->query($SQL, false);

            //pe($db->affected_rows());

            $affRows = $db->affected_rows();
            $noRows+=$affRows == 1 ? 1 : 0;

        } else {
            $badRows=$badRows+1;
        }
        $_SESSION['msg']['msg_info'] = str_replace('<added />', $noRows, $lng['license_csv_message']);
        $_SESSION['msg']['msg_info'] = str_replace('<dropped />', $badRows, $_SESSION['msg']['msg_info'] );

    }

    $SQL = "UPDATE `products` SET `stock`=`stock`+" . $noRows . " WHERE `id_product`='" . $db->escape($id_sel_product) . "'";
    // pe($SQL);
    $result = $db->query($SQL);
}

function cleanLicences($id_sel_product,$date_loaded= false){
    global $db, $cfg, $lng, $now_date_time;

    // clean unused licences
    $SQL = "DELETE FROM `software_licences` 
						WHERE 
						`id_product` = '" . $db->escape($id_sel_product) . "'";
    if ($date_loaded){
        $SQL .= "				AND `date_added` = '" . $db->escape($date_loaded) . "'";
    }
    $SQL .= "				AND `id_order` IS NULL ;";


//    pe($SQL); // pd();

    $result = $db->query($SQL, false);
    $affRows = $db->affected_rows();
//    pe($affRows);

    $_SESSION['msg']['msg_info'] = " Se han limpiado ".$affRows." licencias.";

    // update stock for the product
    if ($date_loaded) {
        $query = "SELECT count(`id_product`) as `licences_left` FROM `software_licences`
                  WHERE `id_product` =  '" . $db->escape($id_sel_product) . "'
                  AND `id_order` IS NULL";
//pe($query);
        $res =  $db->query_first($query);
        $actual_stock = $res['licences_left'];
        // pe("sss=".$actual_stock);
    }
    $cur_stock = alt_isset($actual_stock,0);
//    pe($cur_stock);
    $SQL = "UPDATE `products` SET `stock`= '".$db->escape($cur_stock)."' ,  `last_modified` = '" . $db->escape($now_date_time) . "' WHERE `id_product`='" . $db->escape($id_sel_product) . "'";
//     pe($SQL);
    $result = $db->query($SQL);
//    pd();

}

function is_unique($table, $column, $value){
    global $db, $aInfo, $lng;
    $query_chk = "SELECT *
                FROM `{$table}` 
                WHERE `{$column}` = '".$db->escape($value)."'
                LIMIT 1";
    $result = $db->query($query_chk, false);
    $counter = $db->affected_rows();
    if ($counter > 0 ) return false; else return true;
}

function get_licences_4_product($product_id, $qty) {
    global $db, $cfg, $lng;

    $query = "SELECT * FROM `software_licences` "
        . "WHERE `id_product` = '".$db->escape($product_id)."' "
        . "AND `id_order` IS NULL "
        . "ORDER BY `id_licence` ASC "
        . "LIMIT ".$db->escape($qty)." FOR UPDATE;";
    //     pe ($query);

    return get_results($query);
}

function get_order_items($idOrder){
    global $db, $cfg, $lng;

    $query = "SELECT  `oi` . *, `p`.`name`, `p`.`catalog_part` , `p`.`mail_txt` ,  `p`.`stock` ,  `p`.`stock_alert`, `p`.`status`
				FROM  `orders_items` AS  `oi` 
				INNER JOIN  `products` AS  `p` 
				USING (  `id_product` ) 
				WHERE  `id_order` = ".$db->escape($idOrder).";";

    return get_results($query);
}

function get_software_licence_of_product_per_order ($idOrder){
    global $db, $cfg, $lng;

    $query = "SELECT *  FROM `software_licences` WHERE `id_order` = ".$db->escape($idOrder).";";
    //pe($query);
    return get_results($query);

}

function folderDate($mysql_date_time) {  //campaign
    $targetDate = '/' . substr($mysql_date_time, 0, 4) . '/' . substr($mysql_date_time, 5, 2) . '/';
    return $targetDate;
}

function createDirs($path_from_domain_root) {
    $chk_dir = $_SERVER['DOCUMENT_ROOT'];
    $path_from_domain_root = str_replace($chk_dir,"",$path_from_domain_root);
    $path_from_domain_root = preg_replace('/^\/|\/$/', '', $path_from_domain_root);
    $dirNames = explode("/", $path_from_domain_root);

    //echo '<br />$path_from_domain_root='.$path_from_domain_root;
    //echo '<br />$full_path='.$chk_dir;
    //pp($dirNames);pd();

    foreach ($dirNames as $myDir) {
        $chk_dir = $chk_dir . '/' . $myDir;

        //pe('$chk_dir='.$chk_dir);

        if (!file_exists($chk_dir . '/')) {
            mkdir($chk_dir . '/', 0);
            chmod($chk_dir . '/', 0755);
        }
    }
}

function magicUpload($uploadedFile, $whereTo, $newFileName) {
    global $cfg, $lng, $msg_notice;

    $uploadFileTmp = $cfg['web_path'] . $whereTo . $newFileName;
//    pe($uploadFileTmp);

    if (is_uploaded_file($uploadedFile['tmp_name'])) {

//		   echo "Archivo ". $uploadedFile['name'] ." subido con éxito\n";
//		   echo "Monstrar contenido\n";
//		   readfile($uploadedFile['tmp_name']);
//		   echo '<hr />'.$uploadFileTmp.' '.$newFileName.'<hr />';


        if (move_uploaded_file($uploadedFile['tmp_name'], $uploadFileTmp)) {
            $msg_notice .= '<br />' . $lng['error_upload_file'];

            move_uploaded_file($uploadedFile['tmp_name'], $uploadFileTmp);
            return true;
        } else {
            $msg_notice .= '<br />' . $lng['error_upload_file'];
            return false;
        }
    } else {
        /*
          echo "Posible ataque del archivo subido: ";
          echo "nombre del archivo '". $uploadedFile['tmp_name'] . "'.";
         */
        $msg_notice .= $lng['no_file_uploaded'];

        return false;
    }
}


function object_2_array($object) {
    $json = json_encode($object);
    return json_decode($json,TRUE);
}

function generate_signed_hash($data)
{
    global $cfg, $lng, $msg_notice;
    $private_key = openssl_get_privatekey($cfg['ssl_private_key']);

    $data = implode('||', array_values($data));

    openssl_sign($data, $hash, $private_key);

    return base64_encode($hash);

}


function execute_curl($data, $url){

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POST, count($data));
    curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($data));

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    return $result;
}

function run_curl($data, $url, $auth=false, $header=[], $jsonData = false){

    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 90); //timeout after 90 seconds
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_POST, true);

    if (is_array($data)){
        curl_setopt($ch, CURLOPT_POST, count($data));
        if ($jsonData){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
    } elseif (is_string($data)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    if (!empty($header)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }

    if ($auth){
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, $auth);
    }

    curl_setopt($ch,CURLOPT_FAILONERROR,true);

    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);   //get status code

    /*
        if(curl_exec($ch) === false)
        {
            echo 'Curl error: ' . curl_error($ch);
        }
        else
        {
            echo 'Operación completada sin errores';
        }
    */

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);

    return $result;
}

function rate_pc($value,$lang){
    global $lng, $cfg;
    if (floatval($value) < $cfg['audit']['rank']['low'] ){
        $result['rate_btn'] = "btn-danger";
        $result['rate_val'] = $lng['deficitary'][$lang];
    } elseif (floatval($value) >= $cfg['audit']['rank']['low'] && floatval($value) <= $cfg['audit']['rank']['high'] ) {
        $result['rate_btn'] = "btn-warning";
        $result['rate_val'] = $lng['normal'][$lang];
    } elseif (floatval($value) > $cfg['audit']['rank']['high'] ) {
        $result['rate_btn'] = "btn-success";
        $result['rate_val'] = $lng['optimum'][$lang];
    }
    return $result;
}

function contain_string($needle, $heystack){
    if (stripos($heystack, $needle) !== false) {
        return true;
    } else {
        return false;
    }
}

function delete_all_between($beginning, $end, $string, $includeDelimiters = true) {
    $beginningPos = strpos($string, $beginning);
    //pe($beginningPos);
    $endPos = strpos($string, $end);
   // pe($endPos);
    if ($beginningPos === false || $endPos === false) {
        return $string;
    }
    if ($includeDelimiters) {
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
    } else {
        $textToDelete = substr($string, $beginningPos+strlen($beginning), $endPos - $beginningPos-strlen($beginning));
    }
    return str_replace($textToDelete, '', $string);
}

function dirlist($dir_path, $item_type="all",$ext=null){
    global $cfg;
    $content=[];
    $dateFormat =$cfg['date_format']." H:m:s";

    if (!file_exists($dir_path)) {
        die ($dir_path." does not exist");
    } else {
        if ($item_type == "is_dir") $ext =null;
    }

    $dir= scandir($dir_path);
    $dir = array_diff($dir, $cfg['except_dirs']);
    $i=0;

    foreach($dir as $k => $v){
        $item=$dir_path.$v;
        if ($item_type != 'all') {
            $item_kind = $item_type;
            if(!($item_type($item))){
                // pe($item." is not ".$item_type);
                goto next_item;
            }
            if( !is_null($ext)) {
                $file_info = pathinfo($item);
                if ($ext != $file_info['extension']) {
                    goto next_item;
                }
            }

        } else {
            $item_kind = is_dir($item) ? 'is_dir':'is_file';
        }


        $content[$item_kind][$i]['name'] = $v;
        $item_stats = stat($item);
        //pp($item_stats);
        //$content[$item_kind][$i]['atime']= date($dateFormat, $item_stats[8]);
        //$content[$item_kind][$i]['mtime']= date($dateFormat, $item_stats[9]);
        $content[$item_kind][$i]['ctime']= date($dateFormat, $item_stats[10]);
        next_item:
        $i++;
    }

    return $content;
}