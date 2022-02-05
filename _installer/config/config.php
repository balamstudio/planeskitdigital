<?php

//mysql configuration
$cfg['db_host']='localhost';
$cfg['db_name']='zonait_PCaudit';
$cfg['db_user']='zonait_auditUsr';
$cfg['db_password']='[J1GLR+ODw7]-}*}';

/* DEFAULT SETTINGS*/
$cfg['default_lang'] = 'es';
$cfg['domainBase'] = 'audit.zonait.com/';
$cfg['http_web_url'] = "http://audit.zonait.com/";

// domain tld
$cfg['tlds'] = array('dev', 'com', 'com.es', 'net', 'mx', 'com.mx','es', 'co');

$cfg['sel_items_per_page'] = array('1','3','5','10','20','50','100','250','500',0);
$cfg['items_per_page'] = $cfg['no_items_per_page'] = 20;
$cfg['nav_pages_to_show'] = 3;

$cfg['sandbox'] = true; //true/false
$localDev = array('::1','192.168.1.12', '192.168.1.35', '192.168.1.33', 
'95.62.22.35', // dan
'127.0.0.1'
);

$cfg['session_expired'] = 2700; // 15 min

//echo "<pre>";print_r($_SERVER);echo "</pre>";

//MAIL CONFIG
$cfg['mail_contact']['affiliate'] 	= "info@konibit.com";
$cfg['mail_contact']['inquire'] 	= "info@konibit.com";
$cfg['mail_contact']['support'] 	= "dbaras@me.com";



/* MAIL SETTINGS*/

