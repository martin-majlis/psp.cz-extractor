<?php
/**
 * Zakladni funkce
 *
 * Pouzivany v cele rade projektu
 * @author Martin Majlis (http://m-core.net)
 * @version 0.1
 * 
 * 
 * $LastChangedDate: 2008-03-17 14:19:31 +0100 (Mon, 17 Mar 2008) $
 * $LastChangedRevision: 44 $
 * $Rev: 44 $
 */
define('DAY_INVALID', -1);
define('DAY_MONDAY', 1);
define('DAY_TUESDAY', 2);
define('DAY_WEDNESDAY', 3);
define('DAY_THURSDAY', 4);
define('DAY_FRIDAY', 5);
define('DAY_SATURDAY', 6);
define('DAY_SUNDAY', 0);
define('NL', "\n");
define('TAB', "\t");
/**
    TODO    uparvit funkce getGet, getPost, prepareVar, aby fungovaly
*/

require_once('charset2ascii.php');

global $global_utf,$global_asc; 

/* czech, greek, german and russian characters to ASCII */
$global_utf=explode(',',"á,č,ď,é,ě,í,ĺ,ľ,ň,ó,ř,ŕ,š,ť,ú,ů,ý,ž,ä,ë,ö,ü,Á,Č,Ď,É,Ě,Í,Ĺ,Ľ,Ň,Ó,Ř,Š,Ť,Ú,Ů,Ü,Ý,Ž,Ä,Ë,Ö,?,?,ß,?,?,?,?,?,?,?,?,?,?,?,?,µ,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,??,??,??,??,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,? ,? ,? ,? ,?  ,?  ,? ,? ,? ,?,? ,? ");
$global_asc=explode(',',"a,c,d,e,e,i,l,l,n,o,r,r,s,t,u,u,y,z,a,e,o,u,A,C,D,E,E,I,L,L,N,O,R,S,T,U,U,U,Y,Z,A,E,O,a,a,v,c,d,e,e,z,i,i,t,i,i,k,l,m,n,x,o,o,p,r,s,s,t,i,i,f,x,x,o,o,A,A,V,C,D,E,E,Z,I,I,T,I,I,K,L,M,N,X,O,O,P,R,S,T,I,I,F,X,X,O,O,Q ,q ,X ,x ,B,b,C,c,D,d,E,E,e,F,f,G,g,H,h,I,i,J,j,K,k,L,l,M,m,N,n,P,p,Y,y,Z,z,Ch,ch,Sh,sh,Sht,sht,Zh,zh,ja,j,ju,Ju");

if (!is_callable('createPassword')) {
	function createPassword($length)
	{
		global $global_asc;
		$str = '';
		for ($i = 0; $i < $length; $i++) {
			$str .= $global_asc[rand(0, 100)];
		}
		return $str;
	}
}

/**
    dny v tydnu
*/    
$daysInWeek = array(
    DAY_INVALID => array(
        'short' => '--',
        'long' => '------'),
    DAY_SUNDAY => array(
        'short' => 'Ne',
        'long' => 'Nedele'),
    DAY_MONDAY => array(
        'short' => 'Po',
        'long' => 'Pondeli'),
    DAY_TUESDAY => array(
        'short' => 'Ut',
        'long' => 'Utery'),
    DAY_WEDNESDAY => array(
        'short' => 'St',
        'long' => 'Streda'),
    DAY_THURSDAY => array(
        'short' => 'Ct',
        'long' => 'Ctvrtek'),
    DAY_FRIDAY => array(
        'short' => 'Pa',
        'long' => 'Patek'),
    DAY_SATURDAY => array(
        'short' => 'So',
        'long' => 'Sobota')
    );

/*
  soubor s uzitecnymi funkcemi
*/


