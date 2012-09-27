<?php
require_once('lib.php');

$cache = array(
	'party' =>  array(), 
	'people' => array()
);

function cacheLoad($url, $period)
{
	
	$f = DIR_CACHE_PAGES;
	if ( $period ) {
		$f .= DS . $period . DS; 
	}
	$f .= encodeUrl($url).'.html';
	
	if ( is_file($f) && filesize($f) == 0) { 
		unlink($f);	 
	}
	
	if ( ! is_file($f)) { 
		sleep(2);
		$page = loadUrl($url, 10);
		
		if (!$page) { 
			inf('Sleep: 5; URL: '.$url);
			sleep(5);
			$page = loadUrl($url, 10);
		}
		
		if (!$page) {
			inf('Sleep: 10; URL: '.$url); 
			sleep(10);
			$page = loadUrl($url, 10);
		}		
		
		file_put_contents($f, $page);
	}
	
	inf('Load URL: '.$url.', File: ' . $f . ', size: '.filesize($f));
	
	return file_get_contents($f);	
}



function storeVolebiObdobi(& $obdobi)
{
	$obdobi['url'] = sprintf(PSP_URL_OBDOBI, $obdobi['url']);

	connectDB($obdobi['period']);
	mkdir(DIR_CACHE_PAGES . DS . $obdobi['period'] . DS);

	$page = cacheLoad($obdobi['url'], $obdobi['period']);
	if ($page == '') { 
		inf('Error URL: '.$obdobi['url']);
	}	
	$terms = getTermList($page);
	getVotingList($terms, $obdobi);
	
}


/**
 * Ze stranky http://www.psp.cz/sqw/hlasovani.sqw?o=5 a podobnych ziska seznam 
 * jednotlivych URL, na kterych je mozne najit jednotlive schuze
 * 
 * <b><a href="hl.sqw?o=5&s=1">1.&nbsp;schůze</a></b> (<a href="hl.sqw?o=5&s=1&d=20060627">27.</a>, <a href="hl.sqw?o=5&s=1&d=20060629">29.&nbsp;června</a>, <a href="hl.sqw?o=5&s=1&d=20060714">14.</a>, <a href="hl.sqw?o=5&s=1&d=20060728">28.&nbsp;července</a>, <a href="hl.sqw?o=5&s=1&d=20060804">4.</a>, <a href="hl.sqw?o=5&s=1&d=20060814">14.</a>, <a href="hl.sqw?o=5&s=1&d=20060815">15.&nbsp;srpna&nbsp;2006</a>)<br />
 * @param unknown_type $page
 */
function getTermList($page)
{
	$res = preg_match_all('~<b><a href="hl.sqw\?o=([0-9]*)\&s=([0-9]*)">~U', $page, $match, PREG_SET_ORDER);
 	if (!$res) { 
			txtErr('Invalid pattern for url: '.$url, __FILE__, __LINE__);
	}	
	$term = array();
	foreach ($match as $k) { 
		$term[] = array(
			'o' => $k[1], 
			's' => $k[2], 
			'pg' => 1);
	}
	
	return $term;
}

/**
 * Vraci pole se vsemi hlasovanimi dane schuze
 * http://www.psp.cz/sqw/phlasa.sqw?HE=38&S=1&o=5
 */
function getVotingList(&$terms, & $obdobi)
{
	$votingList = array();

	$it = 0;
	$itC = count($terms);
	foreach ($terms as &$term) {
		if ( $term['s'] < $obdobi['startMeeting']) { 
			continue;
		}

		inf('['.$obdobi['period'].'] Meeting:'.$term['s'].' ('.$it.' / '.$itC.')'); 
		$url = sprintf(PSP_URL_SCHUZE, $term['o'], $term['s'], $term['pg']);
		$page = cacheLoad($url, $obdobi['period']);
		if ($page == '') { 
			inf('Error URL: '.$url);
		}		
		
		$res = preg_match('~HREF="phlasa\.sqw\?o=[0-9]+&s=[0-9]+&pg=([0-9]+)" class="last"~U', $page, $match);
		/* some pages do not contain pager
		if (!$res) {
			txtErr('Invalid pattern - extraction of page count', __FILE__, __LINE__);
		}
		*/
		$pageCount = max( $match[1], 1);

		saveDBTerm($term, $obdobi);

		$obdobi['maxMeeting'] = $term['s'];
		
		$startPage = 1;
		if ( $term['s'] == $obdobi['startMeeting'] ) { 
			$startPage = $obdobi['startPage'];
		}

		for ($i = $startPage; $i <= $pageCount; ++$i ) { 
			$term['pg'] = $i;
			if ( getVotingListPage($term, $obdobi) ) {
				$obdobi['maxPage'] = $i; 
				return 1;
			}
		}
		
		$it++;
	}
}

