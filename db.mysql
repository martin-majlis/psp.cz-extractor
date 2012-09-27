DROP TABLE `parlament_meeting`, `parlament_member`, `parlament_party`, `parlament_result`, `parlament_voting`;

-- --------------------------------------------------------

-- 
-- Table structure for table `parlament_meeting`
-- 

CREATE TABLE IF NOT EXISTS `parlament_meeting` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `period` tinyint(3) unsigned NOT NULL,
  `urlS` int(10) unsigned NOT NULL,
  `urlO` tinyint(3) unsigned NOT NULL,  
  PRIMARY KEY  (`id`),
  UNIQUE KEY `period` (`period`,`urlS`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `parlament_member`
-- 


CREATE TABLE IF NOT EXISTS `parlament_member` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `period` tinyint(3) unsigned NOT NULL,
  `officialId` int(10) unsigned NOT NULL,
  `partyId` int(10) unsigned NOT NULL,
  `urlO` tinyint(4) NOT NULL,
  `name` varchar(50) collate utf8_czech_ci NOT NULL,
  `ratio` double NOT NULL,
  `voteNo` int(11) NOT NULL,
  `voteYes` int(11) NOT NULL,
  `voteMissing` int(11) NOT NULL,
  `voteExcused` int(11) NOT NULL,
  `voteUnvote` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `period` (`period`,`officialId`,`partyId`),
  KEY `partyId` (`partyId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `parlament_party`
-- 

CREATE TABLE IF NOT EXISTS `parlament_party` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `period` tinyint(3) unsigned NOT NULL,
  `shortcut` varchar(10) collate utf8_czech_ci NOT NULL,
  `name` varchar(100) collate utf8_czech_ci NOT NULL,
  `color` char(6) collate utf8_czech_ci NOT NULL,
  `alliance` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `period` (`period`,`shortcut`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `parlament_result`
-- 

CREATE TABLE IF NOT EXISTS `parlament_result` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `votingId` int(10) unsigned NOT NULL,
  `memberId` int(10) unsigned NOT NULL,
  `vote` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `votingId` (`votingId`,`memberId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

-- 
-- Table structure for table `parlament_voting`
-- 

CREATE TABLE IF NOT EXISTS `parlament_voting` (
  `id` int(11) NOT NULL auto_increment,
  `period` tinyint(4) NOT NULL,
  `urlG` int(11) NOT NULL,
  `urlO` int(11) NOT NULL,    
  `meetingId` int(11) NOT NULL,
  `pos` tinyint(4) NOT NULL,
  `name` varchar(255) collate utf8_czech_ci NOT NULL,
  `date` datetime NOT NULL,
  `total` tinyint(4) NOT NULL,
  `need` tinyint(4) NOT NULL,
  `a` tinyint(4) NOT NULL,
  `n` tinyint(4) NOT NULL,
  `res` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `period` (`period`,`urlG`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;