if (!is_callable('getPost')) { 
    
    /**
      vraci $_POST[key] modifikovany parametry, osetruje ruzne stavy 
      magic_quotes_gpc
      defaultne se oescapuje
      \fn getPost($varName, $mode = 1)
      @param  $varName  nazev promenne
      @param  $mode  udava, zpusob upraveni vraceneho retezce
        - 0 - retezec je vracen presne takovy, jak ma byt, bez zpetnych lomitek (defaultne)
        - 1 - je pouzita funkce addslashes + kontrola magic_guotes_gpc
        - 2 - vystup je osetren funkci htmlspecialchars s ENT_QUOTES
      @return upraveny retezec  
    */
    function getPost($varName, $mode = 1){
        if (is_array($_POST[$varName])) {
            $_POST[$varName] = $_POST[$varName][0];
        }        
      $magic = get_magic_quotes_gpc();
      switch($mode){
        case 0:
          if($magic && isset($_POST[$varName])){
            return stripslashes($_POST[$varName]);
          }else{
            return $_POST[$varName];
          }
        case 1:
          if($magic || !isset($_POST[$varName])){
            return $_POST[$varName];
          }else{
            return addslashes($_POST[$varName]);
          }
        case 2:
            if (isset($_POST[$varName])) {
                if($magic){
                    return htmlspecialchars(stripslashes($_POST[$varName]),ENT_QUOTES);
                }else{
                    return htmlspecialchars($_POST[$varName],ENT_QUOTES);
                }
            } else {
                return NULL;
            }
        default:
          echo 'getPost: $mode> '.$mode.' - neznamy mod';      
      }    
      return NULL;
    }
}

if (!is_callable('getGet')) { 
    
    /**
      vraci $_GET[key] modifikovany parametry, osetruje ruzne stavy 
      magic_quotes_gpc
        defaultne se oescapuje
      \fn getGet($varName, $mode = 1)
      @param  $varName  nazev promenne
      @param  $mode  udava, zpusob upraveni vraceneho retezce
        - 0 - retezec je vracen presne takovy, jak ma byt, bez zpetnych lomitek (defaultne)
        - 1 - je pouzita funkce addslashes + kontrola magic_guotes_gpc
        - 2 - vystup je osetren funkci htmlspecialchars s ENT_QUOTES
      @return upraveny retezec  
    */
    function getGet($varName, $mode = 1)
    {
        if (is_array($_GET[$varName])) {
            $_GET[$varName] = $_GET[$varName][0];
        }        
        $magic = get_magic_quotes_gpc();
        switch($mode){
            case 0:
              if($magic && isset($_GET[$varName])){          
                return stripslashes($_GET[$varName]);
              }else{
                return $_GET[$varName];
              }
            case 1:
              if($magic || !isset($_GET[$varName])){
                return $_GET[$varName];
              }else{
                return addslashes($_GET[$varName]);
              }
            case 2:
                if (isset($_GET[$varName])) {
                    if($magic){
                        return htmlspecialchars(stripslashes($_GET[$varName]),ENT_QUOTES);
                    }else{
                        return htmlspecialchars($_GET[$varName],ENT_QUOTES);
                    }
                }
                return NULL;
            default:
              echo 'getGET: $mode> '.$mode.' - neznamy mod';      
        }    
        return NULL;
    }
}

if (!is_callable('prepareVar')) { 
    
    function prepareVar($varName, $mode = 0){
      $magic = get_magic_quotes_gpc();
        if (is_array($varName)) {
            $varName = $varName[0];
        }          
      switch($mode){
        case 0:
          if($magic && isset($varName)){
            return stripslashes($varName);
          }else{
            return $varName;
          }
        case 1:
          if($magic || !isset($varName)){
            return $varName;
          }else{
            return addslashes($varName);
          }
        case 2:
            if (isset($varName)) {
                if($magic){
                    return htmlspecialchars(stripslashes($varName),ENT_QUOTES);
                }else{
                    return htmlspecialchars($varName,ENT_QUOTES);
                }
            } else {
                return NULL;
            }
        default:
          echo 'getGET: $mode> '.$mode.' - neznamy mod';      
      }    
      return NULL;
    }

}

if (!is_callable('err')) { 
    /**
      vypise chybove hlaseni
      \fn err($msg)
      @param $msg - text zpravy
    */  
    function err($msg){
      echo '<div style="border:1px solid red; background-color:white; color:red;padding:5px;">';
      echo '<b>'.get_class($this).'</b>: '.$msg;
      echo '</div>';
    }
}