/**
 * Vraci obsah tabulky ze stranky: 
 * http://www.psp.cz/sqw/phlasa.sqw?HE=38&S=15&o=1
 *
 * @param unknown_type $term
 * @return unknown
 */
function getVotingListPage(&$term, $obdobi)
{
	$url = sprintf(PSP_URL_SCHUZE, $term['o'], $term['s'], $term['pg']);
	$page = cacheLoad($url, $obdobi['period']);
	if ($page == '') { 
		inf('Error URL: '.$url);
	}
	
	inf('['.$obdobi['period'].'] Page: '.$term['pg']);
	$res = preg_match_all('~<TR><TD[^>]+>(\d+)</TD><TD[^>]*><A HREF="hlasy.sqw\?g=(\d+)">(\d+)</A></TD><td[^>]*>([^<]*)</td><TD[^>]*>(.*)</TD><TD[^>]*>([^>]*>)?(\d+).&nbsp;(\d+).&nbsp;(\d+)(</a>)?</TD><TD>([^<]*)</td></tr>.*~uUi', 
	$page, $match, PREG_SET_ORDER);

 	if (!$res) { 
		txtErr('Invalid pattern for url: '.$url, __FILE__, __LINE__);
	}
	
	$it = 0;
	$itC = count($match);	
	foreach ($match as $v ) {
		
		$tS = strtotime($v[9].'-'.$v[8].'-'.$v[7]);
		if ( $tS < $obdobi['fromTS'] ) { 
			continue;
		} else if ( $tS > $obdobi['toTS'] ) {
			return 1; 
		}		
		
		inf('['.$obdobi['period'].'] Voting: '.$v[1].'.'.$v[3].' - '.$v[9].'-'.$v[8].'-'.$v[7].', ('.$it.' / '.$itC.')');
		
		
#		print_r($v);
		$voting = array(
			'schuze' => $v[1], 
			'urlG' => $v[2], 
			'urlO' => $term['o'], 
			'voting' => $v[3], 
			'name' => $v[5], 
			'date' => $v[9].'-'.$v[8].'-'.$v[7], 
			'res' => ($v[11] == 'Přijato' ? 1 : 0), 
			'term' => $term
		);

#		print_r($voting);

		storeVoting($voting, $obdobi);	
		$it++;	
	}
	
}


/**
 * Ulozi jednotlive poslance + vysledek hlasovani do DB
 * http://www.psp.cz/sqw/hlasy.sqw?G=6457&o=1
 * 
 * @param unknown_type $voting
 */
function storeVoting(& $voting, $obdobi)
{
	$url = sprintf(PSP_URL_VOTE, $voting['urlG'], $voting['urlO']);
	
	$page = cacheLoad($url, $obdobi['period']);
	if ($page == '') { 
		inf('Error URL: '.$url);
	}	
	
	$res = preg_match('~<h1[^>]*>.*, (\d+).&nbsp;([^.]+).&nbsp;(\d+), (\d+):(\d+).*</h1>.*~u', $page, $match);

	
 	if (!$res) { 
		txtErr('Invalid pattern for url: '.$url, __FILE__, __LINE__);
	}

	$voting['date'] .= ' '.$match[4].':'.$match[5].':00';

	$res = preg_match('~<p class="counts">Přítomno: <strong>(\d+)</strong>.*Je třeba: <strong>(\d+)</strong></p>~u', $page, $match);
 	if (!$res) { 
			txtErr('Invalid pattern for url: '.$url, __FILE__, __LINE__);
	}	
	$voting['total'] = $match[1];
	$voting['need'] = $match[2];

	# <p class="counts">Přítomno: <strong>175</strong> <span class="separator">&#124;</span> Je třeba: <strong>88</strong></p>                                <table summary="Celkový výsledek hlasování. U každé varianty je příznak, který je použit u jednotlivých poslanců níže.">                	<tr>    <TD class="first"><span class="flag yes"> A</span> Ano: <strong>53</strong></TD>    <TD><span class="flag no"> N</span> Ne: <strong>26</strong></TD>    <TD><span class="flag not-logged-in"> 0</span> Nepřihlášen</TD>        <TD><span class="flag refrained"> Z</span> Zdržel se</TD>    <td><span class="flag refrained"> X</span> Nehlasoval</td> 
	$res = preg_match('~<tr>    <TD class="first"><span class="flag yes"> A</span> Ano: <strong>(\d+)</strong></TD>    <TD><span class="flag no"> N</span> Ne: <strong>(\d+)</strong></TD>~u', $page, $match);
 	if (!$res) { 
		txtErr('Invalid pattern for url: '.$url, __FILE__, __LINE__);
	}
	
	$voting['a'] = $match[1];
	$voting['n'] = $match[2];
	
	saveDBVoting($voting, $obdobi);
	
	storeVotingResult($voting, $obdobi, $page);
	
}

