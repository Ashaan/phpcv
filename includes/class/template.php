<?php

class template
{
  /**
   * Instance de la class
   *
   * @var template
   */
  static private $instance = null;
  /**
   * Liste des templates charger
   *
   * @var array of string
   */
  private $template = array();
  /**
   * Nom du template
   *
   * @var string
   */
  private $name     = '';
  /**
   * Instance de la DB (si DB)
   *
   * @var db
   */
  private $db       = null;

  /**
   * Creation/Recuperation de la class template
   *
   * @param  string $name
   * @return template
   */
  static function getInstance($name = null)
  {
    if (is_null(template::$instance) && !is_null($name)) {
      template::$instance = new template($name);
    }
    return template::$instance;
  }

  /**
   * Constructeur
   *
   * @param string $name
   */
  public function __construct($name)
  {
    $this->name = $name;
    if(class_exists('db')) {
      $this->db = db::getInstance();
    } else {
      $this->db = null;
    }
  }

  /**
   * Enter description here...
   *
   * @param array $tpls
   * @return boolean
   */
  public function set($tpls)
  {
    if (!is_array($tpls)) $tpls = array($tpls);

    foreach($tpls as $tpl) {
      if (!isset($this->template[$tpl])) {
        $this->template[$tpl] = '';
      }
    }

    return true;
  }

  public function load()
  {
    if (is_null($this->db)) {
      foreach($this->template as $name => $value) {
        $file = fopen('templates/'.$this->name.'/'.$name.'.tpl','r');
        $value = fread($file,filesize('templates/'.$this->name.'/'.$name.'.tpl'));
        fclose($file);
        $this->template[$name] = $value;
      }
    }
  }

  public function evaluate($tpl,$data = array(),$begin = '{',$end = '}')
  {
    $tpl = $this->template[$tpl];
    foreach ($data as $name => $value) {
      $tpl = str_replace($begin.$name.$end,$value,$tpl);
    }

    return $tpl;
  }
}

?>