if (!is_callable('msg')) { 
    
    /**
      vypise hlaseni
      \fn msg($msg)
      @param $msg - text zpravy
    */  
    function msg($msg, $file = NULL, $line = NULL){
      echo '<div style="border:1px solid blue; background-color:white; color:blue;padding:5px;">';
      if (isset($file) || isset($line)) {
        echo $file.', '.$line.'<hr>';
      }
    	echo '<table border="1" style="border-collapse:collapse">';	
      $tr = debug_backtrace();
      for ($i = 0; $i  < 2; $i++) {
      	$file = file($tr[$i]['file']);

      	$str = '';
      	for ($j = $tr[$i]['line']-3; $j < $tr[$i]['line'] + 3; $j++) { 
      		$str .= $file[$j];
      	}

      	$hl = highlight_string("<?php \n".$str, TRUE);
      	
      	$hl = str_replace('&lt;?php&nbsp;<br />', '', $hl);
      	$h = md5($tr[$i]['file'].$tr[$i]['line']);
      	echo '<tr>' .
      			'<td>'.$tr[$i]['file'].'</td>' .
      			'<td>'.$tr[$i]['line'].'</td>' .
      			'<td>'.$tr[$i]['class'].'::'.$tr[$i]['function'].'</td>' .
      			'<td> <a href="#" onclick="document.getElementById(\''.$h.'\').style.display=\'none\'">Zobrazit</a>' .
      			'	<pre id="'.$h.'" style="display:none">'.$hl.'</pre></td>'.
      		'</tr>';
      } 
      echo '</table>';      
      echo $msg;
      echo '</div>';
    }
}

if (!is_callable('dump')) {
    /**
      provede var_dump
      \fn dump($msg)
      @param $msg - text zpravy
    */  
    function dump($msg, $file = NULL, $line = NULL){
      echo '<div style="text-align:left; border:1px solid blue; background-color:white; color:blue;padding:5px;">';
      if (isset($file) || isset($line)) {
        echo $file.', '.$line.'<hr>';
      }      

    	echo '<table border="1" style="border-collapse:collapse">';	
      $tr = debug_backtrace();
      for ($i = 0; $i  < 3; $i++) {
      	$file = file($tr[$i]['file']);

      	$str = '';
      	for ($j = $tr[$i]['line']-3; $j < $tr[$i]['line'] + 3; $j++) { 
      		$str .= $file[$j];
      	}

      	$hl = highlight_string("<?php \n".$str, TRUE);
      	
      	$hl = str_replace('&lt;?php&nbsp;<br />', '', $hl);
      	$h = md5($tr[$i]['file'].$tr[$i]['line']);
      	echo '<tr>' .
      			'<td>'.$tr[$i]['file'].'</td>' .
      			'<td>'.$tr[$i]['line'].'</td>' .
      			'<td>'.$tr[$i]['class'].'::'.$tr[$i]['function'].'</td>' .
      			'<td> <a href="#" onclick="document.getElementById(\''.$h.'\').style.display=\'none\'">Zobrazit</a>' .
      			'	<pre id="'.$h.'" style="display:none">'.$hl.'</pre></td>'.
      		'</tr>';
      } 
      echo '</table>';
      
      echo '<pre>';
        ob_start();
        var_dump($msg);
        $content = ob_get_contents();
        ob_end_clean();
        $content = htmlspecialchars($content);
        echo $content;
      echo '</pre>';
      echo '</div>';
    }
}

if (!is_callable('dbg')) { 
    
    function dbg($msg){
      if(DEBUG === true){
        echo '<div style="border:1px solid green; background-color:#DEDEDE; font-size:60%; background-color:white; color:blue;padding:5px;">';
        echo $msg;
        echo '</div>';
      }
    }
}

if (!is_callable('redirect')) {
  function redirect($url, $server = '')
  {   
	
  	if ($url[0] == '.') { 
  		$url = $server.substr($url, 2);
  	}

    $url = html_entity_decode($url);

    header("HTTP/1.1 301 Moved Permanently");
    header("Location: ".$url);
    header("Connection: Close");
    exit;
        
  }
}

