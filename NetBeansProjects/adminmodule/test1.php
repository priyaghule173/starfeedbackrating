<?php
/*
  Template Name: rating
 */



$ThisScript = basename(__FILE__, '.php');
//Common Functions
require_once(ABSPATH . '/dwpivr/common.php');
initconfig(); 


createConnection();
//logGlobals($ThisScript);

summarizeAttendance();

// Wrap up script
closeAll();
?>