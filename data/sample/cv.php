<?php

$header = array(
  'job'       => 'Intitulé du Poste',
  'subjob'    => 'Référence importante',
  'fullname'  => 'Firstname LASTNAME',
);
$data = array();


/**
 * Page de presentation
 */
$presentation = array(
//  'photo'     => 'data/profile/image/myphoto.jpg',
  'lastname'  => 'Machin',
  'firstname' => 'CHOSE',
  'nationality'=> 'Inconnue',
  'age'       => '99 {yearold}',
  'birthdate' => '',
  'birthplace'=> '',
  'address'   => '',
  'homephone' => '',
  'portphone' => '',
  'email'     => 'ashaan@sygil.eu',
  'MSN'       => '',
  'Skype'     => '',
  'ICQ'       => '',
  'Jabber'    => '',
);
$data['presentation'] = array(
  'title' => '{presentation}',
  'name'  => '{presentation}',
  'code'  => 'presentation',
  'data'  => $presentation,
  'hide'  => false,
  'type'  => 'ListParamWithPhoto',
  'menu'  => true,
);


/**
 * Page de Competence
 */
$skill = array();
$skill['Skill List One'] = array('SKILL 1, SKILL 2');
$skill['Skill List Two'] = array('SKILL 1, SKILL 2');
$data['knowledge'] = array(
  'title' => '{knowledge_datapreocessing}',
  'name'  => '{competence}',
  'code'  => 'knowledge',
  'data'  => $skill,
  'hide'  => true,
  'type'  => 'TopBottom',
  'menu'  => true,
);


/**
 * Page Etude et formation
 */
$studies = array();
$studies['2002 - 2003'] = array('Formation 2');
$studies['2001'] = array('Formation 1');
$data['studies'] = array(
  'title' => '{formation}',
  'name'  => '{formation}',
  'code'  => 'studies',
  'data'  => $studies,
  'hide'  => true,
  'type'  => 'TopBottom',
  'menu'  => true,
);


/**
 * Page Divers
 */
$others = array();
$others['Divers 1'] = array('Machin, Chose');
$data['others'] = array(
  'title' => '{other}',
  'name'  => '{other}',
  'code'  => 'others',
  'data'  => $others,
  'hide'  => true,
  'type'  => 'TopBottom',
  'menu'  => true,
);


/**
 * Page Experience
 */
$xp = array();
$xp[0] = array(
  'year'    => '2006',
  'duration'=> '?? {month}',
  'place'   => 'UnLieu/Quelquepart',
  'job'     => 'Mon Boulot',
  'company' => 'Mon Entreprise',
  'mission' => 'Ma Mission',
  'action'  => array(
    'Mes Activités (verbe)'
  ),
);
$xp[3] = array(
  'year'    => '2005',
  'duration'=> '?? {month}',
  'place'   => 'UnLieu/Quelquepart',
  'job'     => 'Mon Boulot',
  'company' => 'Mon Entreprise',
  'mission' => 'Ma Mission',
  'action'  => array(
    'Mes Activités (verbe)'
  ),
  'techno'  => 'Techno Utilisé',
);

$data['experiences'] = array(
  'title' => '{experience_professionnel}',
  'name'  => '{experience}',
  'code'  => 'experiences',
  'data'  => $xp,
  'hide'  => true,
  'type'  => 'Experience',
  'menu'  => true,
);
?>