if (!is_callable('sendHTTPStatus')) { 
        
    function sendHTTPStatus($num = 0) {
      if ($num == 0) {
        $num = 200;
      }
       static $http = array (
           100 => "HTTP/1.1 100 Continue",
           101 => "HTTP/1.1 101 Switching Protocols",
           200 => "HTTP/1.1 200 OK",
           201 => "HTTP/1.1 201 Created",
           202 => "HTTP/1.1 202 Accepted",
           203 => "HTTP/1.1 203 Non-Authoritative Information",
           204 => "HTTP/1.1 204 No Content",
           205 => "HTTP/1.1 205 Reset Content",
           206 => "HTTP/1.1 206 Partial Content",
           300 => "HTTP/1.1 300 Multiple Choices",
           301 => "HTTP/1.1 301 Moved Permanently",
           302 => "HTTP/1.1 302 Found",
           303 => "HTTP/1.1 303 See Other",
           304 => "HTTP/1.1 304 Not Modified",
           305 => "HTTP/1.1 305 Use Proxy",
           307 => "HTTP/1.1 307 Temporary Redirect",
           400 => "HTTP/1.1 400 Bad Request",
           401 => "HTTP/1.1 401 Unauthorized",
           402 => "HTTP/1.1 402 Payment Required",
           403 => "HTTP/1.1 403 Forbidden",
           404 => "HTTP/1.1 404 Not Found",
           405 => "HTTP/1.1 405 Method Not Allowed",
           406 => "HTTP/1.1 406 Not Acceptable",
           407 => "HTTP/1.1 407 Proxy Authentication Required",
           408 => "HTTP/1.1 408 Request Time-out",
           409 => "HTTP/1.1 409 Conflict",
           410 => "HTTP/1.1 410 Gone",
           411 => "HTTP/1.1 411 Length Required",
           412 => "HTTP/1.1 412 Precondition Failed",
           413 => "HTTP/1.1 413 Request Entity Too Large",
           414 => "HTTP/1.1 414 Request-URI Too Large",
           415 => "HTTP/1.1 415 Unsupported Media Type",
           416 => "HTTP/1.1 416 Requested range not satisfiable",
           417 => "HTTP/1.1 417 Expectation Failed",
           500 => "HTTP/1.1 500 Internal Server Error",
           501 => "HTTP/1.1 501 Not Implemented",
           502 => "HTTP/1.1 502 Bad Gateway",
           503 => "HTTP/1.1 503 Service Unavailable",
           504 => "HTTP/1.1 504 Gateway Time-out"       
       );
      
       header($http[$num]);
    }
}    

if (!is_callable('iso2ascii')) { 
    /* converts UTF-8 string phonetically to ASCII alphabet */
    function iso2ascii($title){
        if (empty($title)) return '';
        global $global_utf,$global_asc; 
        if (!isset($global_utf)) return $title;
        $i=0;
        foreach ($global_utf as $s) $res=str_replace(trim($s),trim($global_asc[$i++]),$title);
        //echo $res;
        return $res;
    }
}

if (!is_callable('toASCII')) {    
	/**
	 * Vytvori hezkou URL
	 * Platne hodnoty $charset = utf, iso, win
	 * @param	string	$str		retezec
	 * @param	string	$charset	kodovani
	 */
    function toASCII($str, $charset = 'utf')
    {
    	
    	
    	$str = str_replace('´', '\'', $str);
    	$str = str_replace('“', '\'', $str);
    	$str = str_replace('”', '\'', $str);
    	
    	
    	$charset = strtolower($charset);
    	switch($charset) { 
    		case 'utf':
    		case 'utf-8':
    		case 'utf8':
    			$nStr = cs_utf2ascii($str);
    			break;
    		case 'iso':
    		case 'iso-8859-2':
    		case 'latin2':
    			$nStr = cs_iso2ascii($str);
    			break;
    		case 'win':
    		case 'win-1250':
    		case 'windows-1250':
    			$nStr = cs_win2ascii($str);
    			break;
    	}
		//		$nStr = iso2ascii($str);
        
        $nStr = strtr($nStr, ' ()/.+*,\'"`%^&*', 
							 '---------------');
		$nStr = strtr($nStr, "\r\t\n", '---');
		
        $nStr = mb_strtolower($nStr);
        $nStr = preg_replace('~-+~', '-', $nStr);
        $nStr = preg_replace('~ +~', ' ', $nStr);
        
        do {
        	$len = strlen($nStr);        
        	$nStr = trim($nStr);
        	$nStr = trim($nStr, '-');
        } while (strlen($nStr) != $len);
        
        return $nStr;
    }
}

if (!is_callable('mail_content')) { 
      
    /**
     * 	Zaslani textu na zadanou adresu
     * 	@param	$title		predmet zpravy
     * 	@param	$text		text
     * 	@param	$to			adresat
     */
    function mail_content($title, $text, $to, $from = NULL) 
    {
        $eol = "\n";
        
        if (empty($from)) {
            $from = 'info@'.$_SERVER['HTTP_HOST'];
        }
        	
        //definice hlavicek
        $headers = "From: ".$from."\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\n";
        //$headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "Return-Path: ".$from."\n";
    
      //samotne odeslani
        if (!@mail($to, $title, $text, $headers)) {
            msg('Title: '.$title);
            msg('To: '.$to);
            echo '<pre>';
            msg('Text: '.NL.$text);
            echo '</pre>';
        }
    }
}

