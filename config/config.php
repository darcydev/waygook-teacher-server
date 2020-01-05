<?php
ob_start();

if (!isset($_SESSION)) {
  session_start();
}

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT'] . '/Waygook-Teacher');

date_default_timezone_set('Asia/Seoul');