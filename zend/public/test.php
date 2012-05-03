<?php
// load required classes
require_once 'Zend/Loader.php';
Zend_Loader::loadClass('Zend_Translate');

// set up array of translation keys
// French
$fr = array(
  'good_morning' => 'Bon jour',
  'how_are_you' => 'Comment allez-vous?'
);

// German
$de = array(
  'good_morning' => 'Guten morgen',
  'how_are_you'  => 'Wie geht es Ihnen?'
);

// set up translation adapter
$tr = new Zend_Translate('array', $de, 'de');
// add another translation
$tr->addTranslation($fr, 'fr');

// print German translation
// returns 'Guten morgen'
echo $tr->translate('good_morning', 'de');
echo "<br>";
$tr->setLocale('de');
echo $tr->translate('good_morning' );
echo "<br>";

// print French translation
// returns 'Bon jour'
echo $tr->translate('good_morning', 'fr');
?>