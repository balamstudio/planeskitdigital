<?php

require_once($_SERVER['DOCUMENT_ROOT']."/libs/init.php");

die("soon available");
//pp($cfg); pd("aaaa1");
/*  INITIATE */
$get= $data = [];
$get['project']= $data['project'] = alt_isset($_REQUEST['p'],"VF");
$get['ticket']= $data['ticket'] = alt_isset($_REQUEST['t'],"1452731");

// get project params
$query_project = "SELECT * FROM `projects` WHERE id = '" . $db->escape($get['project']) . "' LIMIT 1";
$project = get_result($query_project);
//pp($project); die;
//writeLog($_SERVER["DOCUMENT_ROOT"] . '/project.log', "START\n" . print_r($project, true) . "END\n*****************************************************************************");
//writeLog($_SERVER["DOCUMENT_ROOT"] . '/fileinfo.log', "START\n" . print_r($_REQUEST, true) . "END\n*****************************************************************************");
//writeLog($_SERVER["DOCUMENT_ROOT"] . '/fileinfo.log', "\n" . print_r($audit_file, true) . "END\n*****************************************************************************");
$lang = $data['lang'] = $project['lang'];
$local_lang = strtolower($lang)."_".strtoupper($lang);

setlocale(LC_ALL,$local_lang.".UTF-8",$local_lang);



if($_SERVER['REQUEST_METHOD'] == 'POST') {
//pp($_POST);
    $quiz_tpl = $_POST['lang'].".thankyou.tpl";
    $prepareThanks= loadTemplateFile($quiz_tpl,$_POST);
    echo $prepareThanks;

} else {

    //load template
    $quiz_tpl = $project['quiz_tpl'];

// Abre el fichero para obtener el contenido existente
    /*pp($project);
    pe($project['audit_tpl']);*/
    $prepareAudit = loadTemplateFile($quiz_tpl,$data);
    echo $prepareAudit;
}