function storeVotingResult(&$voting, $obdobi, &$page)
{
	global $cache;
	global $dbh;
	
	inf('['.$obdobi['period'].'] Store result: '.$voting['dbId']);
	
	
	#<h2 class="section-title center"><span>LSU (<span class="results"><span class="flag yes">A</span> Ano: <strong>3</strong><span class="flag no">N</span> Ne: <strong>6</strong><span class="flag not-logged-in">0</span> Nepřihlášen: <strong>1</strong><span class="flag refrained">Z</span> Zdržel se: <strong>5</strong></span>)</span></h2>
	#$res_party = preg_match_all('~class="section-title center"><span>([^<]+) \((<span class="results"><span class="flag yes">A</span> Ano: <strong>(\d+)</strong>)?(<span class="flag no">N</span> Ne: <strong>(\d+)</strong>)?(<span class="flag not-logged-in">0</span> Nepřihlášen: <strong>(\d+)</strong>)?(<span class="flag refrained">Z</span> Zdržel se: <strong>(\d+)</strong>)?</span>\)</span></h2><ul class="results">    <sqw_flush>    (<li><span class="flag ([^"]+)">(.*)</span> <a href="detail.sqw\?id=(\d+)&o=(\d+)(&l=cz)?">([^<]+)</a></li>)*(</ul>)?<h2~uUi',
	$res_parties = preg_match_all('~class="section-title center"><span>([^<]+) \(<span class="results">(<span class="flag yes">A</span> Ano: <strong>(\d+)</strong>)?(<span class="flag no">N</span> Ne: <strong>(\d+)</strong>)?(<span class="flag not-logged-in">0</span> Nepřihlášen: <strong>(\d+)</strong>)?(<span class="flag refrained">Z</span> Zdržel se: <strong>(\d+)</strong>)?</span>\)</span></h2><ul class="results">    <sqw_flush>(.*)<h2?~uUi',
			$page, $match_parties, PREG_SET_ORDER);
	
 	if (!$res_parties) { 
		txtErr('Invalid pattern', __FILE__, __LINE__);
	}
		
	foreach ($match_parties as $party) {
		$actParty = saveDBParty($obdobi['period'], trim($party[1]));
		$party[10] = trim($party[10]);
		if ( ! $party[10] ) {
			continue;
		}
		$res_members = preg_match_all('~<li><span class="flag ([^"]+)">([^<]*)</span> <a href="detail.sqw\?id=(\d+)&o=(\d+)(&l=cz)?">([^<]+)</a></li>~',
				$party[10], $match_members, PREG_SET_ORDER);

		if (!$res_members) {
			txtErr('Invalid pattern', __FILE__, __LINE__);
		}
		
		foreach ($match_members as $member) {
			$actMember = saveDBMember($member, $obdobi, $actParty);

			$res = $dbh->query('INSERT INTO '.DB_TB_RESULT.' (votingId, memberId, vote)
					VALUES
					('.$voting['dbId'].', '.$actMember.', '.votingCodeToNum($member[2]).')'
			);
		}
		
	}
}




