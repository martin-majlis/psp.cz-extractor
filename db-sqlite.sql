CREATE TABLE IF NOT EXISTS `parlament_meeting` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT,
  `period` tinyint(3)  NOT NULL,
  `urlS` int(10)  NOT NULL,
  `urlO` tinyint(3)  NOT NULL
);
CREATE TABLE IF NOT EXISTS `parlament_member` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT,
  `period` tinyint(3)  NOT NULL,
  `officialId` int(10)  NOT NULL,
  `partyId` int(10)  NOT NULL,
  `urlO` tinyint(4) NOT NULL,
  `name` varchar(50) NOT NULL,
  `ratio` double,
  `voteNo` int(11),
  `voteYes` int(11),
  `voteMissing` int(11),
  `voteExcused` int(11),
  `voteUnvote` int(11)
);
CREATE TABLE IF NOT EXISTS `parlament_party` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT,
  `period` tinyint(3)  NOT NULL,
  `shortcut` varchar(10) NOT NULL,
  `name` varchar(100),
  `color` char(6) NOT NULL,
  `alliance` tinyint(1)
);
CREATE TABLE IF NOT EXISTS `parlament_result` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT,
  `votingId` int(10)  NOT NULL,
  `memberId` int(10)  NOT NULL,
  `vote` tinyint(3)  NOT NULL
);
CREATE TABLE IF NOT EXISTS `parlament_voting` (
  `id` INTEGER PRIMARY KEY  AUTOINCREMENT,
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
  `res` tinyint(4) NOT NULL
);
CREATE UNIQUE INDEX IF NOT EXISTS "parlament_meeting_index" ON "parlament_meeting" (`period`,`urlS`);
CREATE UNIQUE INDEX IF NOT EXISTS  "parlament_member_index" ON "parlament_member" (`period`,`officialId`,`partyId`);
CREATE UNIQUE INDEX IF NOT EXISTS  "parlament_party_index" ON "parlament_party" (`period`,`shortcut`);
CREATE UNIQUE INDEX IF NOT EXISTS  "parlament_result_index" ON "parlament_result" (`votingId`,`memberId`);
CREATE UNIQUE INDEX IF NOT EXISTS  "parlament_voting_index" ON "parlament_voting" (`period`,`urlG`);
