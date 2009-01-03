<?php

define('appName'      ,'phpCV');
define('appVersion'   ,'0.2b');
define('appAuthor'    ,'Ashaan');
define('appSubAuthor' ,'');
define('appLink'      ,'http://test.sygil.eu');

$chronoStart = microtime(true);
require_once('includes/class/template.php');
require_once('includes/class/cv.php');
require_once('includes/class/engine.php');

$engine = engine::getInstance();
$cv     = cv::getInstance();

$engine->initialize();
$cv->initialize();

$engine->execute();
$cv->execute();

$engine->finalize();
/*
$template = template::getInstance();

$header['menu'] = '';//$engine->menu;

$main = array(
  'header'        => $template->evaluate('header' ,$header),
 // 'content'       => $template->evaluate('content' ,array('content'  => $cv->html)),
  'footer'        => $template->evaluate('footer' ,array()),
  'job'           => $cv->header['job'],
  'subjob'        => $cv->header['subjob'],
  'fullname'      => $cv->header['fullname'],
  'theme'         => $engine->getThemeList(),
  'themesel'      => $engine->getSwitchTheme(),
  'cvsel'         => $engine->getSwitchCV(),
  'langue'        => $engine->getSwitchLang(),
);

$html = $template->evaluate('main',$main);
*/

?>
