<?php
/**
 * Contains definitions of constatns.
 * 
 * Created on 7-Mar-08
 * 
 * @author	Martin Majlis
 * 
 * $LastChangedDate: 2008-08-07 23:56:03 +0200 (Thu, 07 Aug 2008) $
 * $LastChangedRevision: 207 $
 * $Rev: 207 $
 */

require_once('globalFunctions.php');

/**
 * Directory separator.
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * New line.
 */ 
//define('NL', "\n");


// require site specific files
if ( ! isset($_SERVER['HTTP_HOST']) ) {
	$_SERVER['HTTP_HOST'] = 'localhost';
	define('COMMAND_LINE', 1);
} else {
	define('COMMAND_LINE', 0);
}

require_once($_SERVER['HTTP_HOST'].'.php');

/**
 * Directory with libraries.
 */ 
define('DIR_LIB', dirname(__FILE__).DS);

define('DIR_DATA', DIR_LIB.'data'.DS);

define('DIR_PAGES', DIR_DATA.'pages'.DS);

define('DB_FILE', DIR_DATA . '/parlament.sqlite');

/**
 * Directory with texts.
 */ 
define('DIR_TEXTS', DIR_DATA.DS.'texts'.DS);

/**
 * Directory for downloaded RSS feeds.
 */ 
define('DIR_CACHE', DIR_DATA.'cache'.DS);

define('DIR_CACHE_PAGES', DIR_CACHE.'pages'.DS);

define('DIR_CACHE_IMAGES', DIR_CACHE.DS.'images'.DS);

/**
 * Directory for unparseable inputs. 
 */
define('DIR_INVALID', DIR_DATA.'invalid'.DS);

/**
 *  Default time-stamp format for DB
 */
define('DB_TIME', 'Y-m-d H:i:s');

/**
 * Common table name prefix
 */
define('DB_TB_PREFIX', 'parlament_');

define('DB_TB_MEETING', DB_TB_PREFIX.'meeting');

define('DB_TB_VOTING', DB_TB_PREFIX.'voting');

define('DB_TB_PARTY', DB_TB_PREFIX.'party');

define('DB_TB_MEMBER', DB_TB_PREFIX.'member');

define('DB_TB_RESULT', DB_TB_PREFIX.'result');

$vlady = array(
	0 => array(
		'name' => '1992 - 1996 - Václav Klaus', 
		'primeMinister' => 'Václav Klaus',
		'url' => '1', 
		'period' => 1,
		'from' => '1992-07-02', 
		'to' => '1996-07-04',
		'alliance' => array('KDS', 'KDU-ČSL', 'ODA', 'ODS')
	), 
	1 => array(
		'name' => '1996 - 1998 - Václav Klaus',
		'primeMinister' => 'Václav Klaus', 
		'url' => '2', 
		'period' => 2,
		'from' => '1996-07-04', 
		'to' => '1998-01-02', 
		'alliance' => array('ODA', 'ODS')
	),
	2 => array(
		'name' => '1998 - Josef Tošovský',
		'primeMinister' => 'Josef Tošovský', 
		'url' => '2', 
		'period' => 3,
		'from' => '1998-01-02', 
		'to' => '1998-07-22', 
		'alliance' => array('ČSSD', 'KDU-ČSL', 'KSČM', 'Nezařazení', 'ODA', 'ODS', 'SPR-RSČ'), 
	),	 
	
	3 => array(
		'name' => '1998 - 2002 - Miloš Zeman',
		'primeMinister' => 'Miloš Zeman',  
		'url' => '3',
		'period' => 4,
		'from' => '1998-07-02', 
		'to' => '2002-07-15', 
		'alliance' => 	array('ČSSD') 		
	),
	
	4 => array(
		'name' => '2002 - 2004 - Vladimír Špidla',
		'primeMinister' => 'Vladimír Špidla',  	
		'url' => '4', 
		'period' => 5,
		'from' => '2002-07-15', 
		'to' => '2004-08-04', 
		'alliance' => 	array('ČSSD', 'KDU-ČSL', 'US-DEU')
	),
	5 => array(
		'name' => '2004 - 2005 - Stanislav Gross', 
		'primeMinister' => 'Stanislav Gross',  
		'url' => '4', 
		'period' => 6,
		'from' => '2004-08-04', 
		'to' => '2005-04-25', 
		'alliance' => 	array('ČSSD', 'KDU-ČSL', 'US-DEU')		
	),		
	6 => array(
		'name' => '2005 - 2006 - Jiří Paroubek',
		'primeMinister' => 'Jiří Paroubek',  
		'url' => '4', 
		'period' => 7,
		'from' => '2005-04-25', 
		'to' => '2006-08-16', 
		'alliance' => 	array('ČSSD', 'KDU-ČSL', 'US-DEU')		
	),		
	
	7 => array(
		'name' => '2006 - Jiří Paroubek', 
		'primeMinister' => 'Jiří Paroubek',  
		'url' => '5', 
		'period' => 8,	
		'from' => '2006-08-16', 
		'to' => '2006-09-04',  
		'alliance' => 	array('ČSSD')		
	), 
	8 => array(
		'name' => '2006 - Mirek Topolánek', 
		'primeMinister' => 'Mirek Topolánek',  
		'url' => '5',  
		'period' => 9,
		'from' => '2006-09-04', 
		'to' => '2006-10-11',  
		'alliance' => 	array('ODS')		
	), 
	9 => array(
		'name' => '2006 - 2007 - Mirek Topolánek', 
		'primeMinister' => 'Mirek Topolánek',  
		'url' => '5',  
		'period' => 10,
		'from' => '2006-10-11', 
		'to' => '2007-01-09', 
		'alliance' => 	array('ODS')
	), 
	10 => array(
		'name' => '2007 - x - Mirek Topolánek', 
		'primeMinister' => 'Mirek Topolánek',  
		'url' => '5', 
		'period' => 11,
	 	'from' => '2007-01-09', 
		'to' => '2008-08-20', 
		'alliance' => 	array('ODS', 'KDU-ČSL', 'SZ')
	)			
);

