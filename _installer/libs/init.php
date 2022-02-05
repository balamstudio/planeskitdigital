<?php

/*  DO NOT TOUCH UNLESS YOU KNOW WHAT YOU'RE DOING */
// prevent pages from being cached
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: text/html; charset=utf-8");

// additional configurations
error_reporting(0);
//error_reporting(E_ALL);
//error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
//error_reporting(E_ALL ^ E_NOTICE);
//ini_set("display_errors", 1); //display or not
//ini_set("memory_limit", "512M");

/* DEFINE CONSTANTS*/
$version = '1.0';
define('CONFIG_DIR', 'config');
define('ADMIN_DIR', 'tasmicro');
define('DICTIONARY_DIR','/language/');

/**
 * Register Globals Security Fix
 * - unsetting every variable registered in $_REQUEST and as variable itself
 */

foreach($_REQUEST as $key => $value)
{
    if(isset($$key))
    {
        unset($$key);
    }
}


unset($_);
unset($value);
unset($key);
unset($cfg);
unset($lng);


/**
 * Reverse magic_quotes_gpc=on to have clean GPC data again
 */

if(get_magic_quotes_gpc())
{
    $in = array(&$_GET, &$_POST, &$_COOKIE
    );

    while(list($k, $v) = each($in))
    {
        foreach($v as $key => $val)
        {
            if(!is_array($val))
            {
                $in[$k][$key] = stripslashes($val);
                continue;
            }

            $in[] = & $in[$k][$key];
        }
    }

    unset($in);
}

//initialize variables;
global $lng;
global $cfg;
global $zVars;

$lng = $cfg = $iInfo = $zVars =$aInfo = $iVideo = array();

$cfg['msg_info'] = $cfg['msg_notice'] = $cfg['msg_alert'] = "";

//Creating default object from empty value
$config = new StdClass;

global $devIP, $localIP, $localDev, $subtlds, $ico;



/* CONFIGURATION  FOR THE LANGUAGE */
$cfg['meta_charset'] = "utf-8";
$cfg['mysql_charset'] = "utf8";
$cfg['mysql_collate'] = "utf8_general_ci";


