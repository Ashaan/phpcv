<?php

class engine
{
  static $instance  = null;
  public $theme     = '';
  public $cv        = '';
  public $template  = '';
  public $lang      = '';
  public $lang_list = array();
  private $menu     = '';
  private $header   = '';
  private $option   = array();
  private $path     = '';

  static function getInstance()
  {
    if (is_null(engine::$instance)) {
      engine::$instance = new engine();
    }
    return engine::$instance;
  }

  public function __construct()
  {
    $this->path = $_SERVER['DOCUMENT_ROOT'].substr($_SERVER["REQUEST_URI"],0,strrpos($_SERVER["REQUEST_URI"],'/'));
    $this->loadConfig();
    template::getInstance($this->template);
  }

  private function loadConfig()
  {
    if(file_exists('includes/config.php')) {
      require_once('includes/config.php');
    } else
    if(file_exists('includes/config.sample.php')) {
      require_once('includes/config.sample.php');
    } else {
      echo 'Erreur Fatal - Pas de fichier de configuration';
      exit;
    }

    $this->template  = isset($_GET['tpl'])   ? $_GET['tpl']   : $default['template'];
    $this->cv        = isset($_GET['cv'])    ? $_GET['cv']    : $default['cv'];
    $this->lang      = isset($_GET['lang'])  ? $_GET['lang']  : $default['lang'];
    $this->theme     = isset($_GET['theme']) ? $_GET['theme'] : $default['theme'];
    $this->lang_list = isset($lang)          ? $lang          : array('gb' => 'english');
    $this->option    = isset($option)        ? $option        : array();
  }

  public function initialize()
  {
    $template = template::getInstance();
    $template->set(
      array(
        'main','header','content','box','footer',
        'menu_element',
        'theme_select_element','theme_select'
      )
    );
  }

  public function execute()
  {
    $template = template::getInstance();
    $template->load();
  }

  private function readInfo($filename)
  {
    $file = fopen($filename,'r');
    $value = fread($file,filesize($filename));
    fclose($file);
    return $value;
  }

  public function addMenu($title,$code,$active)
  {
    $template = template::getInstance();
    if ($active) {
      $option = 'submenuh';
    } else {
      $option = 'submenu';
    }
    $this->menu .= $template->evaluate('menu_element' , array('code' => $code,'class' => $option, 'name' => $title));

  }
  private function getSwitchCV()
  {
    $content = '';

    if(isset($this->option['multi-cv']) && $this->option['multi-cv']) {
      $path    = $this->path.'/data/';
      if (is_dir($path)) {
        $list = scandir($path);
        foreach ($list as $value) {
          if ($value != '..' && $value != '.' && $value != '.svn') {
            $name = $this->readInfo($path.'/'.$value.'/info.cv');
            $option = '';
            if ($value==$this->cv) {
              $option = 'SELECTED';
            }
            $content  .= '<option onclick="window.location.href=\'?cv='.$value.'&lang='.$this->lang.'&tpl='.$this->template.'\';" '.$option.'>'.$name.'</option> ';
          }
        }
      }
      $content  = '<select>'.$content .'</select>';
    }

    return $content ;
  }

  public function getSwitchLang()
  {
    $content = '';
    if(isset($this->option['multi-lang']) && $this->option['multi-lang']) {
      foreach ($this->lang_list as $name => $value) {
        $content .= '
          <a href="?lang='.$name.'&cv='.$this->cv.'&tpl='.$this->template.'">
            <img src="images/'.$name.'.png" alt="{'.$value.'}"/>
          </a>
        ';
      }
    }
    return $content;
  }

  public function getThemeList()
  {
    $content = '';
    $path    = $this->path.'/themes/';

    if (is_dir($path)) {
      $list = scandir($path);
      foreach ($list as $value) {
        if ($value != '..' && $value != '.' && $value != '.svn') {
          if ($value == $this->theme) {
            $content .= '<link rel="stylesheet" type="text/css" href="themes/'.$value.'/theme.css" title="'.$value.'"/>';
          } else {
            $content .= '<link rel="alternate stylesheet" type="text/css" href="themes/'.$value.'/theme.css" title="'.$value.'"/>';
          }
        }
      }
    }
    return $content;
  }
  public function getSwitchTheme()
  {
    $content = '';
    if(isset($this->option['multi-theme']) && $this->option['multi-theme']) {
      $template = template::getInstance();
      $path    = $this->path.'/themes/';
      if (is_dir($path)) {
        $list = scandir($path);
        foreach ($list as $value) {
          if ($value != '..' && $value != '.' && $value != '.svn') {
            $name = $this->readInfo($path.'/'.$value.'/info.theme');
            $option = '';
            if ($value == $this->theme) {
              $option  = 'SELECTED';
            }
            $content .= $template->evaluate('theme_select_element', array('value' => $value, 'name'=>$name, 'option' => $option));
          }
        }
        $content = $template->evaluate('theme_select', array('elements' => $content));
      }
    }
    return $content;
  }

  private function getHeader()
  {
    $template = template::getInstance();
    $cv       = cv::getInstance();
    $header   = $cv->header;
    $header['menu'] = $this->menu;
    return $template->evaluate('header' ,$header);
  }

  private function translate($html)
  {
    require_once('langs/lang.'.$this->lang.'.php');
    foreach ($lang as $name => $value) {
      $html = str_replace('{'.$name.'}',$value,$html);
    }

    $html = str_replace('{appName}'   ,appName,$html);
    $html = str_replace('{appVersion}',appVersion,$html);
    $html = str_replace('{appLink}'   ,appLink,$html);
    $html = str_replace('{appAuthor}' ,appAuthor,$html);
    $html = str_replace('{appSubAuthor}',appSubAuthor,$html);
    $html = str_replace('{time}',((microtime(true) - $chronoStart)*1000).'ms',$html);

    return $html;
  }
  public function finalize()
  {
    $cv       = cv::getInstance();
    $template = template::getInstance();
    $main = array(
      'header'        => $this->getHeader(),
      'content'       => $template->evaluate('content' ,array('content'  => $cv->html)),
      'footer'        => $template->evaluate('footer' ,array()),
      'job'           => $cv->header['job'],
      'subjob'        => $cv->header['subjob'],
      'fullname'      => $cv->header['fullname'],
      'theme'         => $this->getThemeList(),
      'themesel'      => $this->getSwitchTheme(),
      'cvsel'         => $this->getSwitchCV(),
      'langue'        => $this->getSwitchLang(),
    );

    $html = $template->evaluate('main',$main);

    echo $this->translate($html);
  }
}

?>
