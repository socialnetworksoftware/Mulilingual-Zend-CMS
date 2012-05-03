<?php
function tep_db_input($string) 
{
 return addslashes($string);
}
function tep_db_prepare_input($string) 
{
 if (is_string($string)) 
 {
  return trim(tep_sanitize_string(stripslashes($string)));
 } 
 elseif (is_array($string)) 
 {
  reset($string);
  while (list($key, $value) = each($string)) 
  {
   $string[$key] = tep_db_prepare_input($value);
  }
  return $string;
 } 
 else 
 {
  return $string;
 }
}
function tep_sanitize_string($string) 
{
 $patterns = array ('/ +/','/[<>]/');
 $replace = array (' ', '_');
 return preg_replace($patterns, $replace, trim($string));
}
?>