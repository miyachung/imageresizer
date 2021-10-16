<?php
ob_start();
@ini_set('max_execution_time',0);
@ini_set('upload_max_filesize','51M');
@set_time_limit(0);
error_reporting(E_ALL ^ E_NOTICE);

require_once __DIR__.'/require/helper.class.php';
require __DIR__.'/require/resizer_design.php';