$cfg['mail_host']='algorab.elifebackup.com';
$cfg['mail_user']='mailman@tangotargeting.com';
$cfg['mail_password']='!rLwpTOlw*;TiJ(L=';
$cfg['mail_SMTP_port']= 465; //likely to be 25, 465 or 587
$cfg['mail_SMTP_secure'] = 'ssl'; // ssl /tls
$cfg['mail_SMTP_auth'] = true;
$cfg['mail_from'] = $cfg['mail_user'];
$cfg['mail_from_name'] = "PC Audit";
/*
$cfg['mail_host']='exchange.itm.es';
$cfg['mail_user']='ITMZARCOM\facturacion.illescas';
$cfg['mail_password']='Fillescas2018';
$cfg['mail_SMTP_port']= 587; //likely to be 25, 465 or 587
$cfg['mail_SMTP_secure'] = 'ssl'; // ssl /tls
$cfg['mail_SMTP_auth'] = true;
$cfg['mail_from'] = 'facturacion.illescas@trescal.com';
$cfg['mail_from_name'] = "Trescal Illescas";

$cfg['mail_host']= 'smtp.smmpvdr.gmessaging.net';//'exchange.itm.es';
$cfg['mail_user']= 'Anonymous';//'ITMZARCOM\facturacion.illescas';
$cfg['mail_password']= '';//'Fillescas2018';
$cfg['mail_SMTP_port']= 25; //likely to be 25, 465 or 587
$cfg['mail_SMTP_secure'] = 'tls'; // ssl /tls
$cfg['mail_SMTP_auth'] = false;
$cfg['mail_from'] = 'facturacion.illescas@trescal.com';
$cfg['mail_from_name'] = "Trescal Illescas";
*/
$cfg['encrypt_key'] = "aUd1t.BadZxya*.yiB8k.9}MB6P^RVCpW9k+";
$cfg['ssl_private_key'] = "-----BEGIN RSA PRIVATE KEY-----
MIIEpAIBAAKCAQEA4JAIsOfgkqN33bVMi+nLvSZr4ZWY1IimdeTge2aouU4Dh6Ap
20m0PCUBQMGmN4i3gV7kmXq5TqEhkDKC+Q+4lC+qYiKQluqnL6brjNDP+Z0L17jQ
pdYHXtRGdeSCdT3Rdp6J1rDMPGcrtTobz1jLGUNAl//MXGE845DlMLX7VjOSpLp5
pgKwSo3GNjXlxXQPpcgNhkz9Otxh1b+GUR1ruMS54aTkKzZJt6R4neLmZ2wv03Jr
e51qlMJlF35NivUOWcIhA98rkSvYkT5J3E80l3/3CQxBM8+IstQ+W9bZLIG6LZA4
ejGNTL4MfwH60uW5U/QtL94lHKaqXMhmk82nCwIDAQABAoIBAQDYz9fETaxSiAeU
BJZKQ8mU70Kbg58SHHlzPC7MlfpzJvJbgPTIpymgJC5Igm9kENjzzFW0JXxCgWnx
WgOASoBwenDr3Oly/E28wVGNHJKgMtObTPIOG030bck1zBF09uGEwF7e7MXTz9yw
VdFB6T32PytfVfbOfoeWQ3A1Do8C0mtVv5OZCVSvfxaK50W3zwhWe1ewXXU89Fuv
Z/i/XwuLK02IPbPT0NULjWzIiaR/89342RX29777Fm0reSun28ptYSGYhTvlX0jZ
nMBI65vOPeGcYEe4plao3xAcaGKi4qugEOJXhdL0VSV6zyQ3tkzWVJa9yAAx8Q83
yr+5CXnhAoGBAPDSUxgLvFCjLKM/gYj+h/XXDJ8DIqzEK3rCP4ifQzm5CGlRNb/x
A/5AbviLMt2u6Jc7pvSk/ZccNs1MR71q6370FRrbaGSXzUU6uwdyqLZg8wis/uzn
qcF10Y3DbrrONN7b4CL6f+jyDtZanMJzX/J17FCxTIdQjzGJowH7MEVbAoGBAO63
XrSh/I31hYQ7zdYkwROsBz7vP8RUnmDUDfueKoiissJ47lN0zkY4gDBj93eVDQRg
A8EvMmP8D72r2TV9hL4PpJ73a8yZcjJpkPBVCpR1KkIFtNSTKvjMhXWSpcG2s02e
c4157pYj/hHOhi52IOqDOGUSdQdVPo3W5Y07vOQRAoGAWFgwF4Amo0ZZF8IyWPlX
Ez7C6IqT6+FDOhOMjygt1z0j8s7R8woNtvYYP6GBFYYW2XQOuWzVgtvc1s+G+dwB
bF9KZsHauBxgN2dmOUM81TsXrTUZh/sscUYxi9oIAwumpaLvxKU0y1YRT12KaGM+
7YmJGckRFArfnQKrBA0MFkUCgYEAoPiz+zh0VsHmW+n7/lhgfkR1ymGI7XpmJkM4
fqEEa4jS5EKp60sLwdxdzMPMXy36TiX0wjNyVmvlrXARk3llpshjqPKNFbWvhQEc
xIOEE+ICMe0pKGNpCcbYhBT0g3EQ7dlYcGF1mncA378VSoVL4vfDcpyEoAJCoWD1
AEz1LhECgYBqowlMPgOv2xXRxhtgSpV7/nE7I7a4idnaV+7ZMGk0qnq+OLJIBdDP
IxQ7yIWeM+TGMA3hGBiG/4yPx2UTKsb3YXr6Uc3dcojRJRGVGbEM9+yJ3nbFRBRD
Hbc7hJoV0vTiS2F+Nblir9mgFIVzFS9YgwbbNaJPVPPtfaG/b/tmHg==
-----END RSA PRIVATE KEY-----";

$lng['Escritorio'] = 'desktop';
$lng['Portátil'] = 'laptop';

$lng['laptop'] = "portátil";
$lng['desktop'] = "equipo de sobremesa";

$lng['deficitary']['es'] = "Mejorable";
$lng['normal']['es'] = "Correcto";
$lng['optimum']['es'] = "Óptimo";