$encode = array(
		'/' => '_01_', 
		':' => '_02_', 
		'?' => '_03_', 
		'*' => '_04_', 
		'&' => '_05_',
		'=' => '_06_'				
	);	
	
define('PSP_URL', 'http://www.psp.cz/sqw/');
define('PSP_URL_OBDOBI', PSP_URL.'hlasovani.sqw?o=%d');
define('PSP_URL_SCHUZE', PSP_URL.'phlasa.sqw?o=%d&s=%d&pg=%d');
define('PSP_URL_VOTE', PSP_URL.'hlasy.sqw?G=%d&o=%d');
define('PSP_URL_MEMBER', PSP_URL.'detail.sqw?id=%d&o=%d');

define('VOTE_NO', 0);
define('VOTE_YES', 1);	
define('VOTE_MISSING', 2);
define('VOTE_EXCUSED', 3);
define('VOTE_FORBORED', 4);
define('VOTE_UNVOTE', 5);	


/**
 * Loads $url with $timeOut and returns content in UTF-8.
 * 
 * Loads $url with $timeOut and returns content in UTF-8. If http code is 
 * 301 or 302 redirected content is returned. If  http code is different 
 * than 200, empty string is returned. 
 * 
 * @param	$url		URL of document
 * @param	$timeOut	timeout for document
 * @return 	page content 
 */
function loadUrl($url, $timeOut)
{	
	$ch = curl_init();

	curl_setopt ( $ch, CURLOPT_URL, $url);
	curl_setopt ( $ch, CURLOPT_HEADER, 1);
	curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeOut);
	curl_setopt ( $ch , CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux x86_64; en-GB; rv:1.8.1.12) Gecko/20080212 Ubuntu/8.04 (hardy) Firefox/2.0.0.12');
	
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array(
		'Accept-Charset: utf-8;q=0.9')
		);
	ob_start();
	curl_exec ($ch);
	$str = ob_get_clean();
	$code = curl_getinfo($ch,CURLINFO_HTTP_CODE);

	$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

	// divide content	
	$header = substr($str, 0, $headerSize);
	$body = substr($str, $headerSize);
	
	
	// process redirection
	//msg('Load: '.$code.': '.$url);
	if ( $code == 302 || $code == 301 ) { 
		if ( preg_match('~Location: (.*)~', $str, $match) ) { 
			
			$nUrl = substr($match[1], 0, -1);
			 
			return loadUrl($nUrl, $timeOut);
		}
	}

	// different header than 200 OK
	if ( $code != 200) { 
		$body = '';
	}

	// change charset to UTF-8	
	$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	

	if ( $body && preg_match('~charset=(.*)~', $contentType, $match) ) { 
		
		$charset = strtolower($match[1]);
		
		if ($charset != 'utf-8') { 

			$body = iconv($charset, 'utf-8', $body);
			$body = str_replace('encoding="'.$charset.'"', 'encoding="utf-8"', $body);
			
		}
	} else {	
		
		if ( $body && strpos($body, 'encoding="windows-1250"') ) { 
				$body = iconv('windows-1250', 'utf-8', $body);
				$body = str_replace('encoding="windows-1250"', 'encoding="utf-8"', $body);
		}
		
		if ( $body && strpos($body, 'encoding="ISO-8859-2"') ) {
				$body = iconv('ISO-8859-2', 'utf-8', $body);
				$body = str_replace('encoding="ISO-8859-2"', 'encoding="utf-8"', $body);
		}	
	}

	curl_close ($ch);	
	
	return $body;	
}

