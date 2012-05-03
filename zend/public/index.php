<?php
error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', true);
date_default_timezone_set('Europe/London');

$rootDir = dirname(dirname(__FILE__));
set_include_path($rootDir . '/library' . PATH_SEPARATOR . get_include_path());

require_once 'extra_functions.php';
require_once 'table_names.php';
require_once 'Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();


// Initialise Zend_Layout's MVC helpers
Zend_Layout::startMvc(array('layoutPath' => $rootDir.'/application/layouts/scripts/'));
$lang='en';

$config = new Zend_Config(require '../application/config.php');

$title  = $config->appName;
$params = $config->database->toArray();
Zend_Registry::set('title',$title);

$DB = new Zend_Db_Adapter_Pdo_Mysql($params);
  
$DB->setFetchMode(Zend_Db::FETCH_OBJ);
Zend_Registry::set('DB',$DB);


Zend_Controller_Front::run('../application/controllers');
?>