// PC type
$svg['laptop'] = "<svg version=\"1.1\" id=\"_x31_0\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" viewBox=\"0 0 512 512\" style=\"width: 64px; height: 64px; opacity: 1;\" xml:space=\"preserve\">
<style type=\"text/css\">
    .st0{fill:#374149;}
</style>
                        <g>
                            <path class=\"st0\" d=\"M469.789,368.34V123.91c0-19.554-15.851-35.398-35.406-35.398H77.617c-19.554,0-35.402,15.844-35.402,35.398
		v244.43H0v4.383c0,28.023,22.715,50.766,50.766,50.766h410.446c28.05,0,50.789-22.742,50.789-50.766v-4.383H469.789z M288,392.684
		v6.453c0,2.375-1.906,4.25-4.274,4.25H228.25c-2.344,0-4.25-1.875-4.25-4.25v-6.453c0-2.367,1.906-4.274,4.25-4.274h55.476
		C286.094,388.41,288,390.316,288,392.684z M77.617,368.34V123.91h356.766v244.43H77.617z\"></path>
                        </g>
</svg>";

$svg['desktop'] = "<svg version=\"1.1\" id=\"_x31_0\" xmlns=\"http://www.w3.org/2000/svg\" xmlns:xlink=\"http://www.w3.org/1999/xlink\" x=\"0px\" y=\"0px\" viewBox=\"0 0 512 512\" style=\"width: 64px; height: 64px; opacity: 1;\" xml:space=\"preserve\">
<style type=\"text/css\">
	.st0{fill:#374149;}
</style>
<g>
	<path class=\"st0\" d=\"M486.293,102.193H208.66c-14.195,0-25.707,11.508-25.707,25.707v195.371c0,14.195,11.512,25.706,25.707,25.706
		h277.633c14.199,0,25.707-11.511,25.707-25.706V127.9C512,113.701,500.492,102.193,486.293,102.193z M486.293,127.9v176.992v0.379
		H208.66V127.9h276.946H486.293z\"></path>
	<path class=\"st0\" d=\"M442.926,397.724l-22.312-7.246c-8.406-3.034-14.012-11.031-14.012-19.976v-11.234h-118.25v11.234
		c0,8.945-5.605,16.942-14.011,19.976l-22.313,7.246c-0.949,0.309-1.594,1.211-1.594,2.187v7.61c0,1.258,1.027,2.286,2.289,2.286
		H442.23c1.262,0,2.289-1.028,2.289-2.286v-7.61C444.519,398.936,443.879,398.033,442.926,397.724z\"></path>
	<path class=\"st0\" d=\"M120.218,127.998H19.031C8.547,127.998,0,136.568,0,147.029v160.34h139.254v-160.34
		C139.254,136.568,130.68,127.998,120.218,127.998z M26.402,209.701h86.426c1.746,0,3.188,1.418,3.188,3.164v9.562
		c0,1.774-1.442,3.191-3.188,3.191H26.402c-1.75,0-3.164-1.418-3.164-3.191v-9.562C23.238,211.119,24.652,209.701,26.402,209.701z
		 M112.828,183.181H26.402c-1.75,0-3.164-1.414-3.164-3.164v-9.562c0-1.75,1.414-3.187,3.164-3.187h86.426
		c1.746,0,3.188,1.438,3.188,3.187v9.562C116.016,181.768,114.574,183.181,112.828,183.181z\"></path>
	<path class=\"st0\" d=\"M0,390.776c0,10.461,8.547,19.031,19.031,19.031h101.187c10.462,0,19.035-8.57,19.035-19.031v-73.962H0
		V390.776z M69.614,379.108c-9.114,0-16.528-7.414-16.528-16.528c0-9.14,7.414-16.531,16.528-16.531
		c9.14,0,16.531,7.391,16.531,16.531C86.145,371.693,78.754,379.108,69.614,379.108z\"></path>
</g>
</svg>";

$cfg['audit']['rank']['low'] = 4.5;
$cfg['audit']['rank']['high'] = 7;

?>