/**
 * Returns visitor's IP.
 * 
 * @return	visitor's IP
 */
function getIPAddress()
{
	if ($_SERVER['HTTP_X_FORWARDED_FOR']) { 
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else { 
		return $_SERVER['REMOTE_ADDR'];
	}
}

	/**
	 * Decodes string. 
	 * 
	 * @param	$str	encoded URL
	 * @return 	decoded URL
	 */
	function decodeUrl($str)
	{
		global $encode;
		return strtr($str, array_flip($encode));
	}

	/**
	 * Encodes url. 
	 * 
	 * @param	$url	URL
	 * @return	encoded URL
	 */
	function encodeUrl($url)
	{
		global $encode;
		return strtr($url, $encode);
	}
 
function inf($str)
{
	$d = date(DB_TIME, time());
	echo $d.': '.$str.NL;
	if ( ! COMMAND_LINE ) { 
		echo '<br />';
	}	
}

function txtErr($str, $file, $line)
{
	echo $file.':'.$line.NL;
	if ( ! COMMAND_LINE ) { 
		echo '<br />';
	}	
	echo $str.NL;
	if ( ! COMMAND_LINE ) { 
		echo '<br />';
	}	
	exit(1);
}

/*
 * Create required folders
*/
if ( ! is_dir(DIR_INVALID)) {
	inf("Creating folder " . DIR_INVALID);
	mkdir(DIR_INVALID, 0777, true);
}
if ( ! is_dir(DIR_CACHE_IMAGES)) {
	inf("Creating folder " . DIR_CACHE_IMAGES);
	mkdir(DIR_CACHE_IMAGES, 0777, true);
}
if ( ! is_dir(DIR_CACHE_PAGES)) {
	inf("Creating folder " . DIR_CACHE_PAGES);
	mkdir(DIR_CACHE_PAGES, 0777, true);
}
if ( ! is_dir(DIR_PAGES)) {
	inf("Creating folder " . DIR_PAGES);
	mkdir(DIR_PAGES, 0777, true);
}
if ( ! is_dir(DIR_TEXTS)) {
	inf("Creating folder " . DIR_TEXTS);
	mkdir(DIR_TEXTS, 0777, true);
}

// connect to the database

$dbh = null;
$dsn = 'mysql:dbname='.DB_DB.';host='.DB_HOST;
$user = DB_USER;
$password = DB_PASSWORD;

try {
	$dbh = new PDO($dsn, $user, $password);
	inf("Connected to MySQL");
} catch (PDOException $e) {
	echo 'Connection failed: ' . $e->getMessage();
	if ( $e->getCode() == 1049 ) {
		mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
		mysql_query('CREATE DATABASE '.DB_DB);
		inf("Database " . DB_DB . ' was created');
	}
}

if ( ! $dbh ) {	
	try {
		$dbh = new PDO($dsn, $user, $password);	
	} catch (PDOException $e) {
		inf("Can not connect to MySQL database!");
		inf($e->getMessage());
		$dsn = 'sqlite:'.DB_FILE;
		try {
			$dbh = new PDO($dsn, $user, $password);
			inf("Using SQLite database");
		} catch (PDOException $e) {
			inf("Can not create SQLite database.");
			txtErr($e->getMessage());
		}
	}
}

$dbh->query("SET CHARACTER SET utf8");

/* create tables */

$sql_tables = file_get_contents('db.sql');
$tables = split('--', $sql_tables);
foreach ($tables as $table) {
	if ( preg_match('~CREATE~', $table) ) {
		$dbh->query($table);
	}
}

?>