if (!is_callable('check_email')) { 
    
    
    /** Kontrola e-mailové adresy 
      * @param string $email e-mailová adresa 
      * @return bool syntaktická správnost adresy 
      */ 
    function check_email($email) { 
        $atom = '[-a-z0-9!#$%&\'*+/=?^_`{|}~]'; // znaky tvořící uživatelské jméno  
        $domain = '[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])'; // jedna komponenta domény  
        return eregi("^$atom+(\\.$atom+)*@($domain?\\.)+$domain\$", $email); 
    }
}

if (!is_callable('wasPost')) {
    
    function wasPost()
    {
        return count($_POST) > 1;
    }
}

if (!is_callable('dbNow')) { 
	function dbNow()
	{
		return date("Y-m-d H:i:s");
	}
}

if (!is_callable('friendly_url')) { 
	/** Vytvoření přátelského URL
	* @param string $nadpis řetězec v kódování UTF-8, ze kterého se má vytvořit URL
	* @return string řetězec obsahující pouze čísla, znaky bez diakritiky, podtržítko a pomlčku
	* @copyright Jakub Vrána, http://php.vrana.cz
	*/
	function friendly_url($nadpis)
	{
    	$url = $nadpis;
    	$url = preg_replace('~[^\\pL0-9_]+~u', '-', $url);
    	$url = trim($url, "-");
    	echo $url;
    	$url = iconv("utf-8", "us-ascii//TRANSLIT", $url);
    	echo $url;
    	$url = strtolower($url);
    	$url = preg_replace('~[^-a-z0-9_]+~', '', $url);
    	return $url;
	}
}

if (!is_callable('priceFormat')) { 
	/**
	 * Zmodifikuje retezec, aby odpovidal formatu ceny
	 * @param	float	cena
	 */
	function priceFormat($price = 0, $decimalPlaces = 2, 
		$decimalSeparator = ',', $thousandsSeparator = '&nbsp;')
	{
		if (!isset($decimalPlaces)) { 
			$decimalPlaces = 2;
		}
		
		if (!isset($decimalSeparator)) { 
			$decimalSeparator = ',';
		}
		
		if (!isset($thousandsSeparator)) { 
			$thousandsSeparator = '&nbsp;';
		}
		
		$str = number_format($price, $decimalSeparator, ':', '_');
		return str_replace(array(':', '_'), 
					array($decimalSeparator, $thousandsSeparator), 
					$str).',-';
	}
}  
/*
if (!is_callable('getMicroTime'))
{
	function getMicroTime()
	{
   		list($usec, $sec) = explode(" ",microtime());
   		return ((float)$usec + (float)$sec);
   	} 
}
*/	 

function esoErrorHandler($errno, $errstr, $errfile, $errline)
{
	/*
	static $f = NULL;
	static $errId = 0;
	$errId++;
	
	if (!$f) { 
		$f = fopen('/tmp/php-errors', 'w');
	}
	fwrite($f, $errId.') '.$errfile.':'.$errline."\n".$errno.': '.$errstr."\r\n");
	*/
	
	static $errLevel = array(
		E_ERROR => 'E_ERROR', 
		E_WARNING => 'E_WARNING', 
		E_PARSE => 'E_PARSING', 
		E_NOTICE => 'E_NOTICE', 
		E_CORE_ERROR => 'E_CORE_ERROR', 
		E_CORE_WARNING => 'E_CORE_WARNING', 
		E_COMPILE_ERROR => 'E_COMPILE_ERROR', 
		E_COMPILE_WARNING => 'E_COMPILE_WARNING', 
		E_USER_ERROR => 'E_USER_ERROR', 
		E_USER_WARNING => 'E_USER_WARNING', 
		E_USER_NOTICE => 'E_USER_NOTICE', 
		E_STRICT => 'E_STRICT', 
		E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR', 
		E_ALL		
	);
	
	switch ($errno) {
		case E_NOTICE:
		case E_STRICT: 
			// noticy => ignore
			break;	

    	default:
    		$str = $errfile.':'.$errline."\n".$errno.': '.$errstr;
    	    Logger::log(LOG_ERROR, $str); 
    		//echo 'aaaaaaa';
	}
}
?>