function saveDBTerm(&$term, $obdobi)
{
	global $dbh;

	$res = $dbh->query('INSERT INTO '.DB_TB_MEETING.' 
		(period, urlS, urlO) VALUES 
		('.$obdobi['period'].', '.$term['s'].', '.$term['o'].')');
	
	if ($res) { 
		$term['dbId'] = $dbh->lastInsertId();
	} else {
		inf('SELECT id FROM '.DB_TB_MEETING.' WHERE period='.$obdobi['period'].' AND urlS='.$term['s']);
		$result = $dbh->query('SELECT id FROM '.DB_TB_MEETING.' WHERE period='.$obdobi['period'].' AND urlS='.$term['s'])->fetch(PDO::FETCH_ASSOC);
		print_r($result);
		$term['dbId'] = $result['id'];
	}

	if ( ! $term['dbId'] ) {
		txtErr('Something went wrong :)', __FILE__, __LINE__);
	}
}

function saveDBVoting(&$voting, $obdobi)
{
	global $dbh;
	
	$sql = 'INSERT INTO '.DB_TB_VOTING.' 
	(period, urlG, urlO, meetingId, pos, name, `date`, total, need, a, n, res) 
	VALUES 
	('.$obdobi['period'].', '.$voting['urlG'].', '.$voting['urlO'].', '.$voting['term']['dbId'].', '. 
		$voting['voting'].', "'.$voting['name'].'", "'.$voting['date'].'", 
		'.$voting['total'].', '.$voting['need'].', 
		'.$voting['a'].', '.$voting['n'].', '.$voting['res'].')';

	$res = $dbh->query($sql);
		
		
	if ($res) { 
		$voting['dbId'] = $dbh->lastInsertId();
	} else { 
		$result = $dbh->query('SELECT id FROM '.DB_TB_VOTING.' WHERE period='.$obdobi['period'].' AND urlG='.$voting['urlG'])->fetch(PDO::FETCH_ASSOC);
		$voting['dbId'] = $result['id'];
	}

	if ( ! $voting['dbId'] ) {
		txtErr('Something went wrong :)', __FILE__, __LINE__);
	}
}

function saveDBParty($p, $party)
{
	global $cache;
	global $dbh;

	if (isset($cache['party'][$p][$party])) {
		return  $cache['party'][$p][$party];
	}
	
	$r = rand(0, 15);
	$g = rand(0, 15);
	$b = rand(0, 15);
	
	if ($r == 0 && $g == 0 && $b == 0 ) { 
		$r = rand(0, 15);
		$g = rand(0, 15);
		$b = rand(0, 15);		
	}
	
	$res = $dbh->query('INSERT INTO '.DB_TB_PARTY.' 
		(period, shortcut, color) VALUES
		('.$p.', "'.$party.'", "'.dechex($r).dechex($r).dechex($g).dechex($g).dechex($b).dechex($b).'")');
	if ($res) { 
		inf('Add party: '.$party);
		$pId = $dbh->lastInsertId();
	} else {
		$result = $dbh->query('SELECT id FROM '.DB_TB_PARTY.' WHERE period='.$p.' AND shortcut="'.$party.'"')->fetch(PDO::FETCH_ASSOC);
		$pId = $result['id'];
	}

	if ( ! $pId ) {
		txtErr('Something went wrong :)', __FILE__, __LINE__);
	}

	$cache['party'][$p][$party] = $pId;
	return $pId;	
}

function saveDBMember(& $member, $obdobi, $partyId)
{
	global $cache;
	global $dbh;

	if (isset($cache['member'][$obdobi['period']][$member[3]][$partyId])) {
		$member['dbId'] = $cache['member'][$obdobi['period']][$member[3]][$partyId];
		return  $cache['member'][$obdobi['period']][$member[3]][$partyId];
	}

	$member[6] = html_entity_decode($member[6], ENT_COMPAT, 'UTF-8');
	
	$res = $dbh->query('INSERT INTO '.DB_TB_MEMBER.'  
	 	(period, officialId, partyId, urlO, name) VALUES 
	 	('.$obdobi['period'].', '.$member[3].', '.$partyId.', '.$member[4].', "'.$member[6].'")');
	
	if ( $res ) { 
		$mId = $dbh->lastInsertId();
	} else { 
		$result = $dbh->query('SELECT id FROM '.DB_TB_MEMBER.' WHERE period='.$obdobi['period'].' AND officialId='.$member[3].' AND partyId='.$partyId)->fetch(PDO::FETCH_ASSOC);
		$mId = $result['id'];
	}

	
	if ( ! $mId ) {
		txtErr('Something went wrong :)', __FILE__, __LINE__);
	}
	
	$cache['member'][$obdobi['period']][$member[3]][$partyId] = $mId;
	$member['dbId'] = $cache['member'][$obdobi['period']][$member[3]][$partyId];

	return $mId;
}


function votingCodeToNum($code)
{

	switch ($code) { 
		case 'A':
			return VOTE_YES;
		case 'N':
			return VOTE_NO;
		case '0':
			return VOTE_MISSING;
		case 'M':
			return VOTE_EXCUSED;
		case 'Z':
			return VOTE_FORBORED;
		case 'X':
			return VOTE_UNVOTE;
		default:
			inf("Unknown voting code: ".$code);
	}
}
?>
