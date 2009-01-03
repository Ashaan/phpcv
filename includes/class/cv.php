<?php

class cv
{
  static  $instance = null;
  public  $header = array();
  private $data   = array();
  public  $html   = '';

  static function getInstance()
  {
    if (is_null(cv::$instance)) {
      cv::$instance = new cv();
    }
    return cv::$instance;
  }

  public function __construct()
  {
    $engine = engine::getInstance();
    require_once('data/'.$engine->cv.'/cv.php');
    if (file_exists('data/'.$engine->cv.'/lang.'.$engine->lang.'.php')) {
      require_once('data/'.$engine->cv.'/lang.'.$engine->lang.'.php');
    }
    $this->header = $header;
    $this->data   = $data;
  }

  function initialize()
  {
    $template = template::getInstance();
    foreach ($this->data as $value) {
      if ($value['type'] == 'ListParamWithPhoto') {
        $template->set(array('photo','params_container','params_element'));
      } else
      if ($value['type'] == 'TopBottom') {
        $template->set(array('topbottom'));
      } else
      if ($value['type'] == 'Experience') {
        $template->set(array('experiences_element','experiences_subelement','experiences_year'));
      }
    }
  }

  function execute()
  {
    $engine = engine::getInstance();
    $this->html = '';
    foreach ($this->data as $value) {
      $func = 'add'.$value['type'];
      $result = $this->$func($value);
      $this->html .= $result;
      if ($value['menu'] && $result != '') {
        $engine->addMenu($value['name'],$value['code'],!$value['hide']);
      }
    }
  }

  function addListParamWithPhoto($info)
  {
    $template = template::getInstance();
    $content  = '';

    if (isset($info['data']['photo'])) {
      if (is_array($info['data']['photo'])) {

      } else {
        $content .= $template->evaluate('photo',array('photo' => $info['data']['photo']));
      }
    }

    $elements = '';
    foreach ($info['data'] as $name => $value) {
      if($name != 'photo') {
        $elements .= $template->evaluate('params_element',array('name' => $name,'value' => $value));
      }
    }
    $content .= $template->evaluate('params_container',array('elements' => $elements));

    $array = array(
      'code'    => $info['code'],
      'title'   => $info['title'],
      'option'  => 'style="display:'.($info['hide']?'none':'block').'"',
      'content' => $content,
    );
    return $template->evaluate('box',$array);
  }

  function addTopBottom($info)
  {
    $template = template::getInstance();
    $content  = '';
    foreach ($info['data'] as $name => $value) {
      $content .= $template->evaluate('topbottom',array('top' => $name,'bottom' => str_replace('<br/>, ','<br/>',implode(', ', $value))));
    }

    $array = array(
      'code'    => $info['code'],
      'title'   => $info['title'],
      'option'  => 'style="display:'.($info['hide']?'none':'block').'"',
      'content' => $content,
    );
    return $template->evaluate('box',$array);
  }

  function addExperience($info)
  {
    $template = template::getInstance();

    $years = array();
    foreach ($info['data'] as $data) {
      $elements = '';
      if (!isset($data['job']) || $data['job'] == '') {
        $data['job'] = '-';
      }
      if (isset($data['company'])) {
        $elements .= $template->evaluate('experiences_subelement',array('title' => '{company}','info' => $data['company']));
      }
      if (isset($data['mission'])) {
        $elements .= $template->evaluate('experiences_subelement',array('title' => '{mission}','info' => $data['mission']));
      }
      if (isset($data['action'])) {
        $actions = '';
        foreach($data['action'] as $action) {
          $actions .='<li>'.$action.'</li>';
        }

        $elements .= $template->evaluate('experiences_subelement',array('title' => '{activity}','info' => '<ul>'.$actions.'</ul>'));
      }
      if (isset($data['techno'])) {
        $elements .= $template->evaluate('experiences_subelement',array('title' => '{technology}','info' => $data['techno']));
      }
      $data['elements'] = $elements;

      $array = array();
      if(isset($data['place']))    $array[] = $data['place'];
      if(isset($data['duration'])) $array[] = $data['duration'];
      $data['info'] = implode(' - ',$array);

      if (isset($years[$data['year']])) {
        $years[$data['year']] = $template->evaluate('experiences_element',$data).$years[$data['year']];
      } else {
        $years[$data['year']] = $template->evaluate('experiences_element',$data);
      }
    }

    $content = '';
    foreach ($years as $year => $data) {
      $content = $template->evaluate('experiences_year',array('value' => $year,'data' => $data)).$content;
    }
    $content = str_replace('LANG[','{',$content);
    $content .= '
      <script type="text/javascript">
        for (i=0; i<xplock.length; i++) {
          if(document.getElementById("xp"+xplock[i])) {
            document.getElementById("xp"+xplock[i]).style.display  = "block";
          }
        }
      </script>
    ';

    $array = array(
      'code'    => $info['code'],
      'title'   => $info['title'],
      'option'  => 'style="display:'.($info['hide']?'none':'block').'"',
      'content' => $content,
    );
    return $template->evaluate('box',$array);
  }

  function addDownload($info)
  {
    $engine = engine::getInstance();
    $template = template::getInstance();

    $path = 
$_SERVER['DOCUMENT_ROOT'].'/data/'.$engine->cv.'/download/';

    $content = '';
    if (is_dir($path)) {
      $list = scandir($path);
      foreach ($list as $value) {
        if ($value != '..' && $value != '.') {
          $content .= '<a href="data/'.$engine->cv.'/download/'.$value.'">'.$value.'</a><br/>';
        }
      }
      $array = array(
        'code'    => $info['code'],
        'title'   => $info['title'],
        'option'  => 'style="display:'.($info['hide']?'none':'block').'"',
        'content' => $content,
      );
      return $template->evaluate('box',$array);
    } else {
      return '';
    }
  }
}

?>