/*   PATHS CONFIGS */
$cfg['web_url']="http";
$cfg['web_url'].=((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on')?"s":"");
$cfg['https'] = $cfg['web_url']."://";
$cfgFile = $_SERVER['DOCUMENT_ROOT'].'/'.CONFIG_DIR.'/config.php';

/*Includes the Usersettings eg. MySQL-Username/Passwort etc. */
if(!file_exists($cfgFile))
{
    die('You have to configure mysql first!');
}

/*Includes the Usersettings eg. MySQL-Username/Password etc. */
require ($cfgFile);

function isAlive($url) { // check if url exists and is alive
    $url = @parse_url($url);
    if (!$url)
        return false;

    $url = array_map('trim', $url);
    $url['port'] = (!isset($url['port'])) ? 80 : (int) $url['port'];

    $path = (isset($url['path'])) ? $url['path'] : '/';
    $path .= (isset($url['query'])) ? "?$url[query]" : '';

    if (isset($url['host']) && $url['host'] != gethostbyname($url['host'])) {

        $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

        if (!$fp)
            return false; //socket not opened

        fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n"); //socket opened
        $headers = fread($fp, 4096);
        fclose($fp);

        if (preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers)) {//matching header
            return true;
        }
        else
            return false;
    } // if parse url
    else
        return false;
}

function domainBaseUrl($url)
{
    global $cfg, $subtlds;
    $url = strtolower($url);

    $host = parse_url('http://'.$url,PHP_URL_HOST);

    $chk_domain = array();
    $old_length= 0;
    foreach($subtlds as $sub){

        if (preg_match('/\.'.preg_quote($sub).'$/', $host, $extMatch))
        {
            $length = -1 * strlen($extMatch[0]) ;
            $domainPrefix = substr($host, 0, $length);
            preg_match('/[^\.]+$/i',$domainPrefix,$ext);
            $new_length = strlen($ext[0].$extMatch[0]);

            if ( $new_length > $old_length){
                $domainName = $ext[0].$extMatch[0];
                $old_length = $new_length;
            }

            $chk_domain[] = $ext[0].$extMatch[0];

        }

    }

    return $domainName;
}

function get_tlds(){
    global $cfg;
    $address = $filename = realpath(dirname(__FILE__)).'/../_utils/effective_tld_names.dats';
    if (!file_exists($address))
    {
        //try external source
        $tld_url[] = 'http://mxr.mozilla.org/mozilla-central/source/netwerk/dns/effective_tld_names.dat?raw=1';
        $tld_url[] = 'http://zona-dns.com/_utils/effective_tld_names.dat?raw=1';

        foreach ($tld_url as $tldUrl)
        {
            if (isAlive($tldUrl)) {
                $address = $tldUrl;
                break;
            }
        }

    }

    //echo '<br />address ='.$address;

    $content = file($address);

    $subtlds = array();

    foreach($content as $num => $line){
        $line = trim($line);
        if($line == '') continue;
        if(@substr($line[0], 0, 2) == '/') continue;
        // echo "<hr />".($num+1).": ".$line;
        $line = @preg_replace("/[^a-zA-Z0-9\.]/", '', $line);
        if($line == '') continue;  //$line = '.'.$line;
        if(@$line[0] == '.') $line = substr($line, 1);
        // if(!strstr($line, '.')) continue;
        $subtlds[] = $line;
        //echo "<br />{$num}: '{$line}'"; echo "<br>";
    }

    /*
        $subtlds = array_merge($cfg['tlds'],$subtlds);
        $subtlds = array_unique($subtlds);
        //asort($subtlds);
        echo '<pre>';
        print_r($subtlds);
        echo '</pre>';
    */
    return $subtlds;
}

$subtlds = isset ($cfg['tlds']) ? $cfg['tlds'] : get_tlds();

$cfg['domainBase'] = isset($cfg['domainBase']) ? $cfg['domainBase'] : domainBaseUrl($_SERVER['HTTP_HOST']);

$cfg['keycript']        = "tasn3tc0lumb1a".$cfg['domainBase'];
$cfg['ajaxcript']       = array('myajaxKey' => $cfg['keycript'], 'uri'=>$_SERVER['REQUEST_URI']);

$cfg['web_path']     = $_SERVER['DOCUMENT_ROOT'];
$cfg['web_url'] = $cfg['https'].$cfg['domainBase'];
$cfg['web_cms_url']  = $cfg['http_web_url'].'/'.ADMIN_DIR;
$cfg['web_cms_path'] = $cfg['web_path'].'/'.ADMIN_DIR;
//        echo ($cfg['web_cms_url'])."<br/>";
//        echo ($cfg['web_cms_path'])."<br/>";
//        echo $cfg['web_url']."<br/>";

$cfg['dictionary_front_office_path']  = $cfg['web_path'].'/'.DICTIONARY_DIR;


$cfg['404_error'] = $cfg['web_url'];
@$goto_404_ERROR  = $cfg['404_error'];


/**
 * Language Managament
 */
$language = $cfg['default_lang'];
//echo "<br/>LANG=".$language;
/*Includes the Usersettings eg. MySQL-Username/Passwort etc. */
include_once ($cfg['dictionary_front_office_path'].'/'.$language.'.lng.php');

/* SESSION MANAGEMENT */
session_set_cookie_params(0, '/', '.'.$cfg['domainBase']);
session_start();

//session expire time
$session_inactive = 60*15*1*1; // 60sec * 60 min *24 hours * 100 days


$_SESSION['dev'] = 0;
// Use sandbox ?
if (($cfg['sandbox'] === true)  || in_array($_SERVER['REMOTE_ADDR'],$localDev))
{
//		echo "is_Dev";
    $_SESSION['dev']=1;
}

$_SESSION['session_start'] = isset($_SESSION['session_start']) ? $_SESSION['session_start'] : time();
$_SESSION['goto_url']      = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['HTTP_HOST'];
$_SESSION['userLevel']     =  isset($_SESSION['userLevel']) ? $_SESSION['userLevel'] : '4';
$_SESSION['login']         =  isset($_SESSION['login']) ? $_SESSION['login'] : '';

$_SESSION['language']   = isset($_SESSION['language']) ? $_SESSION['language'] :  $language;
$zit_lang               = isset($_GET['abr_lang']) ? $_GET['abr_lang'] : $_SESSION['language'];

/* INCLUDES THE MYSQL FUNCTIONS  */
require_once ($cfg['web_path'].'/libs/class_mysqldb.php');

$_SESSION['db'] = $db = new db($cfg['db_host'], $cfg['db_user'], $cfg['db_password'], $cfg['db_name']);
$database_name = $cfg['db_name'];
/*	echo '<pre>';
	print_r($_SESSION['db']);
	echo '</pre>';
	die("ddddd");*/

unset($cfg['db_password']);
unset($db->password);
unset($cfg['db_host']);
unset($cfg['db_user']);
//	unset($cfg['db_name']);

/* Includes the Functions */
require_once ($cfg['web_path'].'/libs/functions.php');
/* Browser Management */

//	$remote_addr = $_SERVER['REMOTE_ADDR'];
$http_user_agent = alt_isset($_SERVER['HTTP_USER_AGENT']);
//pe($http_user_agent);

if(!isset($_SESSION['user_access']['browser'])) {

    $browser = get_browser(null, false);
    $_SESSION['user_access']['browser'] = $browser;
//	pp($_SERVER);
//	pp($browser); dd();

    $_SESSION['browser'] = $browser->browser;
    $_SESSION['platform'] = $browser->platform;
    $_SESSION['platform_bits'] = alt_isset($browser->platform_bits, '32');
    $_SESSION['browser_version'] = $browser->version;
    $_SESSION['ismobiledevice'] = isset($browser->ismobiledevice) ? ($browser->ismobiledevice == 'Y' ? $browser->ismobiledevice : 'N') : 'N';
}

//Geolocation
$user_ip = alt_isset($_SERVER['GEOIP_ADDR'],get_client_ip());
//        $user_ip ="217.116.5.177"; //Madrid
//        $user_ip = "187.188.132.53"; // MX
//        $user_ip = "83.57.153.101"; // BCN
//     pe('IP='.$user_ip);

if(!isset($_SESSION['user_access']['geo'])) {

    if (isset($_SERVER['GEOIP_ADDR'])) {
        $geo['country_code'] = alt_isset($_SERVER['GEOIP_COUNTRY_CODE']);
        $geo['region'] = alt_isset($_SERVER['GEOIP_REGION_NAME']);
        $geo['city'] = alt_isset($_SERVER['GEOIP_CITY']);
    } else {
        $regFile = '/usr/share/GeoIP/region_codes.csv';
        if (function_exists('geoip_record_by_name')) {

            $geo = geoip_record_by_name($user_ip);
            //      pp($geo);

            if (function_exists('geoip_region_name_by_code')) {
                $region = geoip_region_name_by_code($geo['country_code'], $geo['region']);
                $geo['region_name'] = alt_isset($region, $geo['region']);
            } else {
                if (func_enabled('exec')) {
                    $command = "egrep '^" . $geo['country_code'] . "," . $geo['region'] . ",' " . $regFile . " | cut -d',' -f3";
                    //echo $command;
                    // $command = "wget -qO- http://dev.maxmind.com/static/maxmind-region-codes.csv | egrep '^ES,56,' | cut -d',' -f3";
                    $geo['region_name'] = alt_isset(str_replace('"', '', exec($command)),
                        $geo['region']);
                }

                $geo['region_name'] = alt_isset($geo['region']);

            }
        }
    }
    $geo['user_ip'] = $user_ip;
    $_SESSION['user_access']['geo'] = alt_isset($geo);


}


// unset($_SESSION['user_access']['geo']);




//LOCATION, DATE AND TIME ( locale -a )
putenv("TZ=".$cfg['time_zone']);
date_default_timezone_set($cfg['time_zone']);
// pe('configs_time_zone='.$configs['time_zone']);
setlocale(LC_ALL, "es_ES.utf8", "es_ES");

$cfg['sql_date']         = "Y-m-d H:i:s";
$cfg['calendar']['date'] = "dd-mm-yy";
$cfg['calendar']['time'] = "hh:mm:ss";
$cfg['calendar']['datetime'] = "d-m-Y H:i:s";

global $now_date_time;
@$now_date_time = date("Y-m-d H:i:s");
$cfg['now_date_time'] = $now_date_time;

$cfg['except_dirs'][] = '.';
$cfg['except_dirs'][] = '..';
$cfg['except_dirs'][] = CONFIG_DIR;
$cfg['except_dirs'][] = ADMIN_DIR;
$cfg['except_dirs'][] = 'files';

//pp($_SERVER); die;

//if (!is_dev()) { echo '<p style="text-align: center">Estamos realizando tareas de mantenimiento en el servidor. Le rogamos disculpen las molestias y vuelvan pasado unos minutos</p>'; die;}

?>
