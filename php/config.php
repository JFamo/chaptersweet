<?php
session_start();
if($_SESSION['chapter'] == 'freshman'){
	//define database name for freshmen
	define('DB_NAME', 'chapters_chapterfresh');
}
else{
	//define database name for non-freshmen
	define('DB_NAME', 'chapters_chaptersweet');
}
//access via root user
//TODO - make a seperate user for this program
define('DB_USER', 'chapters_root');
//password (super secret)
//~~~NOTE TO SELF~~~ change this before uploading to github
//dont want any
//	~~~~~~~~SPOOOKY~~~~~~~~~~~~
//  ~~~~~~~~~~~~~~HACKER~~~~~~~
//  ~~~~PEOPLE~~~~~~~~~~~~~~~~~
//seeing this
define('DB_PASSWORD', '');
//define host as localhost, connecting to own machine
define('DB_HOST', 'localhost');
//charset is utf8, latin
define('DB_CHARSET', 'utf8');
?>