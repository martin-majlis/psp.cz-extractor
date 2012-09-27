CREATE TABLE IF NOT EXISTS `parlament_meeting` (
  `id` int(10)  NOT NULL ,
  `period` tinyint(3)  NOT NULL,
  `urlS` int(10)  NOT NULL,
  `urlO` tinyint(3)  NOT NULL, 
  PRIMARY KEY  (`id`)
);
CREATE TABLE IF NOT EXISTS `parlament_member` (
  `id` int(10)  NOT NULL ,
  `period` tinyint(3)  NOT NULL,
  `officialId` int(10)  NOT NULL,
  `partyId` int(10)  NOT NULL,
  `urlO` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ratio` double NOT NULL,
  `voteNo` int(11) NOT NULL,
  `voteYes` int(11) NOT NULL,
  `voteMissing` int(11) NOT NULL,
  `voteExcused` int(11) NOT NULL,
  `voteUnvote` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
);
CREATE TABLE IF NOT EXISTS `parlament_party` (
  `id` int(10)  NOT NULL ,
  `period` tinyint(3)  NOT NULL,
  `shortcut` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `color` char(6) NOT NULL,
  `alliance` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
);
CREATE TABLE IF NOT EXISTS `parlament_result` (
  `id` int(10)  NOT NULL ,
  `votingId` int(10)  NOT NULL,
  `memberId` int(10)  NOT NULL,
  `vote` tinyint(3)  NOT NULL,
  PRIMARY KEY  (`id`)
);
CREATE TABLE IF NOT EXISTS `parlament_voting` (
  `id` int(11) NOT NULL ,
  `period` tinyint(4) NOT NULL,
  `urlG` int(11) NOT NULL,
  `urlO` int(11) NOT NULL,
  `meetingId` int(11) NOT NULL,
  `pos` tinyint(4) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `total` tinyint(4) NOT NULL,
  `need` tinyint(4) NOT NULL,
  `a` tinyint(4) NOT NULL,
  `n` tinyint(4) NOT NULL,
  `res` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
);
CREATE INDEX "parlament_meeting_index" ON "parlament_meeting" (`period`,`urlS`);
CREATE INDEX "parlament_member_index" ON "parlament_member" (`period`,`officialId`,`partyId`);
CREATE INDEX "parlament_party_index" ON "parlament_party" (`period`,`shortcut`);
CREATE INDEX "parlament_result_index" ON "parlament_result" (`votingId`,`memberId`);
CREATE INDEX "parlament_voting_index" ON "parlament_voting" (`period`,`urlG`);