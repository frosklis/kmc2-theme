<?php
/* Welcome to kmc2 :)
Thanks to the fantastic work by kmc2 users, we've now
the ability to translate kmc2 into different languages.

Developed by: Eddie Machado
URL: http://themble.com/kmc2/
*/



// Adding Translation Option
load_theme_textdomain( 'kmc2theme', get_template_directory() .'/library/translation' );
	$locale = get_locale();
	$locale_file = get_template_directory() ."/library/translation/$locale.php";
if ( is_readable($locale_file) ) require_once($locale_file);








?>