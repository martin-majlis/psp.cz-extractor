<?php
/**
 * Nacte data z webu poslanecke snemovny a ulozi je do DB
 */
require_once('getDataLib.php');

$lUrl = null;

foreach ($vlady as & $v) {
	inf('Period: '.$v['name'].' - BEGIN');
	$f = 'get-'.sprintf("%02d", $v['period']).'.done';
	if ( is_file($f)) {
		inf('Already done'); 
	} else { 
		$v['fromTS'] = strtotime($v['from']);
		$v['toTS'] = strtotime($v['to']);
		
		if ($v['url'] == $lUrl) { 
			$v['startMeeting'] = $lMeeting;
			$v['startPage'] = $lPage;
		}
		storeVolebiObdobi($v);
		file_put_contents($f, 'Done :)');
		
		$lUrl = $v['url'];
		$lMeeting = $v['maxMeeting'];
		$lPage = $v['maxPage'];
		
	}
	inf('Period: '.$v['name'].' - FINISHED');
}





?>
